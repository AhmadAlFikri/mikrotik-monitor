<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Support\Facades\Cache;

class MikrotikService
{
    public static function active($ip, $user, $pass)
    {
        $api = new Client([
            'host' => $ip,
            'user' => $user,
            'pass' => $pass,
            'timeout' => 5,
        ]);

        // === INTERVAL REFRESH (HARUS SAMA DENGAN JS) ===
        $interval = 3; // detik

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ HOTSPOT ACTIVE (PAKSA FIELD LENGKAP)
        |--------------------------------------------------------------------------
        */
        $activeQuery = new Query('/ip/hotspot/active/print');
        $activeQuery->equal(
            '.proplist',
            '.id,user,address,mac-address,server,domain,uptime,idle-time,session-time-left,login-by'
        );

        $active = $api->query($activeQuery)->read();

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ FIREWALL CONNECTION (SUMBER RX / TX)
        |--------------------------------------------------------------------------
        */
        $connQuery = new Query('/ip/firewall/connection/print');
        $connQuery->equal('.proplist', 'src-address,rx,tx');

        $connections = $api->query($connQuery)->read();

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ MERGE + HITUNG RATE (SEPERTI WINBOX)
        |--------------------------------------------------------------------------
        */
        return collect($active)->map(function ($row) use ($connections, $ip, $interval) {

            // === TOTAL BYTES SEKARANG ===
            $rxBytes = 0;
            $txBytes = 0;

            foreach ($connections as $c) {
                if (
                    isset($row['address'], $c['src-address']) &&
                    $row['address'] === $c['src-address']
                ) {
                    $rxBytes += (int) ($c['rx'] ?? 0);
                    $txBytes += (int) ($c['tx'] ?? 0);
                }
            }

            // === CACHE KEY ===
            $rxKey = "rx_prev_{$ip}_{$row['address']}";
            $txKey = "tx_prev_{$ip}_{$row['address']}";

            $prevRx = Cache::get($rxKey, $rxBytes);
            $prevTx = Cache::get($txKey, $txBytes);

            // === HITUNG RATE (BPS) ===
            $rxRate = max(0, ($rxBytes - $prevRx) / $interval);
            $txRate = max(0, ($txBytes - $prevTx) / $interval);

            // SIMPAN BYTES TERBARU
            Cache::put($rxKey, $rxBytes, 60);
            Cache::put($txKey, $txBytes, 60);

            return [
                'user' => $row['user'] ?? '-',
                'address' => $row['address'] ?? '-',
                'mac' => $row['mac-address'] ?? '-',
                'server' => $row['server'] ?? '-',
                'domain' => $row['domain'] ?? '-',
                'uptime' => $row['uptime'] ?? '-',
                'idle_time' => $row['idle-time'] ?? '-',
                'session_time_left' => $row['session-time-left'] ?? '-',

                // 🔥 REALTIME RATE (BPS)
                'rx_rate' => round($rxRate, 2),
                'tx_rate' => round($txRate, 2),

                'login_by' => $row['login-by'] ?? '-',
            ];
        })->values();
    }
}

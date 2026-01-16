<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Support\Facades\Cache;

class MikrotikService
{
    /**
     * Ambil user hotspot aktif + hitung realtime RX/TX rate
     * Rate dihitung dari selisih bytes / interval (detik)
     */
    public static function active(string $ip, string $user, string $pass, string $sort_column = 'uptime', string $sort_direction = 'desc')
    {
        // === KONEKSI API MIKROTIK ===
        $api = new Client([
            'host'    => $ip,
            'user'    => $user,
            'pass'    => $pass,
            'timeout' => 5,
        ]);

        // ⚠️ HARUS SAMA DENGAN JS (polling interval)
        $interval = 1; // detik

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ AMBIL HOTSPOT ACTIVE (FIELD LENGKAP)
        |--------------------------------------------------------------------------
        */
        $query = new Query('/ip/hotspot/active/print');
        $query->equal(
            '.proplist',
            '.id,user,address,mac-address,server,domain,uptime,idle-time,session-time-left,bytes-in,bytes-out,login-by'
        );

        $activeUsers = $api->query($query)->read();

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ HITUNG RATE REALTIME (SEPERTI WINBOX)
        |--------------------------------------------------------------------------
        */
        $mappedUsers = collect($activeUsers)->map(function ($row) use ($ip, $interval) {

            // === IDENTITAS UNIK USER (LEBIH AMAN DARI ADDRESS SAJA) ===
            $identity = md5(
                ($row['address'] ?? '-') .
                ($row['mac-address'] ?? '-') .
                ($row['user'] ?? '-')
            );

            // === TOTAL BYTES SEKARANG ===
            $rxBytes = (int) ($row['bytes-in'] ?? 0);
            $txBytes = (int) ($row['bytes-out'] ?? 0);

            // === CACHE KEY ===
            $rxKey = "mt_rx_prev_{$ip}_{$identity}";
            $txKey = "mt_tx_prev_{$ip}_{$identity}";

            // === BYTES SEBELUMNYA ===
            $prevRx = Cache::get($rxKey, $rxBytes);
            $prevTx = Cache::get($txKey, $txBytes);

            // === HITUNG RATE (BYTES PER DETIK) ===
            $rxRate = ($rxBytes - $prevRx) / $interval;
            $txRate = ($txBytes - $prevTx) / $interval;

            // === ANTI NILAI NEGATIF (RECONNECT / RESET COUNTER) ===
            $rxRate = $rxRate < 0 ? 0 : $rxRate;
            $txRate = $txRate < 0 ? 0 : $txRate;

            // === SIMPAN BYTES TERBARU KE CACHE ===
            // TTL 60 detik cukup aman
            Cache::put($rxKey, $rxBytes, 60);
            Cache::put($txKey, $txBytes, 60);

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ RETURN DATA KE DASHBOARD
            |--------------------------------------------------------------------------
            */
            return [
                'id'                 => $row['.id'], // ‼️ PENTING untuk kick user
                'user'               => $row['user'] ?? '-',
                'address'            => $row['address'] ?? '-',
                'mac'                => $row['mac-address'] ?? '-',
                'server'             => $row['server'] ?? '-',
                'domain'             => $row['domain'] ?? '-',
                'uptime'             => $row['uptime'] ?? '-',
                'idle_time'          => $row['idle-time'] ?? '-',
                'session_time_left'  => $row['session-time-left'] ?? '-',

                // TOTAL BYTES
                'bytes_in'           => $rxBytes,
                'bytes_out'          => $txBytes,

                // 🔥 REALTIME RATE (Bps)
                'rx_rate'            => round($rxRate, 2),
                'tx_rate'            => round($txRate, 2),

                'login_by'           => $row['login-by'] ?? '-',
            ];
        });

        // SORTING
        if ($sort_direction === 'asc') {
            $sortedUsers = $mappedUsers->sortBy($sort_column);
        } else {
            $sortedUsers = $mappedUsers->sortByDesc($sort_column);
        }

        return $sortedUsers->values();
    }

    public static function kickUser(string $ip, string $user, string $pass, string $userId)
    {
        try {
            $api = new Client([
                'host'    => $ip,
                'user'    => $user,
                'pass'    => $pass,
                'timeout' => 5,
            ]);

            $query = new Query('/ip/hotspot/active/remove');
            $query->equal('.id', $userId);

            $api->query($query)->read();

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

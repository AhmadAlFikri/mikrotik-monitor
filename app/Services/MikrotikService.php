<?php

namespace App\Services;

use App\Models\SessionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use RouterOS\Client;
use RouterOS\Query;

class MikrotikService
{
    /**
     * Ambil user hotspot aktif + hitung realtime RX/TX rate
     * Rate dihitung dari selisih bytes / interval (detik)
     */
    public static function active(string $ip, string $user, string $pass, string $routerName, string $sort_column = 'uptime', string $sort_direction = 'desc')
    {
        try {
            // === KONEKSI API MIKROTIK ===
            $api = new Client([
                'host' => $ip,
                'user' => $user,
                'pass' => $pass,
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
            | 2️⃣ LOG SESSION PENGGUNA
            |--------------------------------------------------------------------------
            */
            $sessionCacheKey = "active_sessions_{$ip}";
            $previousSessions = Cache::get($sessionCacheKey, []);
            $currentSessions = [];

            foreach ($activeUsers as $activeUser) {
                $currentSessions[$activeUser['.id']] = [
                    'user' => $activeUser['user'],
                    'uptime' => $activeUser['uptime'],
                ];
            }

            $loggedOutUsers = array_diff_key($previousSessions, $currentSessions);

            foreach ($loggedOutUsers as $id => $session) {
                $uptimeSeconds = self::parseUptime($session['uptime']);
                $loginTime = Carbon::now()->subSeconds($uptimeSeconds);

                SessionLog::create([
                    'username' => $session['user'],
                    'router_name' => $routerName,
                    'login_time' => $loginTime,
                    'logout_time' => Carbon::now(),
                ]);
            }

            Cache::put($sessionCacheKey, $currentSessions, now()->addMinutes(5));

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ HITUNG RATE REALTIME (SEPERTI WINBOX)
            |--------------------------------------------------------------------------
            */
            $mappedUsers = collect($activeUsers)->map(function ($row) use ($ip, $interval) {

                // === IDENTITAS UNIK USER (LEBIH AMAN DARI ADDRESS SAJA) ===
                $identity = md5(
                    ($row['address'] ?? '-').
                    ($row['mac-address'] ?? '-').
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
                | 4️⃣ RETURN DATA KE DASHBOARD
                |--------------------------------------------------------------------------
                */
                return [
                    'id' => $row['.id'], // ‼️ PENTING untuk kick user
                    'user' => $row['user'] ?? '-',
                    'address' => $row['address'] ?? '-',
                    'mac' => $row['mac-address'] ?? '-',
                    'server' => $row['server'] ?? '-',
                    'domain' => $row['domain'] ?? '-',
                    'uptime' => $row['uptime'] ?? '-',
                    'idle_time' => $row['idle-time'] ?? '-',
                    'session_time_left' => $row['session-time-left'] ?? '-',

                    // TOTAL BYTES
                    'bytes_in' => $rxBytes,
                    'bytes_out' => $txBytes,

                    // 🔥 REALTIME RATE (Bps)
                    'rx_rate' => round($rxRate, 2),
                    'tx_rate' => round($txRate, 2),

                    'login_by' => $row['login-by'] ?? '-',
                ];
            });

            // SORTING
            if ($sort_direction === 'asc') {
                $sortedUsers = $mappedUsers->sortBy($sort_column);
            } else {
                $sortedUsers = $mappedUsers->sortByDesc($sort_column);
            }

            return $sortedUsers->values();
        } catch (\Exception $e) {
            // JIKA KONEKSI GAGAL, RETURN ERROR MESSAGE
            return ['error' => $e->getMessage()];
        }
    }

    private static function parseUptime(string $uptime): int
    {
        $totalSeconds = 0;
        preg_match_all('/(\d+)([wdhms])/', $uptime, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $value = (int) $match[1];
            $unit = $match[2];
            switch ($unit) {
                case 'w':
                    $totalSeconds += $value * 604800;
                    break;
                case 'd':
                    $totalSeconds += $value * 86400;
                    break;
                case 'h':
                    $totalSeconds += $value * 3600;
                    break;
                case 'm':
                    $totalSeconds += $value * 60;
                    break;
                case 's':
                    $totalSeconds += $value;
                    break;
            }
        }

        return $totalSeconds;
    }

    public static function getInterfaces(string $ip, string $user, string $pass, string $sort_column = 'name', string $sort_direction = 'asc')
    {
        try {
            $api = new Client([
                'host' => $ip,
                'user' => $user,
                'pass' => $pass,
                'timeout' => 5,
            ]);

            $query = new Query('/interface/print');
            $query->equal('.proplist', '.id,name,type,mac-address,last-link-up-time,running,disabled,rx-byte,tx-byte');

            $interfaces = $api->query($query)->read();

            // Ambil traffic per interface
            $trafficQuery = new Query('/interface/monitor-traffic');
            $trafficQuery->equal('interface', collect($interfaces)->pluck('.id')->implode(','));
            $trafficQuery->equal('once', 'true');
            $traffic = $api->query($trafficQuery)->read();

            // Gabungkan traffic ke data interface
            $mappedInterfaces = collect($interfaces)->map(function ($interface) use ($traffic) {
                $interfaceTraffic = collect($traffic)->firstWhere('name', $interface['name']);
                $interface['rx-rate'] = $interfaceTraffic['rx-bits-per-second'] ?? 0;
                $interface['tx-rate'] = $interfaceTraffic['tx-bits-per-second'] ?? 0;
                // Cast bytes to integers for correct sorting
                $interface['rx-byte'] = (int) ($interface['rx-byte'] ?? 0);
                $interface['tx-byte'] = (int) ($interface['tx-byte'] ?? 0);

                return $interface;
            });

            // Lakukan sorting
            $isNumericSort = in_array($sort_column, ['rx-byte', 'tx-byte']);
            $sortedInterfaces = $mappedInterfaces->sortBy($sort_column, $isNumericSort ? SORT_NUMERIC : SORT_REGULAR, $sort_direction === 'desc');

            return $sortedInterfaces->values()->toArray();

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public static function kickUser(string $ip, string $user, string $pass, string $userId)
    {
        try {
            $api = new Client([
                'host' => $ip,
                'user' => $user,
                'pass' => $pass,
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

    public static function getOverallTraffic(string $ip, ?string $user, ?string $pass, string $interface = 'ether1')
    {
        try {
            $api = new Client([
                'host' => $ip,
                'user' => $user,
                'pass' => $pass,
                'timeout' => 5,
            ]);

            $trafficQuery = new Query('/interface/monitor-traffic');
            $trafficQuery->equal('interface', $interface);
            $trafficQuery->equal('once', 'true');
            $traffic = $api->query($trafficQuery)->read();

            if (empty($traffic)) {
                return ['error' => "Interface {$interface} not found or no traffic data."];
            }

            return [
                'rx_rate' => $traffic[0]['rx-bits-per-second'] ?? 0,
                'tx_rate' => $traffic[0]['tx-bits-per-second'] ?? 0,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

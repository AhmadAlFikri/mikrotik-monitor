<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrafficStat;
use App\Models\HourlyTrafficSummary;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SummarizeHourlyTraffic extends Command
{
    protected $signature = 'traffic:summarize-hourly';
    protected $description = 'Summarize raw traffic stats into hourly averages';

    public function handle()
    {
        $this->info('Starting hourly traffic summarization...');

        // Tentukan rentang waktu: 1 jam yang lalu, dibulatkan ke jam
        $endTime = now()->startOfHour();
        $startTime = $endTime->copy()->subHour();

        // Menggunakan nama kolom yang benar: 'user' dan 'N/A' untuk 'interface'
        $hourlyStats = TrafficStat::whereBetween('created_at', [$startTime, $endTime])
            ->select(
                'user',
                DB::raw("'N/A' as interface"), // Mengisi `interface` dengan 'N/A'
                DB::raw('AVG(rx_rate) as avg_rx'),
                DB::raw('AVG(tx_rate) as avg_tx')
            )
            ->groupBy('user')
            ->get();

        if ($hourlyStats->isEmpty()) {
            $this->info('No traffic stats to summarize for the period: ' . $startTime->toDateTimeString() . ' to ' . $endTime->toDateTimeString());
            return 0; // Menggunakan return code 0 untuk sukses tanpa data
        }

        $this->info("Found {$hourlyStats->count()} records to summarize.");

        // Simpan hasil agregasi ke tabel baru
        foreach ($hourlyStats as $stat) {
            HourlyTrafficSummary::updateOrCreate(
                [
                    'user_name'      => $stat->user, // Menggunakan $stat->user dari query
                    'interface_name' => $stat->interface, // Akan berisi 'N/A'
                    'recorded_at'    => $startTime,
                ],
                [
                    'avg_rx_rate'    => round($stat->avg_rx),
                    'avg_tx_rate'    => round($stat->avg_tx),
                ]
            );
        }

        $this->info("Successfully summarized {$hourlyStats->count()} user traffic records.");
        return 0;
    }
}

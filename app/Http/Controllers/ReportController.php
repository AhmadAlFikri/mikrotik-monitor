<?php

namespace App\Http\Controllers;

use App\Models\TrafficStat;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function monthly()
    {
        $data = TrafficStat::select(
            DB::raw('MONTH(stat_date) as month'),
            DB::raw('AVG(rx_rate) as avg_rx'),
            DB::raw('AVG(tx_rate) as avg_tx')
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return view('report.monthly', compact('data'));
    }
}


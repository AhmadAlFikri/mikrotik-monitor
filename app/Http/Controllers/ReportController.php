<?php

namespace App\Http\Controllers;

use App\Models\SessionLog;
use App\Models\TrafficStat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SessionLogsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $filter = $request->query('filter', '1M');
        $selectedUser = $request->query('user');

        $users = TrafficStat::select('user')->distinct()->pluck('user');

        $query = TrafficStat::query();
        $selectRaw = '';
        $groupBy = '';
        $orderBy = '';

        $endDate = Carbon::now();
        $startDate = null;

        if ($selectedUser && $selectedUser !== 'all') {
            $query->where('user', $selectedUser);
        }

        switch ($filter) {
            case '1D':
                $startDate = $endDate->copy()->subDay();
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m-%d %H:00") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '5D':
                $startDate = $endDate->copy()->subDays(5);
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m-%d") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '1M':
                $startDate = $endDate->copy()->subMonth();
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '3M':
                $startDate = $endDate->copy()->subMonths(3);
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '6M':
                $startDate = $endDate->copy()->subMonths(6);
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '1Y':
                $startDate = $endDate->copy()->subYear();
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case '5Y':
                $startDate = $endDate->copy()->subYears(5);
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
            case 'All':
            default:
                $selectRaw = 'DATE_FORMAT(stat_date, "%Y-%m") as label, AVG(rx_rate) as avg_rx, AVG(tx_rate) as avg_tx';
                $groupBy = 'label';
                $orderBy = 'label';
                break;
        }

        if ($startDate) {
            $query->whereBetween('stat_date', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        $data = $query->select(DB::raw($selectRaw))
                      ->groupBy(DB::raw($groupBy))
                      ->orderBy(DB::raw($orderBy))
                      ->get();

        return view('report.monthly', compact('data', 'users', 'selectedUser', 'filter'));
    }

    public function sessionLogs()
    {
        $sessionLogs = SessionLog::orderBy('login_time', 'desc')->paginate(15);
        return view('report.sessions', compact('sessionLogs'));
    }

    public function exportExcel()
    {
        return Excel::download(new SessionLogsExport, 'session-logs.xlsx');
    }

    public function exportPdf()
    {
        $sessionLogs = SessionLog::orderBy('login_time', 'desc')->get();
        $pdf = Pdf::loadView('report.sessions_pdf', compact('sessionLogs'));
        return $pdf->download('session-logs.pdf');
    }
}


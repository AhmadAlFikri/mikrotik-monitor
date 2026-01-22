<?php

namespace App\Http\Controllers;

use App\Exports\SessionLogsExport;
use App\Models\SessionLog;
use App\Models\TrafficStat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function sessionLogs(Request $request)
    {
        $query = SessionLog::query();

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('login_time', [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'login_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sessionLogs = $query->paginate(15);

        return view('report.sessions', compact('sessionLogs', 'sortBy', 'sortOrder'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SessionLogsExport($request), 'session-logs.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = SessionLog::query();

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('login_time', [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'login_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sessionLogs = $query->get();
        $pdf = Pdf::loadView('report.sessions_pdf', compact('sessionLogs'));

        return $pdf->download('session-logs.pdf');
    }
}

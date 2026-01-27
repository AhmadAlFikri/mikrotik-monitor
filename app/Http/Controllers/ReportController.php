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
        $endDate = now();
        $startDate = now(); // Placeholder, akan ditimpa

        // Atur query untuk tabel (umumnya per hari)
        $tableSelectRaw = 'DATE_FORMAT(recorded_at, "%Y-%m-%d") as label, AVG(avg_rx_rate) as avg_rx, AVG(avg_tx_rate) as avg_tx';
        $tableGroupBy = 'label';

        // Atur query untuk grafik (bisa berbeda)
        $chartSelectRaw = $tableSelectRaw;
        $chartGroupBy = $tableGroupBy;

        switch ($filter) {
            case '1D':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                $selectRaw = 'DATE_FORMAT(recorded_at, "%Y-%m-%d %H:00:00") as label, AVG(avg_rx_rate) as avg_rx, AVG(avg_tx_rate) as avg_tx';
                $tableSelectRaw = $selectRaw;
                $chartSelectRaw = $selectRaw;
                $tableGroupBy = 'label';
                $chartGroupBy = 'label';
                break;
            case '1W':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                // Tabel dan Grafik sama-sama per hari
                break;
            case '1M':
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                // Tabel dan Grafik sama-sama per hari
                break;
            case '3M':
                $startDate = now()->subMonths(2)->startOfMonth();
                $endDate = now()->endOfMonth();
                // Tabel per hari (menggunakan default), Grafik per minggu
                $chartSelectRaw = 'DATE(recorded_at - INTERVAL (WEEKDAY(recorded_at)) DAY) as label, AVG(avg_rx_rate) as avg_rx, AVG(avg_tx_rate) as avg_tx';
                $chartGroupBy = 'label';
                break;
            case '1Y':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                // Tabel per hari, Grafik per bulan
                $chartSelectRaw = 'DATE_FORMAT(recorded_at, "%Y-%m-01") as label, AVG(avg_rx_rate) as avg_rx, AVG(avg_tx_rate) as avg_tx';
                $chartGroupBy = 'label';
                break;
        }

        // --- Query Dasar ---
        $baseQuery = \App\Models\HourlyTrafficSummary::query()->whereBetween('recorded_at', [$startDate, $endDate]);
        $selectedUser = $request->query('user');
        if ($selectedUser && $selectedUser !== 'all') {
            $baseQuery->where('user_name', $selectedUser);
        }

        // --- Ambil Data untuk Tabel ---
        $tableQuery = clone $baseQuery;
        $tableData = $tableQuery->select(DB::raw($tableSelectRaw))->groupBy(DB::raw($tableGroupBy))->orderBy('label', 'asc')->get();

        // --- Ambil Data untuk Grafik ---
        $chartQuery = clone $baseQuery;
        $chartData = $chartQuery->select(DB::raw($chartSelectRaw))->groupBy(DB::raw($chartGroupBy))->orderBy('label', 'asc')->get();

        // --- Perhitungan Total (berdasarkan query dasar) ---
        $totalQuery = clone $baseQuery;
        $sumOfAverages = $totalQuery->select(DB::raw('SUM(avg_rx_rate) as total_avg_rx_rate, SUM(avg_tx_rate) as total_avg_tx_rate'))->first();
        $totalRxBytes = (($sumOfAverages->total_avg_rx_rate ?? 0) / 8) * 3600;
        $totalTxBytes = (($sumOfAverages->total_avg_tx_rate ?? 0) / 8) * 3600;

        $users = \App\Models\HourlyTrafficSummary::select('user_name')->distinct()->pluck('user_name');

        return view('report.monthly', [
            'tableData' => $tableData,
            'chartData' => $chartData,
            'users' => $users,
            'selectedUser' => $selectedUser,
            'filter' => $filter,
            'totalRxBytes' => $totalRxBytes,
            'totalTxBytes' => $totalTxBytes,
        ]);
    }

    public function sessionLogs(Request $request)
    {
        $query = SessionLog::query();

        // Filter by date range
        if ($request->has('start_date') && $request->filled('start_date') && $request->has('end_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('login_time', [$startDate, $endDate]);
        }

        // Get the total count after filtering
        $totalLogs = $query->count();

        // Sorting
        $sortBy = $request->get('sort_by', 'login_time');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sessionLogs = $query->paginate(15);

        return view('report.sessions', compact('sessionLogs', 'sortBy', 'sortOrder', 'totalLogs'));
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

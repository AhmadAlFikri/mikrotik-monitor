<?php

namespace App\Exports;

use App\Models\SessionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SessionLogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = SessionLog::query();

        // Filter by date range
        if ($this->request->has('start_date') && $this->request->has('end_date')) {
            $startDate = Carbon::parse($this->request->start_date)->startOfDay();
            $endDate = Carbon::parse($this->request->end_date)->endOfDay();
            $query->whereBetween('login_time', [$startDate, $endDate]);
        }

        // Sorting
        $sortBy = $this->request->get('sort_by', 'login_time');
        $sortOrder = $this->request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Username',
            'Router Name',
            'Login Time',
            'Logout Time',
            'Duration',
        ];
    }

    /**
     * @param  mixed  $log
     */
    public function map($log): array
    {
        return [
            $log->username,
            $log->router_name,
            Carbon::parse($log->login_time)->format('d M Y, H:i:s'),
            Carbon::parse($log->logout_time)->format('d M Y, H:i:s'),
            Carbon::parse($log->logout_time)->diffForHumans(Carbon::parse($log->login_time), true),
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\SessionLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class SessionLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SessionLog::orderBy('login_time', 'desc')->get();
    }

    /**
    * @return array
    */
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
    * @param mixed $log
    *
    * @return array
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

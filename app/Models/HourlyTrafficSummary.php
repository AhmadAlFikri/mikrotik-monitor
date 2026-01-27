<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyTrafficSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'interface_name',
        'avg_rx_rate',
        'avg_tx_rate',
        'recorded_at',
    ];
}

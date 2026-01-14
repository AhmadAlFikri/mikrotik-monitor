<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficStat extends Model
{
    protected $fillable = [
        'router_id',
        'user',
        'rx_rate',
        'tx_rate',
        'stat_date'
    ];
}

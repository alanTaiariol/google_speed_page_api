<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;


    public function metricHistoryRun()
    {
        return $this->belongsTo(MetricHistoryRun::class, 'strategy_id');
    }
}

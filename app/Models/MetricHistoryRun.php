<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricHistoryRun extends Model
{
    // use HasFactory;

    protected $fillable = [
        'url',
        'accesibility_metric',
        'pwa_metric',
        'performance_metric',
        'seo_metric',
        'best_practices_metric',
        'strategy_id'
    ];

    public function strategy()
    {
        return $this->hasOne(Strategy::class, "id", "strategy_id");
    }
}

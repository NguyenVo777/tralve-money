<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = [
        'country',
        'currency_code',
        'currency_name',
        'rate_to_usd',
        'flag_icon',
        'change_percentage',
        'status',
        'is_popular',
    ];

    public function history()
    {
        return $this->hasMany(ExchangeRateHistory::class);
    }
}

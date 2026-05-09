<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'from_currency',
        'to_currency',
        'amount',
        'result',
        'rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'total_amount',
        'total_payment',
        'balance',
        'start_date',
        'end_date',
        'installments_count',
        'installments_amount',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'installment_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'note',
        'attachment',
        'status',
    ];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}

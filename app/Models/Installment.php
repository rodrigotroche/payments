<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'installments';

    protected $fillable = [
        'account_id',
        'installment_number',
        'amount',
        'due_date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

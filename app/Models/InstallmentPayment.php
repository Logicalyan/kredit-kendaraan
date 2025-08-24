<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    protected $fillable = [
        'installment_id', 'payer_id', 'paid_at',
        'amount_paid', 'method', 'reference_number', 'notes'
    ];

    // 🔗 Relasi ke installment
    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }

    // 🔗 Relasi ke payer (user)
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }
}

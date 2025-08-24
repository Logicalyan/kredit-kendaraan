<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = ['contract_id', 'installment_no', 'due_date', 'amount_due', 'status'];

    // 🔗 Relasi ke contract
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    // 🔗 Relasi ke payments
    public function payments()
    {
        return $this->hasMany(InstallmentPayment::class, 'installment_id');
    }
}

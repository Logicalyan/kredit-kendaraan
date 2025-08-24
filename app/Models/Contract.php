<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['application_id', 'contract_number', 'start_date', 'end_date', 'status'];

    // 🔗 Relasi ke credit application
    public function application()
    {
        return $this->belongsTo(CreditApplication::class, 'application_id');
    }

    // 🔗 Relasi ke installments
    public function installments()
    {
        return $this->hasMany(Installment::class, 'contract_id');
    }
}


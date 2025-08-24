<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditApplication extends Model
{
    protected $fillable = [
        'customer_id', 'vehicle_id', 'application_date',
        'status', 'dp_amount', 'loan_amount',
        'tenor_months', 'interest_rate', 'monthly_installment',
        'notes'
    ];

    // 🔗 Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // 🔗 Relasi ke vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // 🔗 Relasi ke approvals
    public function approvals()
    {
        return $this->hasMany(Approval::class, 'application_id');
    }

    // 🔗 Relasi ke contract
    public function contract()
    {
        return $this->hasOne(Contract::class, 'application_id');
    }
}

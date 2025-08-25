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

    protected $appends = [
        'dp_amount_formatted',
        'loan_amount_formatted',
        'monthly_installment_formatted',
    ];

    private function formatRupiah($value) {
        return 'Rp ' . number_format($value ?? 0, 0, ',', '.');
    }

    // ✅ Accessors untuk formatting
    public function getDpAmountFormattedAttribute()
    {
        return $this->formatRupiah($this->attributes['dp_amount'] ?? 0);
    }

    public function getLoanAmountFormattedAttribute()
    {
        return $this->formatRupiah($this->attributes['loan_amount'] ?? 0);
    }

    public function getMonthlyInstallmentFormattedAttribute()
    {
        return $this->formatRupiah($this->attributes['monthly_installment'] ?? 0);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $table = 'customer_profiles';

    protected $fillable = [
        'user_id',
        'monthly_income',
        'occupation',
        'ktp_file',
        'kk_file',
        'slip_gaji_file',
        'rekening_tabungan_file',
        'npwp_file',
        'company_name',
        'nik',
        'npwp_number',
        'phone_number',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['monthly_income_formatted'];

    public function getMonthlyIncomeFormattedAttribute()
    {
        return 'Rp' . number_format($this->monthly_income, 0, ',', '.');
    }
}

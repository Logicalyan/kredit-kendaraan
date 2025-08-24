<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    // 🔗 Relasi ke credit applications
    public function creditApplications()
    {
        return $this->hasMany(CreditApplication::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['application_id', 'approver_id', 'decision', 'decided_at', 'notes'];

    // 🔗 Relasi ke credit application
    public function application()
    {
        return $this->belongsTo(CreditApplication::class, 'application_id');
    }

    // 🔗 Relasi ke approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

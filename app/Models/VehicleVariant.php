<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleVariant extends Model
{
    protected $table = 'vehicle_variants';
    protected $fillable = [
        'model_id',
        'name',
        'transmission',
        'engine_capacity',
        'otr_price',
    ];

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'variant_id');
    }
}

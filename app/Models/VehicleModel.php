<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicle_models';
    protected $fillable = [
        'brand_id',
        'type_id',
        'body_type_id',
        'name',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function bodyType()
    {
        return $this->belongsTo(VehicleBodyType::class, 'body_type_id');
    }

    public function variants()
    {
        return $this->hasMany(VehicleVariant::class, 'model_id');
    }
}

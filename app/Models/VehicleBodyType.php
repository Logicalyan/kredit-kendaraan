<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBodyType extends Model
{
    protected $table = 'vehicle_body_types';
    protected $fillable = [
        'name', 'description', 'type_id'
    ];

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'body_type_id');
    }
}

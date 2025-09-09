<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'vehicle_types';
    protected $fillable = ['name'];

    public function bodyTypes()
    {
        return $this->hasMany(VehicleBodyType::class, 'type_id');
    }
}

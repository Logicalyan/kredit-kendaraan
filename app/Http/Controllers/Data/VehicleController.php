<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Traits\ApiResponses;

class VehicleController extends Controller
{
    use ApiResponses;

    public function index() {
        $query = Vehicle::query();
        return $this->success($query->get());
    }
}

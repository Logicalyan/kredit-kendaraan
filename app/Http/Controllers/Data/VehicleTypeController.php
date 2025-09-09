<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    use ApiResponses;

    public function index(Request $request) {
        $query = VehicleType::query();
        return $this->paginated($query, $request);
    }
}

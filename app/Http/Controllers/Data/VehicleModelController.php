<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
    use ApiResponses;
    public function index(Request $request) {
        $query = VehicleModel::query()->with('brand','bodyType');
        return $this->paginated($query, $request);
    }
}

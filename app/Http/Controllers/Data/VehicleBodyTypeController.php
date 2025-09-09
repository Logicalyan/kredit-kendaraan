<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\VehicleBodyType;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class VehicleBodyTypeController extends Controller
{
    use ApiResponses;
    public function index(Request $request) {
        $query = VehicleBodyType::query();
        return $this->paginated($query, $request);
    }
}

<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\VehicleVariant;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class VehicleVariantController extends Controller
{
    use ApiResponses;

    public function index(Request $request) {
        $query = VehicleVariant::query();
        return $this->paginated($query, $request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use ApiResponses;
    public function index(Request $request) {
        $query = Brand::query();
        return $this->paginated($query, $request);
    }
}

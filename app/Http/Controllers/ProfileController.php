<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ApiResponses;

    public function index() {
        $user = Auth::user();
        $profile = User::with('customerProfile')->where('id', $user->id)->first();

        return $this->success($profile, 'Profile', 200);
    }

    public function store (Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'monthly_income' => 'nullable|integer',
            'nik' => 'nullable|string',
            'occupation' => 'nullable|string',
            'company_name' => 'nullable|string',
            "npwp_file" => 'nullable|string',
            'ktp_file' => 'nullable|string',
            'kk_file' => 'nullable|string',
            'slip_gaji_file' => 'nullable|string',
            'rekening_tabungan_file' => 'nullable|string',
            'npwp_number' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $customerProfile = CustomerProfile::create([
            'user_id' => $user->id,
            'monthly_income' => $validated['monthly_income'],
            'nik' => $validated['nik'],
            'occupation' => $validated['occupation'],
            'ktp_file' => $validated['ktp_file'],
            'kk_file' => $validated['kk_file'],
            'slip_gaji_file' => $validated['slip_gaji_file'],
            'rekening_tabungan_file' => $validated['rekening_tabungan_file'],
            'npwp_file' => $validated['npwp_file'],
            'company_name' => $validated['company_name'],
            'npwp_number' => $validated['npwp_number'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        return $this->success($customerProfile, 'Profile', 200);
    }
}

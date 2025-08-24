<?php

namespace App\Http\Controllers\Modul\Installment;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    /**
     * List semua cicilan dari kontrak milik user (customer).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil semua installment dari kontrak yg dimiliki user
        $installments = Installment::whereHas('contract.application', function ($q) use ($user) {
            $q->where('customer_id', $user->id);
        })
            ->with(['contract', 'payments'])
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $installments,
        ]);
    }

    /**
     * Detail 1 cicilan (termasuk riwayat pembayaran).
     */
    public function show($id)
    {
        $user = Auth::user();

        $installment = Installment::where('id', $id)
            ->whereHas('contract.application', function ($q) use ($user) {
                $q->where('customer_id', $user->id);
            })
            ->with(['contract', 'payments'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $installment,
        ]);
    }
}

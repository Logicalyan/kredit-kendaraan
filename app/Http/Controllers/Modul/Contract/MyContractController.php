<?php

namespace App\Http\Controllers\Modul\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;

class MyContractController extends Controller
{
    /**
     * List semua kontrak milik customer yang sedang login
     */
    public function index()
    {
        $userId = Auth::id();

        $contracts = Contract::withCount([
            'installments as total_installments',
            'installments as paid_installments' => function ($q) {
                $q->where('status', 'paid');
            }
        ])
        ->withSum([
            'installments as outstanding_amount' => function ($q) {
                $q->where('status', '!=', 'paid');
            }
        ], 'amount_due')
        ->whereHas('application', function ($q) use ($userId) {
            $q->where('customer_id', $userId);
        })
        ->get();

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Detail kontrak + daftar cicilan
     */
    public function show($id)
    {
        $userId = Auth::id();

        $contract = Contract::with(['installments' => function ($q) {
            $q->orderBy('installment_no');
        }])
        ->where('id', $id)
        ->whereHas('application', function ($q) use ($userId) {
            $q->where('customer_id', $userId);
        })
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $contract
        ]);
    }

}

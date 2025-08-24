<?php

namespace App\Http\Controllers\Modul\Payment;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InstallmentPaymentController extends Controller
{
    public function store(Request $request, $installmentId)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:1000',
            'method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $installmentId) {
            $installment = Installment::findOrFail($installmentId);

            // Prefix berdasarkan method
            $prefixMap = [
                'transfer' => 'TRF',
                'va'       => 'VA',
                'cash'     => 'CSH',
                'ewallet'  => 'EWL',
            ];

            $prefix = $prefixMap[$request->method] ?? 'PAY';

            // Generate nomor referensi unik
            $referenceNumber = $prefix . '-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

            // Simpan pembayaran
            $payment = InstallmentPayment::create([
                'installment_id' => $installment->id,
                'payer_id' => Auth::id(),
                'paid_at' => Carbon::now(),
                'amount_paid' => $request->amount_paid,
                'method' => $request->method,
                'reference_number' => $referenceNumber,
                'notes' => $request->notes,
            ]);

            // Hitung total sudah dibayar
            $totalPaid = $installment->payments()->sum('amount_paid');

            if ($totalPaid >= $installment->amount_due) {
                $installment->status = 'paid';
            } elseif ($totalPaid > 0) {
                $installment->status = 'partial';
            }

            // Overdue check
            if ($installment->status !== 'paid' && now()->gt($installment->due_date)) {
                $installment->status = 'overdue';
            }

            $installment->save();

            return response()->json([
                'message' => 'Payment recorded successfully',
                'data' => $payment
            ]);
        });
    }

    /**
     * History pembayaran untuk 1 cicilan
     */
    public function show($installmentId)
    {
        $userId = Auth::id();

        $installment = Installment::where('id', $installmentId)
            ->whereHas('contract.application', function ($q) use ($userId) {
                $q->where('customer_id', $userId);
            })
            ->firstOrFail();

        $payments = InstallmentPayment::where('installment_id', $installment->id)
            ->orderBy('paid_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
}

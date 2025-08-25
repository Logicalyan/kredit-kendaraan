<?php

namespace App\Http\Controllers\Modul\CreditApplication;

use App\Http\Controllers\Controller;
use App\Models\CreditApplication;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditApplicationController extends Controller
{
    /**
     * Customer membuat pengajuan kredit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'     => 'required|exists:vehicles,id',
            'dp_amount'      => 'required|numeric|min:0',
            'tenor_months'   => 'required|integer|min:1|max:60',
            'notes'          => 'nullable|string',
        ]);

        $user = Auth::user();

        // ambil harga OTR kendaraan
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // hitung pinjaman
        $loanAmount = $vehicle->otr_price - $request->dp_amount;

        //hitung bunga otomatis (0.5% per bulan)
        $interestRate = $request->tenor_months * 0.5;

        // total bunga setahun → loanAmount * (interestRate / 100)
        $interestPerYear = $loanAmount * ($interestRate / 100);
        $totalInterest   = $interestPerYear * ($request->tenor_months / 12);

        // total pembayaran per bulan
        $totalPayment    = $loanAmount + $totalInterest;

        // hitung cicilan
        $monthlyInstallment = round($totalPayment / $request->tenor_months, 0);

            $application = CreditApplication::create([
                'customer_id'       => $user->id,
                'vehicle_id'        => $vehicle->id,
                'application_date'  => now(),
                'status'            => 'submitted',
                'dp_amount'         => $request->dp_amount,
                'loan_amount'       => $loanAmount,
                'tenor_months'      => $request->tenor_months,
                'interest_rate'     => $interestRate,
                'monthly_installment' => $monthlyInstallment,
                'notes'             => $request->notes,
            ]);

            return response()->json([
                'message' => 'Pengajuan kredit berhasil diajukan.',
                'data'    => $application
            ]);
    }

    /**
     * Daftar pengajuan milik customer login.
     */
    public function myApplications()
    {
        $user = Auth::user();

        $applications = CreditApplication::with('vehicle')
            ->where('customer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($applications);
    }
}

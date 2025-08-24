<?php

namespace App\Http\Controllers\Modul\Contract;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\CreditApplication;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractController extends Controller
{
    // Buat kontrak dari aplikasi kredit yg sudah di-approve
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:credit_applications,id',
            'contract_number' => 'required|unique:contracts,contract_number',
            'start_date' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $application = CreditApplication::findOrFail($request->application_id);

            // Hitung tanggal akhir kontrak
            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addMonths($application->tenor_months);

            // Buat kontrak
            $contract = Contract::create([
                'application_id' => $application->id,
                'contract_number' => $request->contract_number,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            // Generate installments
            for ($i = 1; $i <= $application->tenor_months; $i++) {
                Installment::create([
                    'contract_id' => $contract->id,
                    'installment_no' => $i,
                    'due_date' => $startDate->copy()->addMonths($i),
                    'amount_due' => $application->monthly_installment,
                    'status' => 'unpaid',
                ]);
            }

            return response()->json([
                'message' => 'Contract created successfully',
                'data' => $contract->load('installments')
            ]);
        });
    }

    // Lihat detail kontrak beserta cicilannya
    public function show($id)
    {
        $contract = Contract::with('installments.payments')->findOrFail($id);
        return response()->json($contract);
    }
}

<?php

namespace App\Http\Controllers\Modul\CreditApplication;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\CreditApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'notes'    => 'nullable|string'
        ]);

        $application = CreditApplication::findOrFail($applicationId);

        if ($application->status !== 'submitted') {
            return response()->json([
                'message' => 'Pengajuan tidak dapat diproses karena statusnya bukan submitted.'
            ], 400);
        }

        // buat record approval
        $approval = Approval::create([
            'application_id' => $application->id,
            'approver_id'    => Auth::id(),
            'decision'       => $request->decision,
            'decided_at'     => now(),
            'notes'          => $request->notes,
        ]);

        // update status di credit_applications
        $application->update([
            'status' => $request->decision,
        ]);

        return response()->json([
            'message' => "Pengajuan telah {$request->decision}.",
            'data'    => [
                'application' => $application,
                'approval'    => $approval
            ]
        ]);
    }

    public function show(Request $request, $applicationId)
    {
        $application = CreditApplication::findOrFail($applicationId);

        return response()->json([
            'data' => $application
        ]);
    }
}

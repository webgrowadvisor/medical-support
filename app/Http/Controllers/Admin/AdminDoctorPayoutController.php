<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;
use App\Models\DoctorPayout;
use App\Models\Seller;

class AdminDoctorPayoutController extends Controller
{
    
    public function index(Request $request)
    {
        $payouts = DoctorPayout::with('doctor');

        if ($request->filled('search')) {

            $doctor = Seller::where(function ($q) use ($request) {
                    $q->where('mobile', 'like', '%'.$request->search.'%')
                    ->orWhere('name', 'like', '%'.$request->search.'%');
                })->first();

            if ($doctor) {
                $payouts->where('doctor_id', $doctor->id);
            } else {
                // No doctor found → empty result
                $payouts->whereRaw('1=0');
            }
        }

        $payouts = $payouts->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.doctor_payouts.show', compact('payouts'));
    }

    public function approve($id)
    {
        $payout = DoctorPayout::findOrFail($id);
        $wallet = DoctorWallet::where('doctor_id', $payout->doctor_id)->first();

        if ($wallet->balance < $payout->amount) {
            return back()->with('error_msg', 'Wallet balance issue');
        }

        $wallet->decrement('balance', $payout->amount);

        $payout->update([
            'status' => 'approved',
            'transaction_id' => 'TXN'.time()
        ]);

        return back()->with('success_msg', 'Payout approved');
    }

    public function reject(Request $request, $id)
    {
        $payout = DoctorPayout::findOrFail($id);


        $payout->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note ?? 'Admin Rejected'
        ]);

        $doctorwalletid = Seller::findOrFail($payout->doctor_id);

        walletCredit('doctor', $doctorwalletid->wallet->id, $payout->amount, 'Payout Request Rejected');

        return back()->with('success_msg', 'Payout rejected');
    }

    public function payout_history()
    {
        $payouts = DoctorPayout::latest()->paginate(10);

        return view('admin.doctor_payouts.history', compact('payouts'));
    }

}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;
use App\Models\DoctorPayout;
use App\Models\Seller;

class DoctorPayoutController extends Controller
{
    
    public function index()
    {
        $doctorId = auth('seller')->id();

        $wallet = DoctorWallet::where('doctor_id', $doctorId)->first();
        $payouts = DoctorPayout::where('doctor_id', $doctorId)->latest()->paginate(10);

        return view('seller.payouts.add', compact('wallet', 'payouts'));
    }

    public function payout_history()
    {
        $doctorId = auth('seller')->id();

        $wallet = DoctorWallet::where('doctor_id', $doctorId)->first();
        $payouts = DoctorPayout::where('doctor_id', $doctorId)->latest()->paginate(10);

        return view('seller.payouts.show', compact('wallet', 'payouts'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $doctorId = auth('seller')->id();
        $wallet = DoctorWallet::where('doctor_id', $doctorId)->first();

        if ($wallet->balance < $request->amount) {
            return back()->with('error_msg', 'Insufficient balance');
        }

        DoctorPayout::create([
            'doctor_id' => $doctorId,
            'amount' => $request->amount,
        ]);

        $doctorwalletid = Seller::findOrFail($doctorId);

        walletDebit('doctor', $doctorwalletid->wallet->id, $request->amount, 'Payout Request');

        notifyAdmin(
            'Payout',
            'Payout request',
            'Payout request sent '.auth('seller')->name.'.'
        );

        return back()->with('success_msg', 'Payout request sent to admin');
    }

}

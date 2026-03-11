<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\DoctorWallet;
use App\Models\WalletTransaction;

class SellerWalletController extends Controller
{
    
    public function index()
    {
        $doctorId = Auth::guard('seller')->id();

        $wallet = DoctorWallet::firstOrCreate(
            ['doctor_id' => $doctorId],
            ['balance' => 0]
        );

        return view('seller.wallet.add', compact('wallet'));
    }

    public function transactions()
    {
        $doctorId = Auth::guard('seller')->id();

        $wallet = DoctorWallet::where('doctor_id', $doctorId)->firstOrFail();

        $transactions = WalletTransaction::where('wallet_type', 'doctor')
            ->where('wallet_id', $wallet->id)
            ->latest()
            ->paginate(15);

        return view('seller.wallet.show', compact('wallet', 'transactions'));
    }
}

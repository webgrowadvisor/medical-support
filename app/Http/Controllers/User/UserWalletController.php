<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\UserWallet;
use App\Models\WalletTransaction;

class UserWalletController extends Controller
{
    

    public function index()
    {
        $wallet = UserWallet::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );

        return view('front.wallet.add', compact('wallet'));
    }

    public function transactions()
    {
        $wallet = auth()->user()->wallet;

        $transactions = WalletTransaction::where('wallet_type', 'user')
            ->where('wallet_id', $wallet->id)
            ->latest()
            ->paginate(15);

        return view('front.wallet.show', compact('wallet', 'transactions'));
    }

    // Dummy add money (gateway later)
    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        walletCredit(
            'user',
            auth()->user()->wallet->id,
            $request->amount,
            'Wallet Top-up'
        );

        return back()->with('success_msg', 'Money added to wallet');
    }

}

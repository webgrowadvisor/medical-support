<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use App\Models\CommistionPlan;


class CommisionController extends Controller
{
    
    public function index()
    {
        $plans = CommistionPlan::latest()->get();
        return view('admin.commision.index', compact('plans'));
    }

    public function create()
    {
        $plans = Seller::latest()->get();
        return view('admin.commision.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'specialization' => 'required',
            'type' => 'required',
            'commission_value' => 'required|integer',
            'status' => 'required',
        ]);

        CommistionPlan::create($request->all());

        return redirect()->route('admin.commission.plans')
            ->with('success_msg', 'Plan Created Successfully');
    }

    public function edit($id)
    {
        $plan = CommistionPlan::findOrFail($id);
        return view('admin.commision.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = CommistionPlan::findOrFail($id);

        $plan->update($request->all());

        return redirect()->route('admin.commission.plans')
            ->with('success_msg', 'Plan Updated Successfully');
    }


    // $plan = CommissionPlan::where('doctor_id', $doctorId)
    // ->where('status', 1)
    // ->first();

    // if (!$plan) {
    //     $plan = CommissionPlan::where('specialization', $doctor->specialization)
    //         ->where('status', 1)
    //         ->first();
    // }

    // $commission = 0;

    // if ($plan) {
    //     if ($plan->type == 'percentage') {
    //         $commission = ($amount * $plan->commission_value) / 100;
    //     } else {
    //         $commission = $plan->commission_value;
    //     }
    // }

    // $doctorAmount = $amount - $commission;

    // Admin wallet
    // walletCredit('admin', 1, $commission, 'Commission from appointment');

    // Doctor wallet
    // walletCredit('doctor', $doctorWalletId, $doctorAmount, 'Appointment earning');

}

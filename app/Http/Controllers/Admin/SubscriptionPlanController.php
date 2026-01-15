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

class SubscriptionPlanController extends Controller
{
    
    public function index()
    {
        $plans = SubscriptionPlan::latest()->get();
        return view('admin.subscription.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'status' => 'required',
            'features' => 'required',
        ]);

        SubscriptionPlan::create($request->all());

        return redirect()->route('admin.subscription.plans')
            ->with('success_msg', 'Plan Created Successfully');
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $plan->update($request->all());

        return redirect()->route('admin.subscription.plans')
            ->with('success_msg', 'Plan Updated Successfully');
    }

}

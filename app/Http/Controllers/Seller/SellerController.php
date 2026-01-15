<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use App\Models\Category;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\DoctorPayout;
use Carbon\Carbon;

class SellerController extends Controller
{

    public function index (){

        $data = [
            // 'total_users'          => User::count(),
            // 'total_sellers'        => Seller::count(),
            'total_payout'        => DoctorPayout::where('doctor_id', auth('seller')->id())->count(),
            'cancel_payout'        => DoctorPayout::where('doctor_id', auth('seller')->id())->where('status', '!=', 'rejected')->count(),
            'total_appoiniment'        => Appointment::where('doctor_id', auth('seller')->id())->count(),
            'cancel_appoiniment'        => Appointment::where('doctor_id', auth('seller')->id())->where('status', '!=', 'cancelled')->count(),
            'total_prescription'        => Prescription::where('doctor_id', auth('seller')->id())->count(),
            'cancel_prescription'        => Prescription::where('doctor_id', auth('seller')->id())->where('status', '!=', 'inactive')->count(),
            'wek_appoiniment'        => Appointment::where('doctor_id', auth('seller')->id())
                                        ->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'week_appoiniment'        => Appointment::where('doctor_id', auth('seller')->id())->where('status', 'cancelled')
                                        ->where('created_at', '>=', Carbon::now()->subDays(7))->count(),

            'wek_payout'        => DoctorPayout::where('doctor_id', auth('seller')->id())
                                        ->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'week_payout'        => DoctorPayout::where('doctor_id', auth('seller')->id())->where('status', 'rejected')
                                        ->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            ];

        // dd(Auth::guard('seller')->user());
        return view('seller.dashboard', compact('data'));
    }

    public function accountSettings()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.users-edit', compact('seller'));
    }

    public function updateAccountSettings(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:sellers,email,' . $seller->id,
            'mobile' => 'required|string|unique:sellers,mobile,' . $seller->id,
            'password' => 'nullable|min:6', // only update if entered
            'status' => 'nullable',
            'slug' => 'required',
            'specialization' => 'required',
            'amount' => 'required',
            'gst' => 'required',
            'logo'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gst_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address'   => 'nullable',
        ]);

        if ($request->hasFile('logo')) {
            $paths = uploadWebp($request->file('logo'), 'doctor_logo');
            $seller->logo = $paths['webp'] ?? $paths['image'];
        }
        if ($request->hasFile('gst_image')) {
            $paths = uploadWebp($request->file('gst_image'), 'doctor_cart');
            $seller->gst_image = $paths['webp'] ?? $paths['image'];
        }

        $seller->status = $request->status;
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->mobile = $request->mobile;
        $seller->gst = $request->gst;
        $seller->slug = $request->slug;
        $seller->specialization = $request->specialization;
        $seller->amount = $request->amount;
        $seller->address = $request->address;

        if ($request->password) {
            $seller->password = Hash::make($request->password);
        }

        $seller->save();

        return redirect()->back()->with('success_msg', 'Account settings updated successfully.');
    }

}

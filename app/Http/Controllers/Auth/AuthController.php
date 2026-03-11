<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\SellerOtp;
use App\Models\Dropservice;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    
    public function user_check(Request $request)
    {
        // Validation
        $request->validate([
            'mobile' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('web')->attempt(['mobile' => $request->mobile, 'password' => $request->password, 'status' => 1])) {
            auditLog('user', Auth::guard('web')->user()->id, 'login', [
                'email' => Auth::guard('web')->user()->email,
                'mobile' => Auth::guard('web')->user()->mobile
            ]);
            // Login successful
            return redirect()->route('user.desh')->with('success_msg', value: 'Welcome '. Auth::guard('web')->user()->name);
        }

        // Login failed
        return back()->withErrors([
            'password' => 'Invalid credentials.',
        ])->withInput();
    }

    public function admin_check(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('admin')->attempt($credentials)) {
            // Login successful
            return redirect()->route('admin.dashboard')->with('success_msg', value: 'Welcome '.Auth::guard('admin')->user()->name); 
        }
        // Login failed
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function admin_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'required|digits:10|unique:admins,mobile',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }


    public function seller_check(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // $credentials = $request->only('email', 'password');
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1
        ];

        if (Auth::guard('seller')->attempt($credentials)) {

            auditLog('doctor', Auth::guard('seller')->user()->id, 'login', [
                'email' => Auth::guard('seller')->user()->email,
                'mobile' => Auth::guard('seller')->user()->mobile
            ]);
            // Login successful
            return redirect()->route('seller.dashboard')->with('success_msg', 'Welcome '.Auth::guard('seller')->user()->name); 
        }
        // Login failed
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function seller_register(Request $request, $level = '1')
    {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:admins,email',
                'mobile' => 'required|digits:10|unique:admins,mobile',
                'password' => 'required|min:6|confirmed',
            ]);

            $admin = Seller::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'api_key' => Str::random(60),
                // 'gst' => $level == '2' ? $request->gst : null,
                'gst' => null,
                'status' => 0,
            ]); 

            auditLog('doctor', $admin->id, 'signup', [
                'email' => $admin->email,
                'mobile' => $admin->mobile
            ]);

            // $otp = rand(100000, 999999);
            $otp = 123456;

            SellerOtp::create([
                'seller_id'    => $admin->id,
                'otp'        => $otp,
                'expires_at' => now()->addMinutes(5),
            ]);

            session(['otp_seller_id' => $admin->id]);

            // Auth::guard('seller')->login($admin);

            notifyAdmin(
                'registered',
                'Doctor registered',
                'A new doctor registered on portel.'
            );

        return redirect()->route('seller.otp.verify.form')
        ->with('success_msg', 'OTP sent to your registered email/mobile');

        // $seller = Auth::guard('seller')->user();
        // $seller->gst = $request->gst ?? null;
        // $seller->save();
        // Auth::guard('seller')->login($admin);
        // return redirect()->route('seller.dashboard')->with('success_msg', 'Welcome '.Auth::guard('seller')->user()->name);
    }

    public function otpForm()
    {
        return view('seller.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('otp_seller_id');

        $otpRow = SellerOtp::where('seller_id', $userId)
            ->where('otp', $request->otp)
            ->where('is_used', 0)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRow) {
            return back()->with('error_msg', 'Invalid or expired OTP');
        }

        // ✅ Activate user
        $seller = Seller::find($userId);
        $seller->status = 1;
        $seller->save();

        // ✅ Mark OTP used
        $otpRow->update(['is_used' => 1]);

        auditLog('Doctor', $seller->id, 'otp_verified', [
            'email' => $seller->email,
            'mobile' => $seller->mobile
        ]);

        Auth::guard('seller')->login($seller);

        return redirect()->route('seller.dashboard')->with('success_msg', 'Welcome '.Auth::guard('seller')->user()->name);
    }
    

    public function user_logout(){

        auditLog('user', Auth::guard('web')->user()->id, 'logout', [
                'email' => Auth::guard('web')->user()->email,
                'mobile' => Auth::guard('web')->user()->mobile
            ]);

        Auth::guard('web')->logout();
        return redirect()->route('home');
    }

    public function admin_logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('home');
    }

    public function seller_logout(){

        auditLog('doctor', Auth::guard('seller')->user()->id, 'logout', [
                'email' => Auth::guard('seller')->user()->email,
                'mobile' => Auth::guard('seller')->user()->mobile
            ]);

        Auth::guard('seller')->logout();
        return redirect()->route('home');
    }



}

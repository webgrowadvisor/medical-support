<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Models\Review;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\DoctorPayout;
use App\Models\UserFile;
use Carbon\Carbon;
use App\Models\Protocl;

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
        ], [
            'amount.required' => 'The service charge. field is required.',
            'gst.required' => 'The certificate no. field is required.',
            'logo.mimes' => 'The Id image must be a file of type jpeg,png,jpg.',
            'gst_image.mimes' => 'The certificate image must be a file of type jpeg,png,jpg.',
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


    public function doctorReviews()
    {
        $reviews = Review::with(['user', 'appointment'])
            ->where('doctor_id', auth('seller')->id())
            ->latest()
            ->paginate(10);

        return view('seller.review.index', compact('reviews'));
    }

    public function review_load($id)
    {
        return view('seller.box.review', compact('id'));
    }

    public function review_store(Request $request)
    {
        $request->validate([
            'reviewid' => 'required',
            'review_rection' => 'nullable|string'
        ]);

        $review = Review::where('id', $request->reviewid)->firstOrFail();
        $review->review_rection = $request->review_rection;
        $review->save();

        notifyUser(
            $review->user_id,
            'reaction',
            'Review',
            'Doctor reaction on your review'
        );

        return back()->with('success_msg', 'Thank you for your review!');
    }


    public function dashboard_load($id)
    {
        $user = User::findOrFail($id);
        $wallet = UserWallet::where('user_id',$id)->first();

        $appointments = Appointment::where('user_id', $id);
        $prescriptions = Prescription::where('user_id', $id);

        $data = [
            'total_appoiniment'   => $appointments->count(),
            'cancel_appoiniment'  => $appointments->where('status','cancelled')->count(),
            'total_prescription'  => $prescriptions->count(),
            'cancel_prescription' => $prescriptions->where('status','active')->count()
        ];

        return view('seller.box.profile', compact('id','data','user'));
    }

    public function medications_load($id)
    {
        $prescriptions = Prescription::where('user_id', $id)
            ->where('status', 'active')
            ->latest()->paginate(20);
        return view('seller.box.medicine', compact('id', 'prescriptions'));
    }
    public function passed_load($id)
    {
        $appointments = Appointment::where('user_id', $id)->where('doctor_id', auth('seller')->id())->latest()->paginate(20);
        return view('seller.box.appo', compact('appointments'));
    }
    public function patientfiles_load($id)
    {
        $files = UserFile::where('user_id', $id)->latest()->paginate(20);
        return view('seller.box.files', compact('files'));
    }
    public function profile_load($id)
    {   
        $user = User::findOrFail($id);
        return view('seller.box.bio', compact('user'));
    }

    public function announcement()
    {
        $announcements = Protocl::latest()->paginate(10);
        return view('seller.protocal.index', compact('announcements'));
    }

    public function announcement_load($id)
    {
        $announcement = Protocl::find($id);
        return view('seller.box.protocal', compact('announcement'));
    }

}

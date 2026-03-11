<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use session;
use Str;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use App\Models\UserWallet;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\WalletTransaction;
use App\Models\Review;
use App\Models\Announcement;
use App\Models\Service;
use App\Models\ServiceCategory;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Library;

class UserController extends Controller
{
    public function generateToken(Request $request)
    {
        $identity = $request->identity ?? 'user_' . rand(100,999);

        $accountSid = env('TWILIO_ACCOUNT_SID');
        $apiKey = env('TWILIO_API_KEY');
        $apiSecret = env('TWILIO_API_SECRET');

        

        $token = new AccessToken(
            $accountSid,
            $apiKey,
            $apiSecret,
            3600,
            $identity
        );

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($request->room ?? 'test-room');

        $token->addGrant($videoGrant);

        return response()->json([
            'token' => $token->toJWT(),
        ]);
    }

    public function getvideo($uuid){
        $roomid = 'myMeeting';
        $appointment = Appointment::where('meeting_code', $uuid)->firstOrFail();
        return view('video.video', compact('roomid', 'appointment'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'mobile'   => 'required|string|max:15|unique:users,mobile',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'password' => Hash::make($request->password),
            'status'   => 0,
        ]);

        // $otp = rand(100000, 999999);
        $otp = 123456;

        UserOtp::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new WelcomeEmail($otp));

        auditLog('user', $user->id, 'signup', [
                'email' => $user->email,
                'mobile' => $user->mobile
        ]);

        session(['otp_user_id' => $user->id]);

        notifyAdmin(
            'registered',
            'User registered',
            'A new user registered on portel.'
        );

        return redirect()->route('otp.verify.form')
        ->with('success_msg', 'OTP sent to your registered email/mobile');

        // auth()->login($user);
        // return redirect('/user/dashboard-overview')->with('success_msg', 'Registration successful! Welcome to Telemedicine .');
    }

    public function otpForm()
    {
        return view('front.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('otp_user_id');

        $otpRow = UserOtp::where('user_id', $userId)
            ->where('otp', $request->otp)
            ->where('is_used', 0)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRow) {
            return back()->with('error_msg', 'Invalid or expired OTP');
        }

        // ✅ Activate user
        $user = User::find($userId);
        $user->status = 1;
        $user->save();

        // ✅ Mark OTP used
        $otpRow->update(['is_used' => 1]);

        auditLog('user', $user->id, 'otp_verified', [
            'email' => $user->email,
            'mobile' => $user->mobile
        ]);

        auth()->login($user);

        return redirect('/user/dashboard-overview')->with('success_msg', 'Account verified successfully');
    }

    public function index()
    {
        // dd(Auth::guard('web')->user());
        return view('front.index');
    }

    public function dashboard_overview()
    {
        // dd(auth()->user()->id);
        $wallet = UserWallet::where('user_id',auth()->user()->id)->first();
        
        $data = [
            'total_appoiniment'        => Appointment::where('user_id', auth()->id())->count(),
            'cancel_appoiniment'        => Appointment::where('user_id', auth()->id())->where('status', 'cancelled')->count(),
            'total_prescription'        => Prescription::where('user_id', auth()->id())->count(),
            'cancel_prescription'        => Prescription::where('user_id', auth()->id())->where('status', 'active')->count(),
            'totaluser_trans'        => WalletTransaction::where('wallet_type', 'user')->count(),
        ];
        if($wallet){
         $data['walletTrans'] = WalletTransaction::where('wallet_id', $wallet->id)->where('wallet_type', 'user')->count();
         }else{
            $data['walletTrans'] = 0;
         }
         

        return view('front.dashboard', compact('data'));
    }

    public function accountSettings()
    {
        $user = Auth::user(); // logged-in patient
        return view('front.users-edit', compact('user'));
    }

    public function updateAccountSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:15|unique:users,mobile,' . $user->id,
            'address'=> 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dob'   => 'required|date',
            'other' => 'nullable',
        ], [
            'image.mimes' => 'The image must be a file of type jpeg,png,jpg.',
        ]);

        $data = [
            'name'    => $request->name,
            'mobile'  => $request->mobile,
            'address' => $request->address,
            'dob' => $request->dob,
            'other' => $request->other,
        ];

        if ($request->hasFile('image')) {
            $paths = uploadWebp($request->file('image'), 'user_image');
            $data['image'] = $paths['webp'] ?? $paths['original'];
        }

        // Update password only if filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.account.settings')->with('success_msg', 'Profile updated successfully');
    }

    public function review_load($id)
    {
        return view('front.box.review', compact('id'));
    }

    public function review_store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string'
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Prevent duplicate review
        if (Review::where('appointment_id', $appointment->id)->exists()) {
            return back()->with('error_msg', 'Review already submitted');
        }

        Review::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        notifyDoctor(
            $appointment->doctor_id,
            'review',
            'New Review',
            'You have received a new review.'
        );

        return back()->with('success_msg', 'Thank you for your review!');
    }


    public function announcement()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('front.announcements.index', compact('announcements'));
    }

    public function announcement_load($id)
    {
        $announcement = Announcement::find($id);
        return view('front.box.announcement', compact('announcement'));
    }

    public function service_catalog(Request $request)
    {   
        $search = $request->search;
        $services = Service::with('category')
        ->whereHas('category', function ($query) use ($search){
            $query->where('status', 1);
            if (!empty($search)) {
                $query->where('name', 'LIKE', "%{$search}%");
            }
        })
        ->where('status', 1)->get();

        return view('front.services.index', compact('services'));
    }

    public function medicine_list()
    {
        $user = Auth::user();
        $medicines = Medicine::where('status',1)->where('stock','>',0)->get();
        return view('front.medicines.create', compact('medicines', 'user'));
    }


    public function orderList(Request $request)
    {   
        $search = $request->search;

        $orders = Order::with('items.medicine');
        if (!empty($search)) {
          $orders = $orders->where('order_number', 'LIKE', "%{$search}%");
        }

        $orders = $orders->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('front.medicines.index', compact('orders'));
    }

    public function orderView($order)
    {
        $orders = Order::with('items.medicine')
            ->where('user_id', auth()->id())
            ->where('order_number', $order)
            ->get();

        return view('front.medicines.show', compact('orders'));
    }

    public function buy_medicine(Request $request)
    {
        $request->validate([
            'medicines' => 'required|array',
            'shipping_address' => 'required',
            'mobile' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $total = 0;

            // Create Order First
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'shipping_address' => $request->shipping_address,
                'phone' => $request->mobile,
                'status' => 'pending',
                'total_amount' => 0
            ]);

            foreach ($request->medicines as $medicineId) {

                $medicine = Medicine::findOrFail($medicineId);

                // ✅ Stock Check inside loop
                if ($medicine->stock < 1) {
                    DB::rollBack();
                    return back()->with('error_msg', $medicine->name . ' is Out of Stock');
                }

                $quantity = 1;
                $price = $medicine->price;
                $subtotal = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $medicine->decrement('stock', $quantity);

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total
            ]);

            DB::commit();

            notifyAdmin(
                'Buy Product',
                'Order Placed',
                'You have received a new product order.'
            );

            return back()->with('success_msg', 'Order Placed Successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error_msg', 'Something went wrong');
        }
    }


    public function libary(Request $request)
    {
        $medicines = Library::where('status', true)
                ->when($request->type, function ($q) use ($request) {
                    $q->where('type', $request->type);
                })
                ->latest()
                ->get();
        return view('front.libary.index', compact('medicines'));
    }

    public function library_load($id)
    {
        $libary = Library::find($id);
        return view('front.box.libary', compact('libary'));
    }
    

}

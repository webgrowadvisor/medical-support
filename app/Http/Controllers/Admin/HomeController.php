<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin; 
use App\Models\Seller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Prescription;
use App\Models\DoctorPayout;
use App\Models\DoctorAvailability;
use App\Models\WalletTransaction;
use App\Models\Payment;
use App\Models\Announcement;
use App\Models\Protocl;

class HomeController extends Controller
{
    public function index (){

        // dd(vars: admininfo()->name);
        $data = [
            'total_users'          => User::count(),
            'active_users'          => User::where('status', '1')->count(),
            'total_sellers'        => Seller::count(),
            'active_sellers'          => Seller::where('status', '1')->count(),
            'total_payout'        => DoctorPayout::count(),
            'cancel_payout'        => DoctorPayout::where('status', 'rejected')->count(),
            'total_appoiniment'        => Appointment::count(),
            'cancel_appoiniment'        => Appointment::where('status', 'cancelled')->count(),
            'total_prescription'        => Prescription::count(),
            'cancel_prescription'        => Prescription::where('status', 'active')->count(),
            'appointments'        => Appointment::with(['user', 'doctor'])->latest()->paginate(10),
        ];

        return view('admin.dashboard', compact('data'));
    }

    public function userlist(Request $request)
    {
        $users = User::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $users->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $users = $users
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // $users = User::paginate(10);
        return view('admin.user', compact('users'));
    }

    public function sellerlist(Request $request)
    {
        $sellers = Seller::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $sellers->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $sellers = $sellers
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // $sellers = Seller::paginate(10);
        return view('admin.seller', compact('sellers'));
    }

    public function drop_update(Request $request, $id)
    {
        $drop = Dropservice::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:dropservices,email,' . $drop->id,
            'mobile' => 'required|string|max:15|unique:dropservices,mobile,' . $drop->id,
            'status' => 'required|in:0,1',
            'password' => 'nullable|min:6',
            'gst' => 'nullable',
            'address' => 'nullable',
        ]);

        $drop->name   = $validated['name'];
        $drop->email  = $validated['email'];
        $drop->mobile = $validated['mobile'];
        $drop->status = $validated['status'];
        $drop->status = $validated['gst'];
        $drop->address = $validated['address'];

        if (!empty($validated['password'])) {
            $drop->password = Hash::make($validated['password']);
        }

        $drop->save();

        return redirect()->route('drop.list')->with('success_msg', 'Dropservice user updated successfully.');
    }

    public function seller_edit($id)
    {
        $user = Seller::findOrFail($id);
        return view('admin.seller_edit', compact('user'));
    }

    public function seller_update(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:sellers,email,' . $seller->id,
            'mobile' => 'required|string|max:15|unique:sellers,mobile,' . $seller->id,
            'status' => 'required|in:0,1',
            'password' => 'nullable|min:6',
            'specialization' => 'required',
            'amount' => 'required',
            'gst' => 'required',
            'address' => 'required',
        ], [
            'amount.required' => 'The service charge. field is required.',
            'gst.required' => 'The certificate no. field is required.',
        ]);

        $seller->name = $validated['name'];
        $seller->email = $validated['email'];
        $seller->mobile = $validated['mobile'];
        $seller->status = $validated['status'];
        $seller->gst = $validated['gst'] ?? '';
        $seller->specialization = $validated['specialization'] ?? '';
        $seller->amount = $validated['amount'] ?? '';
        $seller->address = $validated['address'] ?? '';

        if (!empty($validated['password'])) {
            $seller->password = Hash::make($validated['password']);
        }

        $seller->save();

        return redirect()->route('seller.list')->with('success_msg', 'Doctor profile updated successfully.');
    }

    public function user_edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.normal-user-edit', compact('user'));
    }

    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:15|unique:users,mobile,' . $user->id,
            'status' => 'required|in:0,1',
            'password' => 'nullable|min:6',
            'address' => 'nullable',
        ]); 

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->mobile = $validated['mobile'];
        $user->status = $validated['status'];
        $user->address = $validated['address'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('user.list')->with('success_msg', 'User updated successfully.');
    }

    public function userupdateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success_msg' => 'Status updated successfully.']);
    }

    public function sellerupdateStatus(Request $request, $id)
    {
        $user = Seller::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success_msg' => 'Status updated successfully.']);
    }

    public function dropupdateStatus(Request $request, $id)
    {
        $user = Dropservice::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success_msg' => 'Status updated successfully.']);
    }

    public function accountSettings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.users-edit', compact('admin'));
    }

    public function updateAccountSettings(Request $request)
    {
        $seller = Auth::guard('admin')->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:sellers,email,' . $seller->id,
            'mobile' => 'required|string|unique:sellers,mobile,' . $seller->id,
            'password' => 'nullable|min:6', // only update if entered
            'status' => 'nullable',
        ]);

        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->mobile = $request->mobile;

        if ($request->password) {
            $seller->password = Hash::make($request->password);
        }

        $seller->save();

        return redirect()->back()->with('success_msg', 'Account settings updated successfully.');
    }


    public function appointments(Request $request)
    {
         $appointments = Appointment::with(['user', 'doctor']);

        // 🔍 Search (User / Doctor / Status)
        if ($request->filled('search')) {

            $search = $request->search;

            $appointments->where(function ($q) use ($search) {

                // User search
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
                })

                // OR Doctor search
                ->orWhereHas('doctor', function ($dq) use ($search) {
                    $dq->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
                })

                // OR Status
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // 📅 Filter by date
        if ($request->filled('date')) {
            $appointments->whereDate('appointment_date', $request->date);
        }

        $appointments = $appointments
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // $appointments = Appointment::with(['user', 'doctor'])->latest()->paginate(10);
        
        return view('admin.appointments.show', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);

        return back()->with('success_msg', 'Status updated');
    }

    public function AuditLogController()
    {
        $logs = AuditLog::latest()->paginate(20);
        return view('admin.audit.show', compact('logs'));
    }

    public function prescription()
    {
        $prescriptions = Prescription::with(['user', 'doctor'])->latest()->paginate(10);
            // where('user_id', auth()->id())
            // ->where('status', 'active')
            // ->latest()->paginate(10);

        return view('admin.prescription.show', compact('prescriptions'));
    }

    public function prescription_pdf($id)
    {
        $prescription = Prescription::where('id', $id)
            // ->where('user_id', auth()->id())
            ->firstOrFail();

        $pdf = PDF::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download('prescription-'.$id.'.pdf');
    }

    public function announcement_load($id)
    {
        $announcement = Announcement::find($id);
        return view('front.box.announcement', compact('announcement'));
    }

    public function protocol_load($id)
    {
        $announcement = Protocl::find($id);
        return view('seller.box.protocal', compact('announcement'));
    }

    public function wallet_transtion()
    {
        $payments = WalletTransaction::with(['user', 'doctor'])->orderBy('id', 'desc')->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function payment_add()
    {
        $payments = Payment::with(['user', 'doctor'])->orderBy('id', 'desc')->paginate(20);
        return view('admin.payments.payment', compact('payments'));
    }
    
}

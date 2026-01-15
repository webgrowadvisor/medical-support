<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use session;
use Carbon\Carbon;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Appointment;
use App\Models\DoctorAvailability;

class BookingController extends Controller
{
    

    public function doctorList()
    {
        $doctors = Seller::where('status', 1)->paginate(10);
        return view('front.doctors.show', compact('doctors'));
    }

    public function bookForm($id)
    {
        $doctor = Seller::findOrFail($id);
        $slots = DoctorAvailability::where('doctor_id', $id)
                ->where('status', 'active')
                ->orderBy('available_date')
                ->get();

        return view('front.doctors.add', compact('doctor', 'slots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:sellers,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'required',
        ]);

        $doctorId = $request->doctor_id;
        $date = $request->appointment_date;
        $time = $request->appointment_time;
        $notes = $request->notes;

        $appointmentDateTime = Carbon::parse($date . ' ' . $time);

        if ($appointmentDateTime->isPast()) {
            return back()->with([
                'error_msg' => 'Past date or time is not allowed.'
            ]);
        }

        // 🔹 Get DAY from selected date (Monday, Tuesday...)
        $day = Carbon::parse($date)->format('l');

        // dd($day);
        $shadule_created = DoctorAvailability::where('doctor_id', $doctorId)->first();
        if (!$shadule_created) {
            return back()->with('error_msg', 'Doctor schedule is not available at this time');
        }

        // ✅ 1️⃣ Check Doctor Availability
        $availability = DoctorAvailability::where('doctor_id', $doctorId)
            ->where('available_date', $day)  // weekly availability
            ->where('status', 'active')
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->first();

        if (!$availability) {
            return back()->with('error_msg', 'Doctor is not available at this time');
        }

        // ✅ 2️⃣ Check Slot Already Booked
        $alreadyBooked = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error_msg', 'This slot is already booked');
        }

        $doctor = Seller::findOrFail($doctorId);
        $wallet = UserWallet::where('user_id', auth()->id())->first();
        if ($wallet->balance < $doctor->amount) {
            return back()->with('error_msg', 'Insufficient balance');
        }

        // ✅ 3️⃣ Create Appointment
        Appointment::create([
            'user_id' => auth()->id(),
            'doctor_id' => $doctorId,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'notes' => $notes,
            'status' => 'pending',
        ]);

        // Appointment Booking
        $amount = $doctor->amount ?? '0';
        $doctorwalletid = Seller::findOrFail($doctorId);

        walletDebit('user', auth()->user()->wallet->id, $amount, 'Doctor Appointment');

        walletCredit('doctor', $doctorwalletid->wallet->id, $amount, 'Consultation Fee');

        $comm = $amount / 10;
        walletDebit('doctor', $doctorwalletid->wallet->id, $comm, 'Portel Commission');

        notifyDoctor(
            $doctor->id,
            'appointment',
            'New Appointment',
            'You have received a new appointment.'
        );

        notifyAdmin(
            'appointment',
            'New Appointment',
            'You have received a new appointment.'
        );

        return redirect()->route('user.appointments')
            ->with('success_msg', 'Appointment booked successfully');
    }

    public function myAppointments()
    {
        $appointments = Appointment::where('user_id', auth()->id())->latest()->paginate(10);
        return view('front.doctors.appointments', compact('appointments'));
    }

    // Refund
    // walletCredit('user', $user->wallet->id, 500, 'Appointment Refund');
    // walletDebit('doctor', $doctor->wallet->id, 400, 'Appointment Cancelled');

    // ADMIN VIEW (Wallet History)
    // WalletTransaction::latest()->paginate(20);

}

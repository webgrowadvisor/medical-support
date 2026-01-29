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
    

    public function doctorList(Request $request)
    {
        // $doctors = Seller::where('status', 1)->paginate(10);
        $doctors = Seller::where('status', 1);

        if ($request->filled('search')) {
            $doctors->where('specialization', 'like', '%' . $request->search . '%');
        }

        $doctors = $doctors->paginate(10)->withQueryString();
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

        $endTime = Carbon::parse($time)->addMinutes($availability->interval)->format('H:i');

        // ✅ 2️⃣ Check Slot Already Booked
        $alreadyBooked = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            // ->where('appointment_time', $time)
            // ->where('appointment_end', '!>', $endTime)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($time, $endTime) {
                $q->where('appointment_time', '<', $endTime)
                ->where('appointment_end', '>', $time);
            })
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
            'appointment_end' => $endTime,
            'notes' => $notes,
            'status' => 'pending',
        ]);

        // Appointment Booking
        $amount = $doctor->amount ?? '0';
        $doctorwalletid = Seller::findOrFail($doctorId);

        walletDebit('user', auth()->user()->wallet->id, $amount, 'Doctor Appointment');

        walletCredit('doctor', $doctorwalletid->wallet->id, $amount, 'Consultation Fee');

        $comm = $amount / commistion_charge();
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

    public function myAppointments(Request $request)
    {
        // $appointments = Appointment::where('user_id', auth()->id())->latest()->paginate(10);

        $appointments = Appointment::with(['user', 'doctor'])
        ->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;

            $appointments->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%")
                    ->orWhere('mobile', 'like', "%$search%");
                })
                ->orWhereHas('doctor', function($d) use ($search) {
                    $d->where('name', 'like', "%$search%");
                })
                ->orWhere('notes', 'like', "%$search%");
            });
        }

        $appointments = $appointments->latest()->paginate(10);

        return view('front.doctors.appointments', compact('appointments'));
    }

}

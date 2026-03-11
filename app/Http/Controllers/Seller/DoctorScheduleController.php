<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use session;
use App\Models\Seller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\DoctorAvailability;
use App\Models\Prescription;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingMail;

class DoctorScheduleController extends Controller
{
    
    public function index()
    {
        $availabilities = DoctorAvailability::where('doctor_id', auth('seller')->id())
        ->orderBy('available_date', 'asc')->paginate(10);
        return view('seller.availabilities.show', compact('availabilities'));
    }

    public function add_availability()
    {
        return view('seller.availabilities.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'available_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'interval' => 'required',
        ]);

        $doctorId = auth('seller')->id();

        // 🔴 Duplicate Slot Check
        $exists = DoctorAvailability::where('doctor_id', $doctorId)
            ->where('available_date', $request->available_date)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                ->orWhere(function ($q2) use ($request) {
                    $q2->where('start_time', '<=', $request->start_time)
                        ->where('end_time', '>=', $request->end_time);
                });
            })
            ->exists();

        if ($exists) {
            return back()->with('error_msg', 'This time slot already exists');
        }

        DoctorAvailability::create([
            'doctor_id' => $doctorId,
            'available_date' => $request->available_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'interval' => $request->interval,
            'status' => $request->status ?? 'active',
        ]);

        notifyAdmin(
            'Availability',
            'Availability Added',
            'Availability added by doctor '.auth('seller')->name.'.'
        );

        return back()->with('success_msg', 'Availability Added Successfully');
    }

    public function updateAvailabilityStatus(Request $request, $id)
    {
        $availability = DoctorAvailability::findOrFail($id);

        $availability->status = $availability->status === 'active'
            ? 'inactive'
            : 'active';

        $availability->save();

        return response()->json([
            'success' => true,
            'status' => $availability->status
        ]);
    }

    public function appointments(Request $request)
    {
        // $appointments = Appointment::with(['user', 'doctor'])
        // ->where('doctor_id', auth('seller')->id())->latest()
        // ->paginate(10);

        $appointments = Appointment::with(['user', 'doctor'])
        ->where('doctor_id', auth('seller')->id());

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

        return view('seller.appointments.show', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status === 'cancelled') {
            return back()->with('error_msg', 'Appointment already cancelled');
        }

        $doctor = Seller::findOrFail($appointment->doctor_id);
        $user = User::findOrFail($appointment->user_id);

        if($request->status === 'cancelled'){

            $amount = $doctor->amount;
            $comm = $amount / commistion_charge();
            $doctor_refund = $amount - $comm;
            $user_refund = $amount;
            
            walletCredit('user', $user->wallet->id, $user_refund, 'Appointment Refund');
            walletDebit('doctor', $doctor->wallet->id, $doctor_refund, 'Appointment Cancelled');

            notifyUser(
                $appointment->user_id,
                'refund',
                'Appointment Cancelled',
                'Refund has been credited to your wallet.'
            );
        }

        notifyUser(
            $appointment->user_id,
            'appointment',
            'Appointment Status',
            'Your appointment has been '.$request->status.'.'
        );

        notifyAdmin(
            'appointment',
            'Appointment Status',
            'Doctor appointment has been '.$request->status.'.'
        );
        
        $appointment->update(['status' => $request->status]);

        if($user->email){
            Mail::to($user->email)->cc($doctor->email)->send(new BookingMail($appointment));
        }

        return back()->with('success_msg', 'Status updated');
    }

    public function edit_availability($id)
    {
        $availability = DoctorAvailability::findOrFail($id);
        return view('seller.availabilities.edit', compact('availability'));
    }

    public function delete_availability($id)
    {
        $availability = DoctorAvailability::findOrFail($id);
        $availability->delete();

        return back()->with('success_msg', 'Availability deleted Successfully');
    }


    public function update_availability(Request $request)
    {
        $request->validate([
            'available_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'interval' => 'required',
        ]);

        $doctorId = auth('seller')->id();

        // 🔴 Duplicate Slot Check
        $exists = DoctorAvailability::where('doctor_id', $doctorId)
            ->where('id', '!=', $request->avid)
            ->where('available_date', $request->available_date)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                ->orWhere(function ($q2) use ($request) {
                    $q2->where('start_time', '<=', $request->start_time)
                        ->where('end_time', '>=', $request->end_time);
                });
            })
            ->exists();

        if ($exists) {
            return back()->with('error_msg', 'This time slot already exists');
        }

        $update = DoctorAvailability::find($request->avid);
        $update->doctor_id = $doctorId;
        $update->available_date = $request->available_date;
        $update->start_time = $request->start_time;
        $update->end_time = $request->end_time;
        $update->interval = $request->interval;
        $update->status = $request->status ?? 'active';
        $update->save();
        

        notifyAdmin(
          'Availability',
          'Availability updated',
          'Availability updated by doctor '.auth('seller')->name.'.'
        );

        return back()->with('success_msg', 'Availability updated Successfully');
    }

    public function editAppointment(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'type' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'provider_subjective' => 'nullable|string',
            'provider_objective' => 'nullable|string',
            'provider_assessment' => 'nullable|string',
            'provider_plan' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($id);

        $appointment->notes = $request->notes;
        $appointment->type = $request->type;
        $appointment->status = $request->status;
        $appointment->provider_subjective = $request->provider_subjective; // patient notes
        $appointment->provider_objective = $request->provider_objective; // admin note
        // $appointment->provider_assessment = $request->provider_assessment;
        // $appointment->provider_plan = $request->provider_plan;
        $appointment->save();

        Prescription::firstOrCreate(
            [
                'doctor_id' => auth('seller')->id(),
                'user_id' => $appointment->user_id,
                'appointment_id' => $appointment->id,
            ],
            [
                'medicines' => 'wait',
                'notes' => $request->provider_subjective ?? $appointment->notes,
                'prescription_date' => now()->toDateString(),
            ]
        );

       return back()->with('success_msg', 'Appointment info updated Successfully');
    }
    
}
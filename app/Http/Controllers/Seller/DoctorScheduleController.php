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

class DoctorScheduleController extends Controller
{
    
    public function index()
    {
        $availabilities = DoctorAvailability::where('doctor_id', auth('seller')->id())->paginate(10);
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

    public function appointments()
    {
        $appointments = Appointment::with(['user', 'doctor'])
        ->where('doctor_id', auth('seller')->id())->latest()
        ->paginate(10);
        return view('seller.appointments.show', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status === 'cancelled') {
            return back()->with('error_msg', 'Appointment already cancelled');
        }

        if($request->status === 'cancelled'){

            $doctor = Seller::findOrFail($appointment->doctor_id);
            $user = User::findOrFail($appointment->user_id);

            $amount = $doctor->amount;
            $comm = $amount / 10;
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

        return back()->with('success_msg', 'Status updated');
    }
    
}

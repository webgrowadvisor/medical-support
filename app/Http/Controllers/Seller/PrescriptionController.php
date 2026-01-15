<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use session;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Seller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;

class PrescriptionController extends Controller
{
    

    public function index()
    {
        $prescriptions = Prescription::with(['appointment', 'user', 'doctor'])
        ->where('doctor_id', auth('seller')->id())
        ->latest()->paginate(10);

        return view('seller.prescription.show', compact('prescriptions'));
    }

    public function create($appointmentId)
    {
        $apppo = Appointment::findOrFail($appointmentId);
        return view('seller.prescription.add', compact('appointmentId', 'apppo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'medicines' => 'required|array',
        ]);

        Prescription::create([
            'doctor_id' => auth('seller')->id(),
            'user_id' => $request->user_id,
            'appointment_id' => $request->appointment_id ?? '',
            'medicines' => $request->medicines,
            'notes' => $request->notes,
            'prescription_date' => now()->toDateString(),
        ]);

        notifyUser(
            $request->user_id,
            'refund',
            'Prescription',
            'Doctor prescription give you'
        );

        return redirect()->route('doctor.prescriptions')
            ->with('success_msg', 'Prescription Added');
    }

    public function pdf($id)
    {
        $prescription = Prescription::findOrFail($id);

        $pdf = \PDF::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download('prescription-'.$id.'.pdf');
    }

    public function updateAvailabilityStatus(Request $request, $id)
    {
        $availability = Prescription::findOrFail($id);

        $availability->status = $availability->status === 'active'
            ? 'inactive'
            : 'active';

        $availability->save();

        return response()->json([
            'success' => true,
            'status' => $availability->status
        ]);
    }

}

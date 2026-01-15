<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Seller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;

class PrescriptionController extends Controller
{
    
    public function index()
    {
        $prescriptions = Prescription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest()->paginate(10);

        return view('front.prescription.show', compact('prescriptions'));
    }

    public function pdf($id)
    {
        $prescription = Prescription::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pdf = PDF::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download('prescription-'.$id.'.pdf');
    }

}

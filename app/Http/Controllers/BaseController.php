<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class BaseController extends Controller
{
    // Save Inquiry
    public function store(Request $request)
    {
        Inquiry::create($request->all());

        return back()->with('success','Inquiry Sent Successfully');
    }

    // Admin View
    public function index()
    {
        $inquiries = Inquiry::latest()->get();
        return view('admin.inquiries', compact('inquiries'));
    }
    
}

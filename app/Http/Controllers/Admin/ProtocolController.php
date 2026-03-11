<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use session;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Announcement;
use App\Models\Protocl;

class ProtocolController extends Controller
{
    
    public function index()
    {
        $announcements = Protocl::latest()->paginate(10);
        return view('admin.protocal.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.protocal.create');
    }

    public function store(Request $request)
    {
        Protocl::create($request->all());
        return redirect()->route('protocol.index');
    }

    public function edit($id)
    {
        $announcement = Protocl::findOrFail($id);
        return view('admin.protocal.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Protocl::findOrFail($id);
        $announcement->update($request->all());
        return redirect()->route('protocol.index');
    }

    public function destroy($id)
    {
        Protocl::findOrFail($id)->delete();
        return back();
    }

    // $announcement = Protocl::where('is_active',1)
    // ->where(function($q){
    //     $q->whereNull('start_at')
    //       ->orWhere('start_at','<=',now());
    // })
    // ->where(function($q){
    //     $q->whereNull('end_at')
    //       ->orWhere('end_at','>=',now());
    // })
    // ->latest()
    // ->first();

}

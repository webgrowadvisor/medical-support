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


class AnnouncementController extends Controller
{
    

    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        Announcement::create($request->all());
        return redirect()->route('announcements.index');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->all());
        return redirect()->route('announcements.index');
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back();
    }

    // $announcement = Announcement::where('is_active',1)
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

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    


    public function index()
    {
        $files = UserFile::where('user_id', auth()->id())->latest()->paginate(10);
        return view('front.file.index', compact('files'));
    }

    public function add()
    {
        return view('front.file.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'file_type' => 'required'
        ]);

        $file = $request->file('file');
        $name = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('uploads_userfiles', $name, 'public');

        UserFile::create([
            'user_id' => auth()->id(),
            'file_name' => $name,
            'file_path' => $path,
            'file_type' => $request->file_type,
            'added_by' => 'patient'
        ]);

        return back()->with('success_msg','File uploaded');
    }

    public function download($id)
    {
        $file = UserFile::findOrFail($id);
        $path = storage_path('app/public/'.$file->file_path);

        return response()->download($path);
    }

}

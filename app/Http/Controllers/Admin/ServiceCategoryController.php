<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ServiceCategory;
use Str;

class ServiceCategoryController extends Controller
{
    
    public function index()
    {
        $categories = ServiceCategory::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $count = 1;

        while (ServiceCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        ServiceCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('service-categories.index')
            ->with('success_msg', 'Category created successfully');

    }

    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }


    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // unique slug logic (ignore current id)
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $count = 1;

        while (
            ServiceCategory::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('service-categories.index')
            ->with('success_msg', 'Category updated successfully');
    }

}

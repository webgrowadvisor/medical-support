<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;
use App\Models\ServiceCategory;
use Str;

class ServiceController extends Controller
{
    
    public function index()
    {
        $services = Service::with('category')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('status',1)->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status'           => 'required|boolean',
            'category_id'      => 'required|exists:service_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'service_type'     => 'required|in:online,offline',
            'duration'         => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'commission'       => 'required|numeric|min:0',
            'commission_type'  => 'required|in:percent,fixed',
        ]);

        // unique slug
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $count = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        Service::create([
            'status'           => $request->status,
            'category_id'      => $request->category_id,
            'name'             => $request->name,
            'slug'             => $slug,
            'description'      => $request->description,
            'service_type'     => $request->service_type,
            'duration'         => $request->duration,
            'price'            => $request->price,
            'commission'       => $request->commission,
            'commission_type'  => $request->commission_type,
        ]);

        return redirect()
            ->route('services.index')
            ->with('success_msg', 'Service created successfully');
    }

    public function edit($id)
    {
        $plan = Service::findOrFail($id);
        $categories = ServiceCategory::where('status', 1)->get();

        return view('admin.services.edit', compact('plan', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $plan = Service::findOrFail($id);

        $request->validate([
            'status'           => 'required|boolean',
            'category_id'      => 'required|exists:service_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'required|string',
            'service_type'     => 'required|in:online,offline',
            'duration'         => 'required|integer|min:1',
            'price'            => 'required|numeric|min:0',
            'commission'       => 'required|numeric|min:0',
            'commission_type'  => 'required|in:percent,fixed',
        ]);

        // unique slug (ignore current service)
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $count = 1;

        while (
            Service::where('slug', $slug)
                ->where('id', '!=', $plan->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count++;
        }

        $plan->update([
            'status'           => $request->status,
            'category_id'      => $request->category_id,
            'name'             => $request->name,
            'slug'             => $slug,
            'description'      => $request->description,
            'service_type'     => $request->service_type,
            'duration'         => $request->duration,
            'price'            => $request->price,
            'commission'       => $request->commission,
            'commission_type'  => $request->commission_type,
        ]);

        return redirect()
            ->route('services.index')
            ->with('success_msg', 'Service updated successfully');
    }

}

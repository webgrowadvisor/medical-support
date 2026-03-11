<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Medicine;
use App\Models\Library;
use App\Models\Order;

class MedicineController extends Controller
{
    public function index()
    {
        
        $medicines = Medicine::latest()->paginate(10);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('status', 1)->latest()->get();
        return view('admin.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'image.mimes' => 'The image must be a file of type jpeg,png,jpg.',
        ]);

        $data = [
            'name'    => $request->name,
            'status' => $request->status,
            'other' => $request->category_id ?? '',
            'description' => $request->description ?? '',
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            $paths = uploadWebp($request->file('image'), 'medcine_image');
            $data['image'] = $paths['webp'] ?? $paths['original'];
        }

        Medicine::create($data);

        return redirect()->route('admin.medicines')->with('success_msg','Medicine Added Successfully');
    }

    public function edit($id)
    {
        $categories = ServiceCategory::where('status', 1)->latest()->get();
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'image.mimes' => 'The image must be a file of type jpeg,png,jpg.',
        ]);

        $data = [
            'name'        => $request->name,
            'status'      => $request->status,
            'other'       => $request->category_id ?? '',
            'description' => $request->description ?? '',
            'price'       => $request->price,
            'stock'       => $request->stock,
        ];

        // ✅ If new image uploaded
        if ($request->hasFile('image')) {

            // delete old image (optional but recommended)
            if ($medicine->image && file_exists(public_path($medicine->image))) {
                unlink(public_path($medicine->image));
            }

            $paths = uploadWebp($request->file('image'), 'medcine_image');
            $data['image'] = $paths['webp'] ?? $paths['original'];
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines')
            ->with('success_msg', 'Medicine Updated Successfully');
    }

    public function library()
    {
        $medicines = $books = Library::where('status', true)->latest()->get();
        // $books = Library::where('status', true)
        //         ->when($request->type, function ($q) use ($request) {
        //             $q->where('type', $request->type);
        //         })
        //         ->latest()
        //         ->get();
        return view('admin.library.index', compact('medicines'));
    }

    public function library_create()
    {
        $medicines = $books = Library::where('status', true)->latest()->get();
        return view('admin.library.create', compact('medicines'));
    }

    public function libary_store(Request $request)
    {
        $library = new Library();
        $library->title = $request->title;
        $library->short_description = $request->short_description;
        $library->full_content = $request->full_content;
        $library->type = $request->type;
        $library->status = $request->status;

        if ($request->hasFile('cover_image')) {
            $paths = uploadWebp($request->file('cover_image'), 'library');
            $library->cover_image = $paths['webp'] ?? $paths['original'];
        }

        if ($request->hasFile('file')) {
            $library->file_url = $request->file('file')->store('library/files');
        }

        $library->save();

        return redirect()->back()->with('success_msg', 'Library item added successfully');
    }



    public function orderList(Request $request)
    {   
        $search = $request->search;

        $orders = Order::with('items.medicine');
        if (!empty($search)) {
          $orders = $orders->where('order_number', 'LIKE', "%{$search}%");
        }

        $orders = $orders->latest()->paginate(10);

        return view('admin.order.index', compact('orders'));
    }

    public function orderView($order)
    {
        $orders = Order::with('items.medicine')
            ->where('order_number', $order)
            ->get();

        return view('admin.order.show', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);

        // Optional: Agar cancel kare to stock wapas kare
        if ($request->status == 'cancelled' && $order->status != 'cancelled') {
            foreach ($order->items as $item) {
                $item->medicine->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'status' => $request->status
        ]);

        notifyUser(
            $order->user_id,
            'Order',
            'Order Status',
            'You order '.$order->order_number. ' status has been '. $request->status
        );

        return back()->with('success_msg', 'Order Status Updated Successfully');
    }

}

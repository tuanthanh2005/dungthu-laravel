<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerDuration;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CustomerDuration::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('order_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'expiring') {
                $query->expiring();
            } elseif ($status === 'expired') {
                $query->expired();
            }
        }

        // Stats calculation
        $totalCount = CustomerDuration::count();
        $activeCount = CustomerDuration::active()->count();
        $expiringCount = CustomerDuration::expiring()->count();
        $expiredCount = CustomerDuration::expired()->count();

        // Paginate results
        $durations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.customer-durations.index', compact(
            'durations',
            'totalCount',
            'activeCount',
            'expiringCount',
            'expiredCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $orders = Order::orderBy('created_at', 'desc')->take(100)->get();

        return view('admin.customer-durations.create', compact('products', 'users', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'product_name' => 'required|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'order_code' => 'nullable|string|max:255',
            'total_duration' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        // Auto-detect user info from user_id if not filled manually
        if ($request->filled('user_id') && (empty($validated['customer_name']) || empty($validated['customer_email']))) {
            $user = User::find($request->user_id);
            if ($user) {
                $validated['customer_name'] = $validated['customer_name'] ?: $user->name;
                $validated['customer_email'] = $validated['customer_email'] ?: $user->email;
                $validated['customer_phone'] = $validated['customer_phone'] ?: $user->phone;
            }
        }

        // Auto-detect product name if not filled manually
        if ($request->filled('product_id') && empty($validated['product_name'])) {
            $product = Product::find($request->product_id);
            if ($product) {
                $validated['product_name'] = $product->name;
            }
        }

        // Auto-detect order code if order_id is provided
        if ($request->filled('order_id') && empty($validated['order_code'])) {
            $order = Order::find($request->order_id);
            if ($order) {
                $validated['order_code'] = $order->order_code ?? ('DH' . $order->id);
            }
        }

        // Set start_date default to now if empty
        if (empty($validated['start_date'])) {
            $validated['start_date'] = now();
        }

        CustomerDuration::create($validated);

        return redirect()->route('admin.customer-durations')->with('success', 'Cấp phát thời hạn dịch vụ thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customerDuration = CustomerDuration::findOrFail($id);
        $products = Product::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $orders = Order::orderBy('created_at', 'desc')->take(100)->get();

        return view('admin.customer-durations.edit', compact('customerDuration', 'products', 'users', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customerDuration = CustomerDuration::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'product_name' => 'required|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'order_code' => 'nullable|string|max:255',
            'total_duration' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        // Allow clearing expiry_date (admin may want to set it back to active/indefinite)
        $validated['expiry_date'] = $request->filled('expiry_date') ? $request->expiry_date : null;

        $customerDuration->update($validated);

        return redirect()->route('admin.customer-durations')->with('success', 'Cập nhật thời hạn dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customerDuration = CustomerDuration::findOrFail($id);
        $customerDuration->delete();

        return redirect()->route('admin.customer-durations')->with('success', 'Xóa thời hạn dịch vụ thành công!');
    }
}

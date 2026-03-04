<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuffService;
use App\Models\BuffServer;
use App\Models\BuffServerPrice;
use App\Models\BuffOrder;
use Illuminate\Http\Request;

class AdminBuffController extends Controller
{
    // ===== SERVERS =====
    public function serversIndex()
    {
        $servers = BuffServer::orderBy('name')->paginate(20);
        return view('admin.buff.servers.index', compact('servers'));
    }

    public function serversCreate()
    {
        return view('admin.buff.servers.create');
    }

    public function serversStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:buff_servers|max:100',
            'description' => 'nullable|max:500',
            'is_active' => 'boolean',
        ]);

        BuffServer::create($validated);

        return redirect()->route('admin.buff.servers.index')->with('success', 'Tạo server thành công');
    }

    public function serversEdit(BuffServer $buffServer)
    {
        return view('admin.buff.servers.edit', compact('buffServer'));
    }

    public function serversUpdate(BuffServer $buffServer, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:buff_servers,name,' . $buffServer->id . '|max:100',
            'description' => 'nullable|max:500',
            'is_active' => 'boolean',
            'is_maintenance' => 'boolean',
        ]);

        $buffServer->update($validated);

        return redirect()->route('admin.buff.servers.index')->with('success', 'Cập nhật server thành công');
    }

    public function serversDestroy(BuffServer $buffServer)
    {
        $buffServer->delete();
        return redirect()->route('admin.buff.servers.index')->with('success', 'Xóa server thành công');
    }

    // ===== SERVICES =====
    public function servicesIndex()
    {
        $services = BuffService::paginate(20);
        return view('admin.buff.services.index', compact('services'));
    }

    public function servicesCreate()
    {
        return view('admin.buff.services.create');
    }

    public function servicesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'platform' => 'required|in:facebook,tiktok,instagram',
            'service_type' => 'required|in:like,follow,comment,view',
            'description' => 'nullable|max:500',
            'base_price' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'min_amount' => 'required|integer|min:1',
            'max_amount' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        BuffService::create($validated);

        return redirect()->route('admin.buff.services.index')->with('success', 'Tạo dịch vụ thành công');
    }

    public function servicesEdit(BuffService $buffService)
    {
        return view('admin.buff.services.edit', compact('buffService'));
    }

    public function servicesUpdate(BuffService $buffService, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'platform' => 'required|in:facebook,tiktok,instagram',
            'service_type' => 'required|in:like,follow,comment,view',
            'description' => 'nullable|max:500',
            'base_price' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'min_amount' => 'required|integer|min:1',
            'max_amount' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $buffService->update($validated);

        return redirect()->route('admin.buff.services.index')->with('success', 'Cập nhật dịch vụ thành công');
    }

    public function servicesDestroy(BuffService $buffService)
    {
        $buffService->delete();
        return redirect()->route('admin.buff.services.index')->with('success', 'Xóa dịch vụ thành công');
    }

    // ===== SERVER PRICES =====
    public function pricesIndex()
    {
        $prices = BuffServerPrice::with(['buffService', 'buffServer'])->paginate(20);
        return view('admin.buff.prices.index', compact('prices'));
    }

    public function pricesCreate()
    {
        $services = BuffService::where('is_active', true)->get();
        $servers = BuffServer::where('is_active', true)->get();
        return view('admin.buff.prices.create', compact('services', 'servers'));
    }

    public function pricesStore(Request $request)
    {
        $validated = $request->validate([
            'buff_service_id' => 'required|exists:buff_services,id',
            'buff_server_id' => 'required|exists:buff_servers,id',
            'price' => 'required|numeric|min:0',
        ]);

        // Check unique
        $exists = BuffServerPrice::where('buff_service_id', $validated['buff_service_id'])
            ->where('buff_server_id', $validated['buff_server_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Giá cho dịch vụ và server này đã tồn tại']);
        }

        BuffServerPrice::create($validated);

        return redirect()->route('admin.buff.prices.index')->with('success', 'Tạo giá server thành công');
    }

    public function pricesEdit(BuffServerPrice $buffServerPrice)
    {
        $services = BuffService::where('is_active', true)->get();
        $servers = BuffServer::where('is_active', true)->get();
        return view('admin.buff.prices.edit', compact('buffServerPrice', 'services', 'servers'));
    }

    public function pricesUpdate(BuffServerPrice $buffServerPrice, Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $buffServerPrice->update($validated);

        return redirect()->route('admin.buff.prices.index')->with('success', 'Cập nhật giá thành công');
    }

    public function pricesDestroy(BuffServerPrice $buffServerPrice)
    {
        $buffServerPrice->delete();
        return redirect()->route('admin.buff.prices.index')->with('success', 'Xóa giá thành công');
    }

    // ===== ORDERS MANAGEMENT =====
    public function ordersIndex(Request $request)
    {
        $query = BuffOrder::with(['user', 'buffService', 'buffServer']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('order_code', 'like', '%' . $request->search . '%')
                ->orWhere('social_link', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('email', 'like', '%' . request('search') . '%');
                });
        }

        $orders = $query->orderByDesc('created_at')->paginate(20);
        $statuses = ['pending', 'paid', 'processing', 'completed', 'cancelled', 'refunded'];

        return view('admin.buff.orders.index', compact('orders', 'statuses'));
    }

    public function ordersEdit(BuffOrder $buffOrder)
    {
        return view('admin.buff.orders.edit', compact('buffOrder'));
    }

    public function ordersUpdate(BuffOrder $buffOrder, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,cancelled,refunded',
            'actual_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ]);

        $buffOrder->update($validated);

        // Nếu chuyển sang processing, ghi nhận thời gian bắt đầu
        if ($request->status === 'processing' && !$buffOrder->started_at) {
            $buffOrder->update(['started_at' => now()]);
        }

        // Nếu hoàn thành, ghi nhận thời gian kết thúc
        if ($request->status === 'completed' && !$buffOrder->completed_at) {
            $buffOrder->update(['completed_at' => now()]);
        }

        return redirect()->route('admin.buff.orders.index')->with('success', 'Cập nhật đơn hàng thành công');
    }

    public function ordersShow(BuffOrder $buffOrder)
    {
        return view('admin.buff.orders.show', compact('buffOrder'));
    }

    // Dashboard
    public function dashboard()
    {
        $totalOrders = BuffOrder::count();
        $totalRevenue = BuffOrder::where('status', 'completed')->sum('actual_price') 
            ?: BuffOrder::where('status', 'completed')->sum('total_price');
        $processingOrders = BuffOrder::where('status', 'processing')->count();
        $completedOrders = BuffOrder::where('status', 'completed')->count();

        $recentOrders = BuffOrder::with(['user', 'buffService'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.buff.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'processingOrders',
            'completedOrders',
            'recentOrders'
        ));
    }
}

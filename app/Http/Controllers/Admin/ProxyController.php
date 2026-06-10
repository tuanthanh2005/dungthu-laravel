<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proxy;
use Illuminate\Http\Request;
use App\Services\GoogleIndexingService;

class ProxyController extends Controller
{
    public function index()
    {
        $proxies = Proxy::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.proxies.index', compact('proxies'));
    }

    public function create()
    {
        return view('admin.proxies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'protocol' => 'nullable|string|max:255',
            'ip' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Proxy::create($validated);

        try {
            GoogleIndexingService::publishUrlStatic(route('vpn.tab', 'proxy'), 'URL_UPDATED', 'proxy_create');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] proxy_create failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.proxies')->with('success', 'Thêm Proxy thành công!');
    }

    public function edit(Proxy $proxy)
    {
        return view('admin.proxies.edit', compact('proxy'));
    }

    public function update(Request $request, Proxy $proxy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'protocol' => 'nullable|string|max:255',
            'ip' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $proxy->update($validated);

        try {
            GoogleIndexingService::publishUrlStatic(route('vpn.tab', 'proxy'), 'URL_UPDATED', 'proxy_update');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] proxy_update failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.proxies')->with('success', 'Cập nhật Proxy thành công!');
    }

    public function destroy(Proxy $proxy)
    {
        $proxy->delete();

        try {
            GoogleIndexingService::publishUrlStatic(route('vpn.tab', 'proxy'), 'URL_UPDATED', 'proxy_delete');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[GoogleIndexing] proxy_delete failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.proxies')->with('success', 'Đã xóa Proxy thành công!');
    }
}

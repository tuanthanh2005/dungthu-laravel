<?php

namespace App\Http\Controllers;

use App\Models\BuffService;
use App\Models\BuffServer;
use Illuminate\Http\Request;

class BuffServiceController extends Controller
{
    public function index()
    {
        $services = BuffService::where('is_active', true)
            ->get()
            ->groupBy('platform');

        return view('buff.index', compact('services'));
    }

    public function show(BuffService $buffService)
    {
        $servers = BuffServer::where('is_active', true)
            ->where('is_maintenance', false)
            ->get();

        return view('buff.service-detail', compact('buffService', 'servers'));
    }

    public function getServerPrice(BuffService $buffService, BuffServer $buffServer)
    {
        $price = $buffService->getPriceForServer($buffServer->id);
        
        return response()->json([
            'price' => $price,
            'base_price' => 0,
        ]);
    }

    public function calculatePrice(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:buff_services,id',
            'server_id' => 'required|exists:buff_servers,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $service = BuffService::find($validated['service_id']);
        $unitPrice = $service->getPriceForServer($validated['server_id']);
        
        $totalPrice = ($unitPrice * $validated['quantity']);

        return response()->json([
            'unit_price' => $unitPrice,
            'base_price' => 0,
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'total_price_formatted' => number_format($totalPrice, 0, ',', '.') . ' đ',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Proxy;
use Illuminate\Http\Request;

class VpnProxyController extends Controller
{
    public function index()
    {
        // Get VPN products
        $vpnProducts = Product::where('is_vpn', true)
            ->with(['categoryRelation', 'features'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vpn-proxy.index', compact('vpnProducts'));
    }
}

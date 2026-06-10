<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Proxy;
use Illuminate\Http\Request;

class VpnProxyController extends Controller
{
    public function index($tab = 'vpn')
    {
        // Prevent invalid tabs
        if (!in_array($tab, ['vpn', 'proxy'])) {
            return redirect()->route('vpn.tab', ['tab' => 'vpn']);
        }

        // Get VPN products
        $vpnProducts = Product::where('is_vpn', true)
            ->with(['categoryRelation', 'features'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get Proxies
        $proxies = Proxy::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('vpn-proxy.index', compact('vpnProducts', 'proxies', 'tab'));
    }
}

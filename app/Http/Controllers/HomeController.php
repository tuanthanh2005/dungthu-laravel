<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Blog;
use App\Models\Order;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách categories active và show_on_home
        $categories = ProductCategory::where('is_active', true)
            ->where('show_on_home', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
        
        // Lấy 4 sản phẩm featured cho trang home - Hàng 1
        $featuredProducts = Product::featured(8)->get();
        
        // Lấy 8 sản phẩm độc quyền - 2 hàng x 4 sản phẩm
        $highlightProducts = Product::where('is_exclusive', true)->latest()->take(8)->get();
        
        // Lấy sản phẩm Combo AI giá rẻ (12 items = 3 hàng x 4)
        $comboAiProducts = Product::query()
            ->where('is_combo_ai', true)
            ->inStock()
            ->latest()
            ->take(12)
            ->get();
        
        // Lấy 4 blog mới nhất (published)
        $latestBlogs = Blog::published()->orderBy('published_at', 'desc')->take(4)->get();
        

        // S?n ph?m ?ang gi?m gi? (hi?n th? 2-3 sp tr?n home)
        // ??m ng??c ??n cu?i ng?y (het gio thi an block, khong reset gia)
        $flashSaleEnabled = SiteSetting::getValue('flash_sale_enabled', '1') === '1';
        $saleEndsAt = now()->endOfDay();
        if ($flashSaleEnabled && now()->lt($saleEndsAt)) {
            $saleProducts = Product::query()
                ->where('is_flash_sale', true)
                ->inStock()
                ->latest()
                ->take(4)
                ->get();
        } else {
            $saleProducts = collect();
        }

        $recentPurchases = Cache::remember('home.recent_purchases.v1', now()->addMinutes(5), function () {
            return Order::query()
                ->with(['orderItems.product'])
                ->whereNotIn('status', ['cancelled'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function (Order $order) {
                    $firstItem = $order->orderItems->first();
                    $product = $firstItem?->product;
                    $extraItems = max(0, $order->orderItems->count() - 1);

                    $verb = in_array($order->status, ['completed', 'delivered', 'shipped'], true) ? 'mua' : 'đặt';

                    return [
                        'customer_name' => self::maskCustomerName((string) $order->customer_name),
                        'verb' => $verb,
                        'product_name' => $product?->name ?? 'Sản phẩm',
                        'product_slug' => $product?->slug,
                        'product_url' => $product?->slug ? route('product.show', $product->slug) : null,
                        'extra_items' => $extraItems,
                        'time_ago' => optional($order->created_at)->diffForHumans(),
                    ];
                })
                ->values()
                ->all();
        });

        return view('home', compact(
            'categories',
            'featuredProducts',
            'highlightProducts',
            'comboAiProducts',
            'latestBlogs',
            'recentPurchases',
            'saleProducts',
            'saleEndsAt'
        ));
    }

    private static function maskCustomerName(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return 'Khách hàng';
        }

        $parts = preg_split('/\\s+/u', $name) ?: [];
        $parts = array_values(array_filter($parts, fn ($p) => $p !== ''));

        if (count($parts) === 1) {
            $first = mb_substr($parts[0], 0, 1);
            return $first . '***';
        }

        $givenName = $parts[count($parts) - 1];
        $surnameInitial = mb_substr($parts[0], 0, 1);

        return $givenName . ' ' . $surnameInitial . '.';
    }
}


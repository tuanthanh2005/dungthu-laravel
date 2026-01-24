<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Blog;
use App\Models\TiktokDeal;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 4 sản phẩm featured cho trang home - Hàng 1
        $featuredProducts = Product::featured(4)->get();
        
        // Lấy 6 sản phẩm độc quyền - Hàng 2
        $highlightProducts = Product::where('is_exclusive', true)->latest()->take(6)->get();
        
        // Lấy deals Tiktok nổi bật (4 items)
        $tiktokDeals = TiktokDeal::featured()->active()->ordered()->take(4)->get();
        
        // Lấy 3 blog mới nhất (published)
        $latestBlogs = Blog::published()->orderBy('published_at', 'desc')->take(3)->get();
        
        return view('home', compact('featuredProducts', 'highlightProducts', 'tiktokDeals', 'latestBlogs'));
    }
}

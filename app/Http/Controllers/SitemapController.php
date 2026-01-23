<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Blog;
use App\Models\TiktokDeal;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $blogs = Blog::all();
        $tiktokDeals = TiktokDeal::where('is_active', true)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Trang chủ
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';
        
        // Shop
        $xml .= '<url>';
        $xml .= '<loc>' . route('shop') . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.9</priority>';
        $xml .= '</url>';
        
        // Products
        foreach ($products as $product) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('product.show', $product->slug) . '</loc>';
            $xml .= '<lastmod>' . $product->updated_at->format('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        
        // Blog index
        $xml .= '<url>';
        $xml .= '<loc>' . route('blog.index') . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
        
        // Blogs
        foreach ($blogs as $blog) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('blog.show', $blog->slug) . '</loc>';
            $xml .= '<lastmod>' . $blog->updated_at->format('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }
        
        // TikTok Deals
        $xml .= '<url>';
        $xml .= '<loc>' . route('tiktok-deals.index') . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>0.8</priority>';
        $xml .= '</url>';
        
        foreach ($tiktokDeals as $deal) {
            $xml .= '<url>';
            $xml .= '<loc>' . route('tiktok-deals.show', $deal->slug) . '</loc>';
            $xml .= '<lastmod>' . $deal->updated_at->format('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }
        
        // Card Exchange
        $xml .= '<url>';
        $xml .= '<loc>' . route('card-exchange.index') . '</loc>';
        $xml .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.6</priority>';
        $xml .= '</url>';
        
        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}

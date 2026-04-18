<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Blog;
use App\Models\CommunityPost;
use App\Models\BuffService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        return \Illuminate\Support\Facades\Cache::remember('sitemap.xml', now()->addDay(), function () {
            // Only get necessary columns to save memory
            $blogs = Blog::published()->select(['slug', 'updated_at', 'image', 'title', 'category'])->orderBy('updated_at', 'desc')->get();
            $products = Product::select(['slug', 'name', 'updated_at', 'image', 'category'])->orderBy('updated_at', 'desc')->get();

            // Lấy danh mục unique
            $productCategories = $products->pluck('category')->unique()->filter();
            $blogCategories = $blogs->pluck('category')->unique()->filter();

            $communityPosts = [];
            if (class_exists(CommunityPost::class)) {
                try {
                    $communityPosts = CommunityPost::select(['slug', 'updated_at'])->orderBy('updated_at', 'desc')->get();
                } catch (\Throwable $e) { $communityPosts = collect(); }
            }

            $buffServices = [];
            if (class_exists(BuffService::class)) {
                try {
                    $buffServices = BuffService::select(['id', 'updated_at'])->orderBy('updated_at', 'desc')->get();
                } catch (\Throwable $e) { $buffServices = collect(); }
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

            // Main Pages
            $xml .= $this->buildUrl(url('/'), date('Y-m-d'), 'daily', '1.0');
            $xml .= $this->buildUrl(route('shop'), date('Y-m-d'), 'daily', '0.9');
            $xml .= $this->buildUrl(route('web-design'), date('Y-m-d'), 'monthly', '0.7');
            $xml .= $this->buildUrl(route('policy'), date('Y-m-d'), 'monthly', '0.5');

            // Product Categories
            foreach ($productCategories as $cat) {
                $xml .= $this->buildUrl(route('shop') . '?category=' . urlencode($cat), date('Y-m-d'), 'daily', '0.8');
            }

            // Products
            foreach ($products as $p) {
                $imageXml = $p->image ? '<image:image><image:loc>'. $this->escapeXml($p->image) .'</image:loc><image:title>'. $this->escapeXml($p->name) .'</image:title></image:image>' : '';
                $xml .= $this->buildUrl(route('product.show', $p->slug), $p->updated_at->format('Y-m-d'), 'weekly', '0.8', $imageXml);
            }

            // Blog Pages
            $xml .= $this->buildUrl(route('blog.index'), date('Y-m-d'), 'daily', '0.8');
            foreach ($blogCategories as $cat) {
                $xml .= $this->buildUrl(route('blog.category', $cat), date('Y-m-d'), 'daily', '0.7');
            }
            foreach ($blogs as $b) {
                $imageXml = $b->image ? '<image:image><image:loc>'. $this->escapeXml($b->image) .'</image:loc><image:title>'. $this->escapeXml($b->title) .'</image:title></image:image>' : '';
                $xml .= $this->buildUrl(route('blog.show', $b->slug), $b->updated_at->format('Y-m-d'), 'weekly', '0.7', $imageXml);
            }

            // Buff Services
            foreach ($buffServices as $s) {
                $xml .= $this->buildUrl(route('buff.show', $s), $s->updated_at->format('Y-m-d'), 'weekly', '0.6');
            }

            // Community
            foreach ($communityPosts as $post) {
                $xml .= $this->buildUrl(route('community.show', $post), $post->updated_at->format('Y-m-d'), 'weekly', '0.6');
            }

            $xml .= $this->buildUrl(route('card-exchange.index'), date('Y-m-d'), 'monthly', '0.6');
            $xml .= '</urlset>';

            return response($xml, 200)
                ->header('Content-Type', 'application/xml')
                ->header('X-Robots-Tag', 'noindex');
        });
    }

    /**
     * Build a single <url> element.
     */
    private function buildUrl(string $loc, string $lastmod, string $changefreq, string $priority, string $extra = ''): string
    {
        $xml  = '<url>';
        $xml .= '<loc>' . $this->escapeXml($loc) . '</loc>';
        $xml .= '<lastmod>' . $lastmod . '</lastmod>';
        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= $extra;
        $xml .= '</url>';
        return $xml;
    }

    /**
     * Escape special XML characters.
     */
    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}

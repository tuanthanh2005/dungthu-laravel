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
        // Only get published blogs
        $blogs = Blog::published()->orderBy('updated_at', 'desc')->get();
        $products = Product::orderBy('updated_at', 'desc')->get();

        // Lấy danh mục sản phẩm unique
        $productCategories = Product::select('category')->distinct()->pluck('category');
        // Lấy danh mục blog unique (from published blogs only)
        $blogCategories = Blog::published()->select('category')->distinct()->pluck('category');

        // Community posts
        $communityPosts = [];
        if (class_exists(CommunityPost::class)) {
            try {
                $communityPosts = CommunityPost::orderBy('updated_at', 'desc')->get();
            } catch (\Throwable $e) {
                $communityPosts = collect();
            }
        }

        // Buff services
        $buffServices = [];
        if (class_exists(BuffService::class)) {
            try {
                $buffServices = BuffService::orderBy('updated_at', 'desc')->get();
            } catch (\Throwable $e) {
                $buffServices = collect();
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // ── Trang chủ ──
        $xml .= $this->buildUrl(url('/'), date('Y-m-d'), 'daily', '1.0');

        // ── Shop ──
        $xml .= $this->buildUrl(route('shop'), date('Y-m-d'), 'daily', '0.9');

        // ── Trang tĩnh ──
        $xml .= $this->buildUrl(route('web-design'), date('Y-m-d'), 'monthly', '0.7');
        $xml .= $this->buildUrl(route('policy'), date('Y-m-d'), 'monthly', '0.5');

        // ── Product Categories ──
        foreach ($productCategories as $category) {
            if ($category) {
                $xml .= $this->buildUrl(
                    route('shop') . '?category=' . urlencode($category),
                    date('Y-m-d'),
                    'daily',
                    '0.85'
                );
            }
        }

        // ── Products ──
        foreach ($products as $product) {
            $imageXml = '';
            if ($product->image) {
                $imageXml = '<image:image><image:loc>' . $this->escapeXml($product->image) . '</image:loc>';
                $imageXml .= '<image:title>' . $this->escapeXml($product->name) . '</image:title>';
                $imageXml .= '</image:image>';
            }
            $xml .= $this->buildUrl(
                route('product.show', $product->slug),
                $product->updated_at->format('Y-m-d'),
                'weekly',
                '0.8',
                $imageXml
            );
        }

        // ── Blog index ──
        $xml .= $this->buildUrl(route('blog.index'), date('Y-m-d'), 'daily', '0.8');

        // ── Blog Categories ──
        foreach ($blogCategories as $category) {
            if ($category) {
                $xml .= $this->buildUrl(
                    route('blog.category', $category),
                    date('Y-m-d'),
                    'daily',
                    '0.75'
                );
            }
        }

        // ── Blogs (published only) ──
        foreach ($blogs as $blog) {
            $imageXml = '';
            $rawImage = $blog->image;
            if ($rawImage) {
                $imageXml = '<image:image><image:loc>' . $this->escapeXml($rawImage) . '</image:loc>';
                $imageXml .= '<image:title>' . $this->escapeXml($blog->title) . '</image:title>';
                $imageXml .= '</image:image>';
            }
            $xml .= $this->buildUrl(
                route('blog.show', $blog->slug),
                $blog->updated_at->format('Y-m-d'),
                'weekly',
                '0.7',
                $imageXml
            );
        }

        // ── Buff Services ──
        if (count($buffServices) > 0) {
            $xml .= $this->buildUrl(route('buff.index'), date('Y-m-d'), 'weekly', '0.7');
            foreach ($buffServices as $service) {
                $xml .= $this->buildUrl(
                    route('buff.show', $service),
                    $service->updated_at->format('Y-m-d'),
                    'weekly',
                    '0.6'
                );
            }
        }

        // ── Community ──
        if (count($communityPosts) > 0) {
            $xml .= $this->buildUrl(route('community.index'), date('Y-m-d'), 'daily', '0.7');
            foreach ($communityPosts as $post) {
                $xml .= $this->buildUrl(
                    route('community.show', $post),
                    $post->updated_at->format('Y-m-d'),
                    'weekly',
                    '0.6'
                );
            }
        }

        // ── Card Exchange ──
        $xml .= $this->buildUrl(route('card-exchange.index'), date('Y-m-d'), 'monthly', '0.6');

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('X-Robots-Tag', 'noindex'); // Sitemap itself shouldn't be indexed
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

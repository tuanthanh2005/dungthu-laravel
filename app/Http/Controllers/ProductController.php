<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TiktokDeal;
use App\Helpers\PathHelper;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const SEO_KEYWORDS = [
        'gpt' => [
            'label' => 'GPT',
            'heading' => 'Mua tài khoản ChatGPT giá rẻ',
            'title' => 'Mua tài khoản ChatGPT, GPT Plus giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản ChatGPT, GPT Plus và các gói GPT đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['gpt', 'chatgpt', 'chat gpt', 'openai'],
        ],
        'cursor' => [
            'label' => 'Cursor',
            'heading' => 'Mua tài khoản Cursor AI Pro giá rẻ',
            'title' => 'Mua tài khoản Cursor AI Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Cursor AI Pro, Cursor Pro và các gói Cursor đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['cursor', 'cursor ai', 'cursor pro'],
        ],
        'claude' => [
            'label' => 'Claude',
            'heading' => 'Mua tài khoản Claude AI giá rẻ',
            'title' => 'Mua tài khoản Claude AI giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Claude AI, Claude Pro và các gói Claude đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['claude', 'claude ai', 'claude pro', 'anthropic'],
        ],
        'canva' => [
            'label' => 'Canva',
            'heading' => 'Mua tài khoản Canva Pro giá rẻ',
            'title' => 'Mua tài khoản Canva Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Canva Pro và các gói Canva đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['canva', 'canva pro'],
        ],
        'capcut' => [
            'label' => 'CapCut',
            'heading' => 'Mua tài khoản CapCut Pro giá rẻ',
            'title' => 'Mua tài khoản CapCut Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản CapCut Pro và các gói CapCut đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['capcut', 'capcut pro'],
        ],
        'vpn' => [
            'label' => 'VPN',
            'heading' => 'Mua tài khoản VPN giá rẻ',
            'title' => 'Mua tài khoản VPN giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản VPN và các gói VPN đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['vpn'],
        ],
        'gemini' => [
            'label' => 'Gemini',
            'heading' => 'Mua tài khoản Gemini Advanced giá rẻ',
            'title' => 'Mua tài khoản Gemini Advanced giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Gemini Advanced và các gói Gemini đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['gemini', 'gemini advanced', 'google gemini'],
        ],
        'midjourney' => [
            'label' => 'Midjourney',
            'heading' => 'Mua tài khoản Midjourney giá rẻ',
            'title' => 'Mua tài khoản Midjourney giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Midjourney và các gói tạo ảnh AI đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['midjourney', 'mid journey'],
        ],
        'perplexity' => [
            'label' => 'Perplexity',
            'heading' => 'Mua tài khoản Perplexity Pro giá rẻ',
            'title' => 'Mua tài khoản Perplexity Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Perplexity Pro và các gói Perplexity đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['perplexity', 'perplexity pro'],
        ],
        'poe' => [
            'label' => 'Poe',
            'heading' => 'Mua tài khoản Poe AI giá rẻ',
            'title' => 'Mua tài khoản Poe AI giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Poe AI và các gói Poe đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['poe', 'poe ai', 'poe pro'],
        ],
        'copilot' => [
            'label' => 'Copilot',
            'heading' => 'Mua tài khoản Copilot Pro giá rẻ',
            'title' => 'Mua tài khoản Copilot Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Copilot Pro và các gói Copilot đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['copilot', 'copilot pro', 'microsoft copilot'],
        ],
        'notion' => [
            'label' => 'Notion',
            'heading' => 'Mua tài khoản Notion Plus giá rẻ',
            'title' => 'Mua tài khoản Notion Plus giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Notion Plus, Notion AI và các gói Notion đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['notion', 'notion plus', 'notion ai'],
        ],
        'grammarly' => [
            'label' => 'Grammarly',
            'heading' => 'Mua tài khoản Grammarly Premium giá rẻ',
            'title' => 'Mua tài khoản Grammarly Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Grammarly Premium và các gói Grammarly đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['grammarly', 'grammarly premium'],
        ],
        'quillbot' => [
            'label' => 'QuillBot',
            'heading' => 'Mua tài khoản QuillBot Premium giá rẻ',
            'title' => 'Mua tài khoản QuillBot Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản QuillBot Premium và các gói QuillBot đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['quillbot', 'quill bot', 'quillbot premium'],
        ],
        'turnitin' => [
            'label' => 'Turnitin',
            'heading' => 'Mua tài khoản Turnitin giá rẻ',
            'title' => 'Mua tài khoản Turnitin giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Turnitin và các dịch vụ kiểm tra đạo văn đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['turnitin', 'turnitin ai'],
        ],
        'office' => [
            'label' => 'Office 365',
            'heading' => 'Mua tài khoản Office 365 giá rẻ',
            'title' => 'Mua tài khoản Office 365 giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Office 365, Microsoft 365 và các gói Office đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['office', 'office 365', 'microsoft 365', 'microsoft office'],
        ],
        'adobe' => [
            'label' => 'Adobe',
            'heading' => 'Mua tài khoản Adobe giá rẻ',
            'title' => 'Mua tài khoản Adobe giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Adobe, Adobe Creative Cloud và các gói Adobe đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['adobe', 'adobe creative cloud', 'creative cloud'],
        ],
        'freepik' => [
            'label' => 'Freepik',
            'heading' => 'Mua tài khoản Freepik Premium giá rẻ',
            'title' => 'Mua tài khoản Freepik Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Freepik Premium và các gói Freepik đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['freepik', 'freepik premium'],
        ],
        'envato' => [
            'label' => 'Envato',
            'heading' => 'Mua tài khoản Envato Elements giá rẻ',
            'title' => 'Mua tài khoản Envato Elements giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Envato Elements và các gói Envato đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['envato', 'envato elements'],
        ],
        'netflix' => [
            'label' => 'Netflix',
            'heading' => 'Mua tài khoản Netflix giá rẻ',
            'title' => 'Mua tài khoản Netflix giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Netflix và các gói Netflix đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['netflix'],
        ],
        'spotify' => [
            'label' => 'Spotify',
            'heading' => 'Mua tài khoản Spotify Premium giá rẻ',
            'title' => 'Mua tài khoản Spotify Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Spotify Premium và các gói Spotify đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['spotify', 'spotify premium'],
        ],
        'youtube' => [
            'label' => 'YouTube',
            'heading' => 'Mua tài khoản YouTube Premium giá rẻ',
            'title' => 'Mua tài khoản YouTube Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản YouTube Premium và các gói YouTube đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['youtube', 'youtube premium'],
        ],
        'google-one' => [
            'label' => 'Google One',
            'heading' => 'Mua tài khoản Google One giá rẻ',
            'title' => 'Mua tài khoản Google One giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Google One và các gói dung lượng Google đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['google one', 'google drive', 'google storage'],
        ],
        'onedrive' => [
            'label' => 'OneDrive',
            'heading' => 'Mua tài khoản OneDrive 1TB giá rẻ',
            'title' => 'Mua tài khoản OneDrive 1TB giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản OneDrive 1TB và các gói lưu trữ OneDrive đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['onedrive', 'one drive', 'onedrive 1tb', 'one drive 1tb'],
        ],
        'nordvpn' => [
            'label' => 'NordVPN',
            'heading' => 'Mua tài khoản NordVPN Premium giá rẻ',
            'title' => 'Mua tài khoản NordVPN Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản NordVPN Premium và các gói NordVPN đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['nordvpn', 'nord vpn', 'nordvpn premium'],
        ],
        'hma-vpn' => [
            'label' => 'HMA VPN',
            'heading' => 'Mua tài khoản HMA VPN giá rẻ',
            'title' => 'Mua tài khoản HMA VPN giá rẻ - DungThu.com',
            'description' => 'Danh sách key HMA VPN và các gói HMA VPN đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['hma', 'hma vpn', 'hide my ass', 'hide my ass vpn'],
        ],
        'surfshark' => [
            'label' => 'Surfshark',
            'heading' => 'Mua tài khoản Surfshark VPN Premium giá rẻ',
            'title' => 'Mua tài khoản Surfshark VPN Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Surfshark VPN Premium và các gói Surfshark đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['surfshark', 'surf shark', 'surfshark vpn', 'surfshark premium'],
        ],
        'expressvpn' => [
            'label' => 'ExpressVPN',
            'heading' => 'Mua tài khoản ExpressVPN Premium giá rẻ',
            'title' => 'Mua tài khoản ExpressVPN Premium giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản ExpressVPN Premium và các gói ExpressVPN đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['expressvpn', 'express vpn', 'expressvpn premium'],
        ],
        'grok' => [
            'label' => 'Grok',
            'heading' => 'Mua tài khoản Grok 1 tháng giá rẻ',
            'title' => 'Mua tài khoản Grok, Super Grok giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Grok, Super Grok và các gói Grok đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['grok', 'super grok', 'grok ai'],
        ],
        'github-copilot' => [
            'label' => 'GitHub Copilot',
            'heading' => 'Mua tài khoản GitHub Copilot Pro giá rẻ',
            'title' => 'Mua tài khoản GitHub Copilot Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản GitHub Copilot Pro và các gói GitHub Copilot đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['github copilot', 'github copilot pro', 'git hub copilot'],
        ],
        'antigravity' => [
            'label' => 'Antigravity',
            'heading' => 'Mua tài khoản Antigravity Ultra giá rẻ',
            'title' => 'Mua tài khoản Antigravity Ultra giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Antigravity Ultra và các gói Antigravity đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['antigravity', 'anti gravity', 'antigravity ultra', 'google antigravity'],
        ],
        'warp' => [
            'label' => 'Warp',
            'heading' => 'Mua tài khoản Warp Dev giá rẻ',
            'title' => 'Mua tài khoản Warp Dev giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Warp Dev và các gói Warp đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['warp', 'warp dev', 'warp terminal'],
        ],
        'claude-code' => [
            'label' => 'Claude Code',
            'heading' => 'Mua tài khoản Claude Code Pro giá rẻ',
            'title' => 'Mua tài khoản Claude Code Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Claude Code Pro và các gói Claude Code đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['claude code', 'claude code pro'],
        ],
        'figma' => [
            'label' => 'Figma',
            'heading' => 'Mua tài khoản Figma Pro giá rẻ',
            'title' => 'Mua tài khoản Figma Pro giá rẻ - DungThu.com',
            'description' => 'Danh sách tài khoản Figma Pro và các gói Figma đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['figma', 'figma pro', 'figma edu'],
        ],
        'intellij' => [
            'label' => 'IntelliJ IDEA',
            'heading' => 'Mua tài khoản IntelliJ IDEA Ultimate giá rẻ',
            'title' => 'Mua tài khoản IntelliJ IDEA Ultimate giá rẻ - DungThu.com',
            'description' => 'Danh sách license IntelliJ IDEA Ultimate và các gói JetBrains đang bán tại DungThu.com, giá tốt, giao nhanh, hỗ trợ đầy đủ.',
            'aliases' => ['intellij', 'intellij idea', 'intellij idea ultimate', 'jetbrains', 'jetbrains license'],
        ],
    ];

    private static ?array $cachedKeywords = null;

    public static function seoKeywords(): array
    {
        if (self::$cachedKeywords !== null) {
            return self::$cachedKeywords;
        }

        self::$cachedKeywords = \Illuminate\Support\Facades\Cache::remember('seo_keywords_list', now()->addDays(7), function () {
            $dbKeywords = \App\Models\SeoKeyword::where('is_active', true)
                ->get()
                ->keyBy('slug')
                ->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'heading' => $item->heading,
                        'title' => $item->title,
                        'description' => $item->description,
                        'aliases' => $item->aliases ?? [],
                    ];
                })
                ->toArray();

            if (empty($dbKeywords)) {
                return self::SEO_KEYWORDS;
            }
            return $dbKeywords;
        });

        return self::$cachedKeywords;
    }

    public function index(Request $request)
    {
        $currentCategoryId = $request->category_id ?? 'all';
        $searchTerm = $request->search ?? '';

        if ($request->filled('search') && $currentCategoryId === 'all' && !$request->filled('page')) {
            return redirect()->route('product.keyword', $this->resolveKeywordSlug($searchTerm));
        }
        
        // Lấy danh sách categories active
        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
        $keywordLinks = self::seoKeywords();
        
        // Xử lý products
        $query = Product::query();
        
        // Filter theo category_id nếu có
        if ($currentCategoryId != 'all') {
            $query->where('category_id', $currentCategoryId);
        }
        
        // Search theo tên hoặc mô tả (hỗ trợ tìm kiếm linh hoạt: "chat gpt" → "chatgpt")
        if ($searchTerm) {
            $this->applyLooseSearch($query, $searchTerm);
        }
        
        $items = $query->latest()->paginate(18)->withQueryString();
        
        return view('products.index', compact('items', 'categories', 'currentCategoryId', 'searchTerm', 'keywordLinks'));
    }

    public function keyword(Request $request, string $keyword)
    {
        $keywordSlug = $this->resolveKeywordSlug($keyword);
        if ($keywordSlug !== Str::slug($keyword)) {
            return redirect()->route('product.keyword', $keywordSlug);
        }

        $keywordConfig = self::seoKeywords()[$keywordSlug] ?? null;
        $searchTerm = $keywordConfig['label'] ?? Str::headline(str_replace('-', ' ', $keywordSlug));
        $aliases = $keywordConfig['aliases'] ?? [str_replace('-', ' ', $keywordSlug)];
        $currentCategoryId = 'all';

        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();
        $keywordLinks = self::seoKeywords();

        $query = Product::query();
        $this->applyKeywordSearch($query, $aliases);

        $items = $query->latest()->paginate(18)->withQueryString();

        if ($items->total() === 0) {
            return redirect()->route('seo.router', $keywordSlug);
        }

        $seoTitle = $keywordConfig['title'] ?? "Mua tài khoản {$searchTerm} giá rẻ - DungThu.com";
        $seoDescription = $keywordConfig['description'] ?? "Danh sách sản phẩm {$searchTerm} đang bán tại DungThu.com: tài khoản, gói sử dụng và dịch vụ liên quan, cập nhật theo kho.";
        $canonical = route('product.keyword', $keywordSlug);
        $pageHeading = $keywordConfig['heading'] ?? "Mua tài khoản {$searchTerm} giá rẻ";
        $isKeywordLanding = true;

        return view('products.index', compact(
            'items',
            'categories',
            'currentCategoryId',
            'searchTerm',
            'seoTitle',
            'seoDescription',
            'canonical',
            'pageHeading',
            'isKeywordLanding',
            'keywordLinks'
        ));
    }

    private function applyKeywordSearch($query, array $aliases): void
    {
        $query->where(function($q) use ($aliases) {
            foreach ($aliases as $alias) {
                $q->orWhere('name', 'LIKE', '%' . $alias . '%');

                $noSpace = str_replace(' ', '', $alias);
                if ($noSpace !== $alias) {
                    $q->orWhere('name', 'LIKE', '%' . $noSpace . '%');
                }
            }
        });
    }

    private function resolveKeywordSlug(string $value): string
    {
        $normalized = Str::slug($value);
        $compact = str_replace('-', '', $normalized);
        $matches = [];

        foreach (self::seoKeywords() as $keyword => $config) {
            if ($normalized === $keyword || str_contains($normalized, $keyword)) {
                $matches[] = [$keyword, strlen($keyword)];
            }

            foreach ($config['aliases'] as $alias) {
                $aliasSlug = Str::slug($alias);
                $aliasCompact = str_replace('-', '', $aliasSlug);

                if ($aliasSlug && (str_contains($normalized, $aliasSlug) || str_contains($compact, $aliasCompact))) {
                    $matches[] = [$keyword, max(strlen($aliasSlug), strlen($aliasCompact))];
                }
            }
        }

        if (!empty($matches)) {
            usort($matches, fn ($a, $b) => $b[1] <=> $a[1]);
            return $matches[0][0];
        }

        return $normalized;
    }

    private function applyLooseSearch($query, string $searchTerm): void
    {
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");

            $noSpace = str_replace(' ', '', $searchTerm);
            if ($noSpace !== $searchTerm) {
                $q->orWhere('name', 'LIKE', "%{$noSpace}%")
                  ->orWhere('description', 'LIKE', "%{$noSpace}%");
            }

            $tokens = array_filter(explode(' ', trim($searchTerm)));
            if (count($tokens) > 1) {
                $q->orWhere(function($sub) use ($tokens) {
                    foreach ($tokens as $token) {
                        $sub->where(function($t) use ($token) {
                            $t->where('name', 'LIKE', "%{$token}%")
                              ->orWhere('description', 'LIKE', "%{$token}%");
                        });
                    }
                });
            }
        });
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['comments', 'features'])->first();
        
        if (!$product) {
            $keywordSlug = $this->resolveKeywordSlug($slug);
            $seoKeywords = self::seoKeywords();
            
            if (isset($seoKeywords[$keywordSlug])) {
                return redirect()->route('product.keyword', $keywordSlug)
                    ->with('error', 'Không tìm thấy sản phẩm yêu cầu. Đang chuyển hướng bạn đến mục liên quan.');
            }
            
            $searchTerm = str_replace('-', ' ', $slug);
            return redirect()->route('shop', ['search' => $searchTerm])
                ->with('error', 'Không tìm thấy sản phẩm yêu cầu. Đây là kết quả tìm kiếm liên quan.');
        }
        
        // Lấy sản phẩm liên quan
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        
        // Tính rating trung bình
        $averageRating = $product->comments()->avg('rating') ?? 0;
        $totalReviews = $product->comments()->count();
        
        // Chọn view theo category
        $viewMap = [
            'tech' => 'products.show-tech',
            'ebooks' => 'products.show-ebooks',
            'doc' => 'products.show-doc',
        ];
        
        $view = $viewMap[$product->category] ?? 'products.show';
        
        // Check if user has purchased this product (for ebooks)
        $hasPurchased = false;
        if (auth()->check() && $product->category === 'ebooks') {
            $hasPurchased = $product->isPurchasedBy(auth()->id());
        }
        
        return view($view, compact('product', 'relatedProducts', 'averageRating', 'totalReviews', 'hasPurchased'));
    }

    public function storeComment(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'comment.required' => 'Vui lòng nhập nội dung đánh giá',
            'comment.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự',
            'rating.required' => 'Vui lòng chọn số sao',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
        ]);

        $product->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function download($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tải file');
        }

        // Check if product has file
        if (!$product->hasFile()) {
            abort(404, 'Sản phẩm này không có file để tải');
        }

        // Check if user has purchased this product
        if (!$product->isPurchasedBy(auth()->id())) {
            return back()->with('error', 'Bạn cần mua sản phẩm này trước khi tải về');
        }

        // Get file path
        $filePath = PathHelper::publicRootPath('files/' . $product->file_path);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File không tồn tại');
        }

        // Return file download
        return response()->download($filePath, $product->name . '.' . $product->file_type);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    private const BLOG_TOPICS = [
        'ai' => [
            'label' => 'AI',
            'heading' => 'Blog AI và công cụ trí tuệ nhân tạo',
            'title' => 'Blog AI, công cụ trí tuệ nhân tạo - DungThu.com',
            'description' => 'Tổng hợp bài viết về AI, công cụ trí tuệ nhân tạo, mẹo sử dụng và cập nhật xu hướng AI mới tại DungThu.com.',
            'aliases' => ['ai', 'trí tuệ nhân tạo', 'artificial intelligence'],
        ],
        'chatgpt' => [
            'label' => 'ChatGPT',
            'heading' => 'Hướng dẫn ChatGPT và GPT Plus',
            'title' => 'Hướng dẫn ChatGPT, GPT Plus - DungThu.com',
            'description' => 'Tổng hợp bài viết, mẹo dùng ChatGPT, GPT Plus và các công cụ GPT hiệu quả.',
            'aliases' => ['chatgpt', 'chat gpt', 'gpt', 'gpt plus', 'openai'],
        ],
        'cursor' => [
            'label' => 'Cursor',
            'heading' => 'Hướng dẫn Cursor AI cho lập trình',
            'title' => 'Hướng dẫn Cursor AI, Cursor Pro - DungThu.com',
            'description' => 'Tổng hợp bài viết về Cursor AI, Cursor Pro, mẹo code với AI và tối ưu workflow lập trình.',
            'aliases' => ['cursor', 'cursor ai', 'cursor pro'],
        ],
        'gemini' => [
            'label' => 'Gemini',
            'heading' => 'Hướng dẫn Gemini Advanced',
            'title' => 'Hướng dẫn Gemini Advanced - DungThu.com',
            'description' => 'Tổng hợp bài viết về Gemini, Gemini Advanced và cách dùng công cụ AI của Google hiệu quả.',
            'aliases' => ['gemini', 'gemini advanced', 'google gemini'],
        ],
        'claude' => [
            'label' => 'Claude',
            'heading' => 'Hướng dẫn Claude AI và Claude Code',
            'title' => 'Hướng dẫn Claude AI, Claude Code - DungThu.com',
            'description' => 'Tổng hợp bài viết về Claude AI, Claude Code và cách ứng dụng Claude trong công việc.',
            'aliases' => ['claude', 'claude ai', 'claude code'],
        ],
        'vpn' => [
            'label' => 'VPN',
            'heading' => 'Hướng dẫn VPN và bảo mật tài khoản',
            'title' => 'Hướng dẫn VPN, bảo mật tài khoản - DungThu.com',
            'description' => 'Tổng hợp bài viết về VPN, bảo mật tài khoản, truy cập an toàn và quyền riêng tư khi dùng internet.',
            'aliases' => ['vpn', 'nordvpn', 'surfshark', 'expressvpn', 'hma'],
        ],
        'office' => [
            'label' => 'Office 365',
            'heading' => 'Mẹo dùng Office 365 và Microsoft 365',
            'title' => 'Mẹo dùng Office 365, Microsoft 365 - DungThu.com',
            'description' => 'Tổng hợp bài viết về Office 365, Microsoft 365, OneDrive và các công cụ văn phòng.',
            'aliases' => ['office', 'office 365', 'microsoft 365', 'onedrive'],
        ],
        'canva' => [
            'label' => 'Canva',
            'heading' => 'Hướng dẫn Canva Pro và thiết kế nhanh',
            'title' => 'Hướng dẫn Canva Pro, thiết kế nhanh - DungThu.com',
            'description' => 'Tổng hợp bài viết về Canva Pro, mẹo thiết kế nhanh và tối ưu nội dung hình ảnh.',
            'aliases' => ['canva', 'canva pro', 'thiết kế'],
        ],
        'youtube' => [
            'label' => 'YouTube',
            'heading' => 'Mẹo dùng YouTube Premium',
            'title' => 'Mẹo dùng YouTube Premium - DungThu.com',
            'description' => 'Tổng hợp bài viết về YouTube Premium, giải trí số và tối ưu trải nghiệm xem video.',
            'aliases' => ['youtube', 'youtube premium'],
        ],
        'hoc-tap' => [
            'label' => 'Học tập',
            'heading' => 'Công cụ học tập và làm việc hiệu quả',
            'title' => 'Công cụ học tập và làm việc hiệu quả - DungThu.com',
            'description' => 'Tổng hợp bài viết về công cụ học tập, làm việc, viết nội dung và tối ưu năng suất.',
            'aliases' => ['học tập', 'hoc tap', 'notion', 'grammarly', 'quillbot', 'turnitin'],
        ],
    ];

    private static ?array $cachedTopics = null;

    public static function blogTopics(): array
    {
        if (self::$cachedTopics !== null) {
            return self::$cachedTopics;
        }

        self::$cachedTopics = \Illuminate\Support\Facades\Cache::remember('blog_topics_list', now()->addDays(7), function () {
            $dbTopics = \App\Models\BlogTopic::where('is_active', true)
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

            if (empty($dbTopics)) {
                return self::BLOG_TOPICS;
            }
            return $dbTopics;
        });

        return self::$cachedTopics;
    }


    public function index()
    {
        $blogs = Blog::published()->orderBy('published_at', 'desc')->paginate(9);
        $topicLinks = self::blogTopics();

        return view('blogs.index', compact('blogs', 'topicLinks'));
    }

    public function topic(string $topic)
    {
        $topicSlug = $this->resolveTopicSlug($topic);
        if ($topicSlug !== Str::slug($topic)) {
            return redirect()->route('blog.topic', $topicSlug);
        }

        $topicConfig = self::blogTopics()[$topicSlug] ?? null;
        $aliases = $topicConfig['aliases'] ?? [str_replace('-', ' ', $topicSlug)];

        $blogs = Blog::published()
            ->where(function ($query) use ($aliases) {
                foreach ($aliases as $alias) {
                    $query->orWhere('title', 'LIKE', '%' . $alias . '%')
                        ->orWhere('excerpt', 'LIKE', '%' . $alias . '%')
                        ->orWhere('content', 'LIKE', '%' . $alias . '%');
                }
            })
            ->orderBy('published_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        $topicLinks = self::blogTopics();
        $seoTitle = $topicConfig['title'] ?? 'Blog ' . Str::headline(str_replace('-', ' ', $topicSlug)) . ' - DungThu.com';
        $seoDescription = $topicConfig['description'] ?? 'Tổng hợp bài viết mới nhất tại DungThu.com.';
        $canonical = route('blog.topic', $topicSlug);
        $pageHeading = $topicConfig['heading'] ?? 'Blog ' . Str::headline(str_replace('-', ' ', $topicSlug));
        $isTopicLanding = true;

        return view('blogs.index', compact(
            'blogs',
            'topicLinks',
            'seoTitle',
            'seoDescription',
            'canonical',
            'pageHeading',
            'isTopicLanding'
        ));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->published()->first();
        
        if (!$blog) {
            $topicSlug = $this->resolveTopicSlug($slug);
            $blogTopics = self::blogTopics();
            
            if (isset($blogTopics[$topicSlug])) {
                return redirect()->route('blog.topic', $topicSlug)
                    ->with('error', 'Không tìm thấy bài viết yêu cầu. Đang chuyển hướng bạn đến mục liên quan.');
            }
            
            return redirect()->route('blog.index')
                ->with('error', 'Không tìm thấy bài viết yêu cầu. Đây là danh sách tất cả bài viết.');
        }
        $blog->incrementViews();

        $relatedBlogs = Blog::published()
            ->where('category', $blog->category)
            ->where('id', '!=', $blog->id)
            ->take(3)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    public function category($category)
    {
        $blogs = Blog::published()
            ->byCategory($category)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $topicLinks = self::blogTopics();
        $seoTitle = 'Blog ' . Str::headline($category) . ' - DungThu.com';
        $seoDescription = 'Tổng hợp bài viết thuộc chuyên mục ' . Str::headline($category) . ' tại DungThu.com.';
        $canonical = route('blog.category', $category);
        $pageHeading = 'Blog ' . Str::headline($category);

        return view('blogs.index', compact('blogs', 'category', 'topicLinks', 'seoTitle', 'seoDescription', 'canonical', 'pageHeading'));
    }

    private function resolveTopicSlug(string $value): string
    {
        $normalized = Str::slug($value);
        $compact = str_replace('-', '', $normalized);
        $matches = [];

        foreach (self::blogTopics() as $topic => $config) {
            if ($normalized === $topic || str_contains($normalized, $topic)) {
                $matches[] = [$topic, strlen($topic)];
            }

            foreach ($config['aliases'] as $alias) {
                $aliasSlug = Str::slug($alias);
                $aliasCompact = str_replace('-', '', $aliasSlug);

                if ($aliasSlug && (str_contains($normalized, $aliasSlug) || str_contains($compact, $aliasCompact))) {
                    $matches[] = [$topic, max(strlen($aliasSlug), strlen($aliasCompact))];
                }
            }
        }

        if (!empty($matches)) {
            usort($matches, fn ($a, $b) => $b[1] <=> $a[1]);
            return $matches[0][0];
        }

        return $normalized;
    }
}

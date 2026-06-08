<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Http\Controllers\BlogController;
use App\Models\BlogTopic;

class BlogTopicSeeder extends Seeder
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

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::BLOG_TOPICS as $slug => $config) {
            BlogTopic::updateOrCreate(
                ['slug' => $slug],
                [
                    'label' => $config['label'],
                    'heading' => $config['heading'],
                    'title' => $config['title'],
                    'description' => $config['description'],
                    'aliases' => $config['aliases'],
                    'is_active' => true,
                ]
            );
        }
    }
}

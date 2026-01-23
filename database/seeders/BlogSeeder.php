<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $blogs = [
            [
                'title' => 'Top 5 Source Code Website Bán Hàng Tốt Nhất 2024',
                'excerpt' => 'Tổng hợp các mã nguồn mở PHP, Laravel, Nodejs giúp bạn xây dựng web nhanh chóng...',
                'content' => '<p>Trong thời đại số hóa, việc xây dựng một website bán hàng chuyên nghiệp không còn quá khó khăn nhờ vào các source code mã nguồn mở. Bài viết này sẽ giới thiệu đến bạn top 5 source code website bán hàng tốt nhất năm 2024.</p><h2>1. Laravel E-commerce</h2><p>Laravel là framework PHP phổ biến nhất hiện nay với cú pháp rõ ràng và dễ học...</p>',
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=500',
                'category' => 'tech',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Phối Đồ Streetwear: Bí Quyết Để Trông "Ngầu" Hơn',
                'excerpt' => 'Hướng dẫn cách mix áo Hoodie với quần Baggy và giày Sneaker đúng chuẩn Gen Z...',
                'content' => '<p>Streetwear không chỉ là phong cách thời trang mà còn là cách thể hiện cá tính của giới trẻ. Để phối đồ streetwear đẹp và "ngầu", bạn cần nắm được những bí quyết sau.</p><h2>1. Chọn áo Hoodie phù hợp</h2><p>Áo hoodie oversized là lựa chọn hoàn hảo cho phong cách streetwear...</p>',
                'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=500',
                'category' => 'fashion',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Cách Sử Dụng Tool Check Live UID Facebook Miễn Phí',
                'excerpt' => 'Hướng dẫn chi tiết cách lọc data khách hàng tiềm năng bằng bộ công cụ miễn phí tại DungThu.com...',
                'content' => '<p>Tool check live UID Facebook là công cụ hữu ích giúp các marketer và người làm kinh doanh online xác định được những tài khoản Facebook còn hoạt động.</p><h2>Tại sao cần check live UID?</h2><p>Việc lọc được UID live giúp bạn tối ưu chi phí quảng cáo và tăng tỷ lệ chuyển đổi...</p>',
                'image' => 'https://images.unsplash.com/photo-1555421689-49263376da7a?w=500',
                'category' => 'tools',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Hướng Dẫn Tối Ưu SEO Website Laravel Năm 2024',
                'excerpt' => 'Những kỹ thuật SEO on-page và off-page cho website Laravel để tăng thứ hạng Google...',
                'content' => '<p>Laravel là framework tuyệt vời để xây dựng website, nhưng để website của bạn lên top Google thì cần áp dụng các kỹ thuật SEO phù hợp.</p><h2>1. Tối ưu URL thân thiện</h2><p>Sử dụng Route trong Laravel để tạo URL dễ đọc và có chứa từ khóa...</p>',
                'image' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8f2c293?w=500',
                'category' => 'tech',
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Xu Hướng Thời Trang Nam 2024: Minimalism Is The Key',
                'excerpt' => 'Phong cách tối giản đang trở thành xu hướng thống trị làng thời trang nam toàn cầu...',
                'content' => '<p>Năm 2024 đánh dấu sự trở lại mạnh mẽ của phong cách minimalism trong thời trang nam. Ít nhưng chất, đơn giản nhưng tinh tế.</p><h2>Màu sắc trung tính lên ngôi</h2><p>Các tông màu như đen, trắng, be, xám đang được ưa chuộng hơn bao giờ hết...</p>',
                'image' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=500',
                'category' => 'fashion',
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'slug' => Str::slug($blog['title']),
                'excerpt' => $blog['excerpt'],
                'content' => $blog['content'],
                'image' => $blog['image'],
                'category' => $blog['category'],
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => $blog['published_at'],
                'views' => rand(100, 1000),
            ]);
        }
    }
}

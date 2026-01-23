<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'price' => 34990000,
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500',
                'category' => 'tech',
                'description' => 'Titanium design, A17 Pro chip, 48MP Main camera, USB-C.',
            ],
            [
                'name' => 'MacBook Pro M3',
                'price' => 45990000,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca4?w=500',
                'category' => 'tech',
                'description' => 'Mind-blowing performance with M3 chip. Up to 22 hours of battery life.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'price' => 8490000,
                'image' => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?w=500',
                'category' => 'tech',
                'description' => 'Industry-leading noise canceling headphones with premium sound.',
            ],
            [
                'name' => 'Áo Hoodie Streetwear',
                'price' => 450000,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
                'category' => 'fashion',
                'description' => 'Áo hoodie form rộng, chất liệu nỉ bông cao cấp, phong cách trẻ trung.',
            ],
            [
                'name' => 'Giày Sneaker Chunky',
                'price' => 1200000,
                'image' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=500',
                'category' => 'fashion',
                'description' => 'Thiết kế hầm hố, đế cao hack dáng, êm ái khi di chuyển.',
            ],
            [
                'name' => 'Ebook Laravel Pro',
                'price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500',
                'category' => 'doc',
                'description' => 'Sách hướng dẫn lập trình Laravel từ cơ bản đến nâng cao.',
            ],
            [
                'name' => 'Source Code Web Bán Hàng',
                'price' => 500000,
                'image' => 'https://images.unsplash.com/photo-1555099962-4199c345e5dd?w=500',
                'category' => 'doc',
                'description' => 'Full source code website bán hàng Laravel chuẩn SEO, giao diện đẹp.',
            ],
            [
                'name' => 'Bàn Phím Cơ Keychron K2',
                'price' => 1890000,
                'image' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=500',
                'category' => 'tech',
                'description' => 'Bàn phím cơ không dây, layout 75%, switch Gateron mượt mà.',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'price' => $product['price'],
                'image' => $product['image'],
                'category' => $product['category'],
                'description' => $product['description'],
                'stock' => 100,
            ]);
        }
    }
}

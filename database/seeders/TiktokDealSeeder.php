<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiktokDeal;

class TiktokDealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = [
            [
                'name' => 'Áo Thun Nam Form Rộng Unisex',
                'description' => 'Áo thun nam nữ form rộng phong cách Hàn Quốc, chất liệu cotton mềm mịn',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 150000,
                'sale_price' => 89000,
                'discount_percent' => 41,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Quần Jean Nam Baggy Ống Suông',
                'description' => 'Quần jean baggy nam hot trend 2026, vải denim cao cấp',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 299000,
                'sale_price' => 179000,
                'discount_percent' => 40,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Giày Thể Thao Nam Nữ Sneaker',
                'description' => 'Giày sneaker thời trang, đế êm, phong cách trẻ trung năng động',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 399000,
                'sale_price' => 249000,
                'discount_percent' => 38,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Balo Laptop Thời Trang Công Sở',
                'description' => 'Balo laptop chống sốc, nhiều ngăn tiện dụng, phù hợp đi học đi làm',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 350000,
                'sale_price' => 199000,
                'discount_percent' => 43,
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Túi Đeo Chéo Nữ Mini Hàn Quốc',
                'description' => 'Túi đeo chéo mini xinh xắn, phối màu trendy, dễ phối đồ',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 189000,
                'sale_price' => 99000,
                'discount_percent' => 48,
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Đồng Hồ Nam Nữ Thời Trang',
                'description' => 'Đồng hồ thời trang phong cách tối giản, dây da cao cấp',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 450000,
                'sale_price' => 199000,
                'discount_percent' => 56,
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Set Đồ Nữ Áo Croptop + Quần Shorts',
                'description' => 'Set đồ nữ mùa hè siêu xinh, chất liệu thông thoáng mát mẻ',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 259000,
                'sale_price' => 139000,
                'discount_percent' => 46,
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'Mũ Bucket Hat Vải Dù Unisex',
                'description' => 'Mũ bucket hat hot trend, vải dù chống nắng cực tốt',
                'tiktok_link' => 'https://vt.tiktok.com/ZSjrFMq3K/',
                'original_price' => 89000,
                'sale_price' => 49000,
                'discount_percent' => 45,
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($deals as $deal) {
            TiktokDeal::create($deal);
        }
    }
}

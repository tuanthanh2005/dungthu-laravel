<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Http\Controllers\ProductController;
use App\Models\SeoKeyword;

class SeoKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywords = ProductController::seoKeywords();

        foreach ($keywords as $slug => $config) {
            SeoKeyword::updateOrCreate(
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

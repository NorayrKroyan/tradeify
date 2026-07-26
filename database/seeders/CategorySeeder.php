<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Agriculture and Food', 'Beverages', 'Chemicals', 'Construction Materials',
            'Consumer Goods', 'Electronics', 'Energy', 'Industrial Equipment',
            'Medical and Healthcare', 'Metals and Minerals', 'Packaging',
            'Textiles and Apparel', 'Transport and Automotive', 'Other',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'is_active' => true]);
        }
    }
}

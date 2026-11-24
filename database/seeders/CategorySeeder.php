<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Dog',
                'description' => 'Wash & Blow-Dry, Ears Cleaning, Nails Trim and Drill, Paw Treatment',
                'pricing_type' => 'sizeable',
                'image' => 'categories/dog.webp',
                'icon' => null,
                'is_active' => true,
                'prices' => [
                    1 => 90.00, // Small
                    2 => 110.00, // Medium
                    3 => 130.00, // Large
                    4 => 160.00, // XLarge
                ]
            ],
            [
                'name' => 'Cat',
                'description' => 'Wash & Blow-Dry and Nails',
                'pricing_type' => 'fixable',
                'image' => 'categories/cat.webp',
                'icon' => null,
                'is_active' => true,
                'price' => 110.00
            ],
        ];

        foreach ($categories as $catData) {
            $prices = $catData['prices'] ?? null;
            $singlePrice = $catData['price'] ?? null;
            unset($catData['prices'], $catData['price']);

            $category = \App\Models\Category::create($catData);

            if ($category->pricing_type === 'fixable') {
                \App\Models\CategoryStandard::create([
                    'category_id' => $category->id,
                    'size_id' => null,
                    'title' => 'Fox Grooming Standard',
                    'price' => $singlePrice,
                ]);
            } else {
                foreach ($prices as $sizeId => $price) {
                    \App\Models\CategoryStandard::create([
                        'category_id' => $category->id,
                        'size_id' => $sizeId,
                        'title' => 'Fox Grooming Standard',
                        'price' => $price,
                    ]);
                }
            }
        }
    }
}

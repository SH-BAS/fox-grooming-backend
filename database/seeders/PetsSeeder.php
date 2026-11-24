<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Size;

class PetsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have users and categories to link to
        if (User::count() == 0 || Category::count() == 0) {
            $this->command->info('No users or categories found. Skipping PetsSeeder.');
            return;
        }

        // Create 10 pets
        for ($i = 0; $i < 10; $i++) {
            $user = User::inRandomOrder()->first();
            $category = Category::inRandomOrder()->first();
            
            // Optional relations
            $categoryType = CategoryType::where('category_id', $category->id)->inRandomOrder()->first();
            $size = Size::inRandomOrder()->first();

            Pet::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'category_type_id' => $categoryType ? $categoryType->id : null,
                'size_id' => $size ? $size->id : null,
                'name' => 'Pet ' . fake()->name(),
                'age' => fake()->numberBetween(1, 15),
                'weight' => fake()->randomFloat(2, 2, 40),
                'gender' => fake()->randomElement(['male', 'female']),
                'is_vaccinated' => fake()->boolean(),
                'notes' => fake()->sentence(),
            ]);
        }
    }
}

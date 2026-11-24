<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles must exist first
        $this->call([
            RoleSeeder::class,
        ]);

        // 2. Ensure users exist (they need role_id=5)
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        // 3. Run remaining seeders
        $this->call([
            AdminSeeder::class,
            SizeSeeder::class,
            CategorySeeder::class,
            PetsSeeder::class,
            ServicesSeeder::class,
            BookingsSeeder::class,
            DogBreedSeeder::class,
            CatBreedSeeder::class,
        ]);
    }
}

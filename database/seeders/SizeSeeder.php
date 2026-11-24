<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //  protected $fillable = ['name', 'weight_from', 'weight_to'];
    public function run(): void
    {
        $sizes = [
            ['name' => 'Small', 'weight_from' => 0, 'weight_to' => 20],
            ['name' => 'Medium', 'weight_from' => 20, 'weight_to' => 50],
            ['name' => 'Large', 'weight_from' => 50, 'weight_to' => 100],
            ['name' => 'XLarge', 'weight_from' => 100, 'weight_to' => 160],
        ];

        foreach ($sizes as $size) {
            Size::create($size);
        }
    }
}

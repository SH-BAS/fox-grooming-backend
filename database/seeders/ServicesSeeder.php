<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceSize;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dogCategory = Category::where('name', 'Dog')->first();
        $catCategory = Category::where('name', 'Cat')->first();

        /*
        |--------------------------------------------------------------------------
        | DOG SERVICES
        |--------------------------------------------------------------------------
        */

        $dogServices = [

            // Sizeable Services
            [
                'name' => 'Puppy Cut',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 35,
                    2 => 50,
                    3 => 65,
                    4 => 80,
                ]
            ],
            [
                'name' => 'Deshedding',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 35,
                    2 => 45,
                    3 => 60,
                    4 => 70,
                ]
            ],
            [
                'name' => 'Demmating',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 40,
                    2 => 50,
                    3 => 60,
                    4 => 70,
                ]
            ],
            [
                'name' => 'Flea Treatment',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 35,
                    2 => 45,
                    3 => 60,
                    4 => 90,
                ]
            ],
            [
                'name' => 'Hand Stripping',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 35,
                    2 => 40,
                    3 => 60,
                    4 => 100,
                ]
            ],
            [
                'name' => 'Shave Down',
                'pricing_type' => 'sizeable',
                'minutes' => 30,
                'prices' => [
                    1 => 40,
                    2 => 55,
                    3 => 70,
                    4 => 85,
                ]
            ],

            // Fixable Services
            [
                'name' => 'Nail Trim',
                'pricing_type' => 'fixable',
                'minutes' => 30,
                'price' => 40
            ],
            [
                'name' => 'Ears Cleaning',
                'pricing_type' => 'fixable',
                'minutes' => 30,
                'price' => 40
            ],
            [
                'name' => 'Partial Trim Work',
                'pricing_type' => 'fixable',
                'minutes' => 30,
                'price' => 40
            ],
            [
                'name' => 'Sanitary Trim',
                'pricing_type' => 'fixable',
                'minutes' => 30,
                'price' => 30
            ],
        ];

        foreach ($dogServices as $serviceData) {

            $prices = $serviceData['prices'] ?? null;
            $singlePrice = $serviceData['price'] ?? null;

            unset($serviceData['prices'], $serviceData['price']);

            $service = Service::create([
                'category_id' => $dogCategory->id,
                'name' => $serviceData['name'],
                'description' => null,
                'minutes' => $serviceData['minutes'],
                'image' => null,
                'pricing_type' => $serviceData['pricing_type'],
                'is_active' => true,
            ]);

            if ($service->pricing_type === 'fixable') {

                ServiceSize::create([
                    'service_id' => $service->id,
                    'size_id' => null,
                    'price' => $singlePrice,
                ]);

            } else {

                foreach ($prices as $sizeId => $price) {
                    ServiceSize::create([
                        'service_id' => $service->id,
                        'size_id' => $sizeId,
                        'price' => $price,
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CAT SERVICES
        |--------------------------------------------------------------------------
        */

        $catServices = [
            [
                'name' => 'Cats Deshedding',
                'price' => 35
            ],
            [
                'name' => "Cats Lion Cut",
                'price' => 70
            ],
        ];

        foreach ($catServices as $serviceData) {

            $service = Service::create([
                'category_id' => $catCategory->id,
                'name' => $serviceData['name'],
                'description' => null,
                'image' => null,
                'pricing_type' => 'fixable',
                'is_active' => true,
            ]);

            ServiceSize::create([
                'service_id' => $service->id,
                'size_id' => null,
                'price' => $serviceData['price'],
            ]);
        }
    }
}

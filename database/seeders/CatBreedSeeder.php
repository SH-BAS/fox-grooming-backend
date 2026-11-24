<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use App\Models\Category;

class CatBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catCategory = Category::where('name', 'Cat')->first();

        if (!$catCategory) {
            return;
        }

        $breeds = [

            'Abyssinian',
            'American Bobtail',
            'American Curl',
            'American Shorthair',
            'American Wirehair',
            'Balinese',
            'Bengal',
            'Birman',
            'Bombay',
            'British Shorthair',
            'British Longhair',
            'Burmese',
            'Burmilla',
            'Chartreux',
            'Chausie',
            'Colorpoint Shorthair',
            'Cornish Rex',
            'Cymric',
            'Devon Rex',
            'Donskoy',
            'Egyptian Mau',
            'European Burmese',
            'Exotic Shorthair',
            'Havana Brown',
            'Highlander',
            'Himalayan',
            'Japanese Bobtail',
            'Javanese',
            'Khao Manee',
            'Korat',
            'Kurilian Bobtail',
            'LaPerm',
            'Lykoi',
            'Maine Coon',
            'Manx',
            'Munchkin',
            'Nebelung',
            'Norwegian Forest Cat',
            'Ocicat',
            'Oriental Longhair',
            'Oriental Shorthair',
            'Persian',
            'Peterbald',
            'Pixie-Bob',
            'Ragamuffin',
            'Ragdoll',
            'Russian Blue',
            'Savannah',
            'Scottish Fold',
            'Selkirk Rex',
            'Serengeti',
            'Siamese',
            'Siberian',
            'Singapura',
            'Snowshoe',
            'Somali',
            'Sphynx',
            'Thai',
            'Tonkinese',
            'Toyger',
            'Turkish Angora',
            'Turkish Van',
            'York Chocolate',
            'Arabian Mau',
            'Australian Mist',
            'Brazilian Shorthair',
            'British Semi-Longhair',
            'California Spangled',
            'Dragon Li',
            'German Rex',
            'Ojos Azules',
            'Ukrainian Levkoy',
        ];

        foreach ($breeds as $breedName) {

            CategoryType::create([
                'category_id' => $catCategory->id,
                'name' => $breedName,
                'image' => null,
                'description' => null,
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryType;
use App\Models\Category;

class DogBreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dogCategory = Category::where('name', 'Dog')->first();

        if (!$dogCategory) {
            return;
        }

        $breeds = [

            // Sporting
            'Labrador Retriever',
            'Golden Retriever',
            'Cocker Spaniel',
            'English Springer Spaniel',
            'Pointer',
            'Irish Setter',
            'Weimaraner',
            'Vizsla',

            // Working
            'Rottweiler',
            'Doberman Pinscher',
            'Boxer',
            'Siberian Husky',
            'Great Dane',
            'Bernese Mountain Dog',
            'Saint Bernard',
            'Akita',

            // Herding
            'German Shepherd',
            'Border Collie',
            'Australian Shepherd',
            'Shetland Sheepdog',
            'Belgian Malinois',
            'Old English Sheepdog',
            'Corgi',
            'Collie',

            // Hound
            'Beagle',
            'Dachshund',
            'Greyhound',
            'Basset Hound',
            'Bloodhound',
            'Whippet',
            'Rhodesian Ridgeback',

            // Toy
            'Pomeranian',
            'Chihuahua',
            'Shih Tzu',
            'Yorkshire Terrier',
            'Maltese',
            'Pug',
            'Papillon',

            // Non-Sporting
            'Bulldog',
            'Dalmatian',
            'Chow Chow',
            'Boston Terrier',
            'French Bulldog',
            'Lhasa Apso',
            'Shar Pei',

            // Terriers
            'Pit Bull Terrier',
            'Bull Terrier',
            'Airedale Terrier',
            'Staffordshire Bull Terrier',
            'West Highland White Terrier',
            'Scottish Terrier',
            'Jack Russell Terrier',

            // Extra Popular
            'Alaskan Malamute',
            'Cane Corso',
            'Newfoundland',
            'Shiba Inu',
            'Samoyed',
            'Havanese',
            'Bichon Frise',
            'Australian Cattle Dog',
            'Miniature Schnauzer',
            'Standard Schnauzer',
            'Great Pyrenees',
            'English Bulldog',
            'American Bully',
            'Cavalier King Charles Spaniel',
            'Chinese Crested',
            'Italian Greyhound',
            'Basenji',
            'Afghan Hound',
            'Bullmastiff',
            'Tibetan Mastiff',
            'American Eskimo Dog',
            'Cockapoo',
            'Goldendoodle',
            'Labradoodle',
            'Pomsky',
            'Miniature Pinscher',
            'Keeshond',
            'Border Terrier',
            'Norwegian Elkhound',
            'Irish Wolfhound',
            'Anatolian Shepherd',
            'Belgian Tervuren',
            'Borzoi',
            'Clumber Spaniel',
            'English Foxhound',
            'Finnish Spitz',
            'Giant Schnauzer',
            'Leonberger',
            'Neapolitan Mastiff',
            'Saluki',
            'Toy Poodle',
            'Miniature Poodle',
            'Standard Poodle',
        ];

        foreach ($breeds as $breedName) {

            CategoryType::create([
                'category_id' => $dogCategory->id,
                'name' => $breedName,
                'image' => null,
                'description' => null,
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookedService;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceSize;
use App\Models\CategoryStandard;
use Carbon\Carbon;

class BookingsSeeder extends Seeder
{
    public function run(): void
    {
        if (Pet::count() == 0) {
            $this->command->info('No pets found. Skipping BookingsSeeder.');
            return;
        }

        // Create 3 bookings
        for ($i = 0; $i < 3; $i++) {
            $pet = Pet::inRandomOrder()->first();
            $user = $pet->user;

            $bookingDate = Carbon::now()->addDays(rand(1, 30));
            $bookingTime = Carbon::createFromTime(rand(9, 17), 0, 0);

            $booking = Booking::create([
                'user_id' => $user->id,
                'pet_id' => $pet->id,
                'staff_id' => null,
                'booking_date' => $bookingDate->format('Y-m-d'),
                'booking_time' => $bookingTime->format('H:i:s'),
                'status' => 'pending',
                'base_price' => 0,
                'tax' => 0,
                'total_price' => 0,
            ]);

            // Add 1-3 services
            $numServices = rand(1, 3);
            $totalBasePrice = 0;

            for ($j = 0; $j < $numServices; $j++) {
                // Try to get a price from ServiceSize (preferred) or CategoryStandard
                $serviceSize = ServiceSize::inRandomOrder()->first();
                $categoryStandard = CategoryStandard::inRandomOrder()->first();
                $service = Service::inRandomOrder()->first();

                $price = 0;
                $sizeId = null;

                if ($serviceSize) {
                    $price = $serviceSize->price;
                    $sizeId = $serviceSize->size_id;
                } elseif ($categoryStandard) {
                    $price = $categoryStandard->price;
                    $sizeId = $categoryStandard->size_id;
                } else {
                    // Fallback if no price data exists
                    $price = rand(30, 100);
                }

                BookedService::create([
                    'booking_id' => $booking->id,
                    'size_id' => $sizeId,
                    'price' => $price,
                    'service_id' => $service->id,
                ]);

                $totalBasePrice += $price;
            }

            // Calculate totals
            $tax = $totalBasePrice * 0.10; // Assuming 10% tax
            $totalPrice = $totalBasePrice + $tax;

            $booking->update([
                'base_price' => $totalBasePrice,
                'tax' => $tax,
                'total_price' => $totalPrice,
            ]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('pet_id')->constrained('pets');
            $table->foreignId('staff_id')->nullable()->constrained('staff');
            $table->date('booking_date')->nullable();
            $table->time('booking_time')->nullable();
            $table->enum('status', [
                'pending',
                'assigned',
                'ontheway',
                'arrived',
                'cancelled',
                'started',
                'completed'
            ])->default('pending');
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

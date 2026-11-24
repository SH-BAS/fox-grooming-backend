<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booked_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained('sizes');
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booked_services');
    }
};

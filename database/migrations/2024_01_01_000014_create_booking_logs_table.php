<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('ontheway_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->string('updated_by_type')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};

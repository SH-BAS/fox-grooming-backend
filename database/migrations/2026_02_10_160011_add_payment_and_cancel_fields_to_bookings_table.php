<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('received_amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('received_type')->nullable(); // admin, staff
            $table->text('cancel_note')->nullable();
            $table->string('cancel_type')->nullable(); // admin, staff
            $table->unsignedBigInteger('cancel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'received_amount',
                'payment_method',
                'received_type',
                'cancel_note',
                'cancel_type',
                'cancel_id'
            ]);
        });
    }
};

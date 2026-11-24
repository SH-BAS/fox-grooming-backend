<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'booking_time')) {
                $table->renameColumn('booking_time', 'start_time');
            }
            if (!Schema::hasColumn('bookings', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'start_time')) {
                $table->renameColumn('start_time', 'booking_time');
            }
            if (Schema::hasColumn('bookings', 'end_time')) {
                $table->dropColumn('end_time');
            }
        });
    }
};

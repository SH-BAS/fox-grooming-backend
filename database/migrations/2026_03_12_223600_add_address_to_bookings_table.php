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
            $table->string('address')->nullable()->after('is_reviewed');
            $table->string('zip_code')->nullable()->after('address');
            $table->float('latitude')->nullable()->after('zip_code');
            $table->float('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('zip_code');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};

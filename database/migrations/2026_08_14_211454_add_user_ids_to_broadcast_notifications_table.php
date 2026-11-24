<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('broadcast_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('broadcast_notifications', 'user_ids')) {
                $table->json('user_ids')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('broadcast_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('broadcast_notifications', 'user_ids')) {
                $table->dropColumn('user_ids');
            }
        });
    }
};

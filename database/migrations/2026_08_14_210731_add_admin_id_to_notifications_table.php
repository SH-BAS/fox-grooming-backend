<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK so we can make user_id nullable
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Throwable $e) {
            // FK name may differ; continue with raw alter
        }

        DB::statement('ALTER TABLE notifications MODIFY user_id BIGINT UNSIGNED NULL');

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            if (!Schema::hasColumn('notifications', 'admin_id')) {
                $table->foreignId('admin_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('admins')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'admin_id')) {
                $table->dropConstrainedForeignId('admin_id');
            }
        });
    }
};

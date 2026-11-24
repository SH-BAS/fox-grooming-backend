<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broadcast_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('type')->default('broadcast');
            $table->enum('target_type', ['all', 'specific'])->default('all');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('audience_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'broadcast_id')) {
                $table->foreignId('broadcast_id')
                    ->nullable()
                    ->after('booking_id')
                    ->constrained('broadcast_notifications')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'broadcast_id')) {
                $table->dropConstrainedForeignId('broadcast_id');
            }
        });

        Schema::dropIfExists('broadcast_notifications');
    }
};

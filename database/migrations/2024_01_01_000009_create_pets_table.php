<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('category_type_id')->nullable()->constrained('category_types');
            $table->foreignId('size_id')->nullable()->constrained('sizes');
            $table->string('name', 150)->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 6, 2)->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->boolean('is_vaccinated')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

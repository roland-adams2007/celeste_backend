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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['standard', 'signature'])->default('standard');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->string('category');
            $table->unsignedSmallInteger('size');
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->string('bed_type');
            $table->string('view_type');
            $table->unsignedInteger('rate');
            $table->unsignedInteger('rate_weekend');
            $table->json('images')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};

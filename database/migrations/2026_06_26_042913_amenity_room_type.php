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
        Schema::create('amenity_room_type', function (Blueprint $table) {
            $table->id();

            // Link to the room types table
            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->onDelete('cascade');

            // Link to the amenities table
            $table->foreignId('amenity_id')
                ->constrained('amenities')
                ->onDelete('cascade');

            // Prevents assigning the same amenity to the same room type twice
            $table->unique(['room_type_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_room_type');
    }
};

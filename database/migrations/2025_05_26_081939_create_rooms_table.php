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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id'); // as per ERD 'room_id'
            $table->unsignedBigInteger('hotel_id'); // FK to hotels (from 'contains' relation)
            $table->integer('max_occupancy'); // from ERD 'max_occupancy'
            $table->decimal('price_per_night', 10, 2); // from ERD 'price_per_night'
            $table->text('services')->nullable(); // from ERD 'services'
            $table->json('photos_json')->nullable(); // from ERD 'photos', assuming JSON
            $table->json('videos_json')->nullable(); // from ERD 'videos', assuming JSON
            $table->string('payment_link')->nullable(); // from ERD 'payment_link'
            $table->text('notes')->nullable(); // from ERD 'notes'
            $table->timestamps();

            $table->foreign('hotel_id')
                  ->references('hotel_id')->on('hotels')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

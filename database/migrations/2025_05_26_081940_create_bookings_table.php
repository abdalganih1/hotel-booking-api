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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('book_id'); // as per ERD 'book_id'
            $table->unsignedBigInteger('user_id'); // FK to users (from 'books' relation with users)
            $table->unsignedBigInteger('room_id'); // FK to rooms (from 'books' relation with room)
            $table->enum('booking_status', ['pending_verification', 'confirmed', 'rejected', 'cancelled']); // from ERD 'booking_staus'
            $table->date('booking_date')->useCurrent(); // from ERD 'date' (converted to date)
            $table->date('check_in_date'); // from ERD 'check_in date'
            $table->date('check_out_date'); // from ERD 'check_out date'
            $table->integer('duration_nights'); // from ERD 'durating nights'
            $table->decimal('total_price', 10, 2); // from ERD 'total_price'
            $table->text('user_notes')->nullable(); // from ERD 'user_notes'
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('room_id')
                  ->references('room_id')->on('rooms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id'); // as per ERD 'transaction_id'
            $table->unsignedBigInteger('user_id'); // FK to users
            $table->unsignedBigInteger('booking_id')->nullable(); // FK to bookings
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['credit', 'debit']); // from ERD 'type'
            $table->enum('reason', [
                'deposit', 'booking_payment', 'booking_refund',
                'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'
            ]); // from ERD 'reason'
            $table->timestamp('transaction_date')->useCurrent(); // from ERD 'date' (converted to timestamp)
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('booking_id')
                  ->references('book_id')->on('bookings') // references 'book_id' as per ERD
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

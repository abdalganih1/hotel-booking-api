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
        Schema::create('user_payment_methods', function (Blueprint $table) {
            $table->id(); // as per ERD 'id' for u_p
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->timestamps();

            // Make the combination of user_id and payment_method_id unique
            $table->unique(['user_id', 'payment_method_id'], 'user_payment_method_unique');

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('payment_method_id')
                  ->references('id')->on('payment_methods') // references 'id' in payment_methods
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payment_methods');
    }
};

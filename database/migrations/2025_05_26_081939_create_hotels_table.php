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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id('hotel_id'); // as per ERD 'hotel_id'
            $table->string('name');
            $table->text('location')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('notes')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->unsignedBigInteger('admin_user_id')->nullable(); // FK to users (from 'maneges' relation and 'user_id' attribute)
            $table->json('photos_json')->nullable(); // From ERD 'photos'
            $table->json('videos_json')->nullable(); // From ERD 'videos'
            $table->timestamps();

            $table->foreign('admin_user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};

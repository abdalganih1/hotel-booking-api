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
        Schema::create('hotel_admin_requests', function (Blueprint $table) {
            $table->id('request_id'); // as per ERD 'request_id'
            $table->unsignedBigInteger('user_id'); // FK from 'submite' relation
            $table->string('requested_hotel_name'); // from ERD 'reqestsed_ho_tel_name'
            $table->text('requested_hotel_location')->nullable(); // from ERD 'location'
            $table->string('requested_contact_phone')->nullable(); // from ERD 'contact_phone'
            $table->json('requested_photos_json')->nullable(); // from ERD 'photos', assuming JSON
            $table->json('requested_videos_json')->nullable(); // added for consistency with photos, not in ERD
            $table->text('request_notes')->nullable(); // from ERD 'notes'
            $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending'); // from ERD 'request_staus'
            $table->unsignedBigInteger('reviewed_by_user_id')->nullable(); // Implied by review_timestamp, from previous schema
            $table->timestamp('review_timestamp')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('reviewed_by_user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_admin_requests');
    }
};

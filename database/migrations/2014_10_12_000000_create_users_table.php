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
        Schema::create('users', function (Blueprint $table) {
            // تغيير id() إلى user_id ليطابق مخطط ERD
            $table->id('user_id'); // Primary Key as user_id

            $table->string('username')->unique(); // من مخطط ERD
            $table->string('password'); // تم تغييرها إلى 'password' كما هو مطلوب وموجود في ERD
            $table->enum('role', ['user', 'hotel_admin', 'app_admin'])->default('user'); // من مخطط ERD
            $table->string('first_name')->nullable(); // من مخطط ERD
            $table->string('last_name')->nullable(); // من مخطط ERD
            $table->string('phone_number')->nullable()->unique(); // من مخطط ERD
            $table->text('address')->nullable(); // من مخطط ERD
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // من مخطط ERD
            $table->integer('age')->nullable(); // من مخطط ERD

            $table->string('email')->unique(); // تم الإبقاء عليها من Breeze لأغراض المصادقة
            $table->timestamp('email_verified_at')->nullable(); // من Breeze
            $table->rememberToken(); // من Breeze

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

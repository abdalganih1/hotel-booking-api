<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // أضف hotel_id بعد room_id
            $table->unsignedBigInteger('hotel_id')->after('room_id')->nullable(); // اجعله nullable إذا كان يمكن أن يكون فارغاً مؤقتاً

            // إضافة المفتاح الأجنبي
            $table->foreign('hotel_id')
                  ->references('hotel_id')->on('hotels')
                  ->onDelete('cascade'); // أو set null حسب سياستك
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']); // حذف المفتاح الأجنبي أولاً
            $table->dropColumn('hotel_id'); // ثم حذف العمود
        });
    }
};
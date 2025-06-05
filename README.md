1.  **أسماء الجداول والحقول:** سأستخدم الأسماء الإنجليزية للحقول والجداول كما هي موضحة في الصورة (مثلاً `users`, `hotels`, `user_id`, `hotel_name`). التعليقات العربية في الصورة هي للشرح فقط.
2.  **جدول `users` الافتراضي:** Laravel ينشئ جدول `users` مع حقول معينة. سنقوم بتعديل ملف الهجرة الخاص به ليناسب تصميمك.
3.  **أنواع البيانات:** سأقوم بتحويل أنواع البيانات من الصورة إلى ما يقابلها في Laravel Migrations.
4.  **العلاقات:** سأقوم بتضمين تعريف العلاقات الأساسية (Foreign Keys) في ملفات الهجرة، وكذلك تعريف دوال العلاقات في النماذج (Models).
5.  **`id` مقابل `*_id`:** مخططك يستخدم `user_id`, `hotel_id` كـ Primary Keys. Laravel افتراضيًا يستخدم `id`. سأقوم بتخصيص ذلك في النماذج وملفات الهجرة.

---

**الخطوات التفصيلية:**

**الخطوة 0: المتطلبات الأساسية**

*   تأكد من أن لديك PHP, Composer, و RDBMS (مثل MySQL, PostgreSQL) مثبتة على نظامك.
*   تأكد من أن لديك محرر أكواد جيد (مثل VS Code).

**الخطوة 1: إنشاء مشروع Laravel جديد**

افتح الطرفية (Terminal/Command Prompt) ونفذ الأمر التالي لإنشاء مشروع جديد (استبدل `hotel_reservation_platform` باسم مشروعك المفضل):

```bash
composer create-project --prefer-dist laravel/laravel hotel_reservation_platform
cd hotel_reservation_platform
```

**الخطوة 2: إعداد قاعدة البيانات**

1.  قم بإنشاء قاعدة بيانات جديدة في نظام إدارة قواعد البيانات الخاص بك (مثلاً `hotel_reservation_db` في MySQL).
2.  افتح ملف `.env` في جذر مشروع Laravel وقم بتعديل إعدادات الاتصال بقاعدة البيانات:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=hotel_reservation_db  // اسم قاعدة البيانات التي أنشأتها
    DB_USERNAME=root                // اسم مستخدم قاعدة البيانات
    DB_PASSWORD=                    // كلمة مرور مستخدم قاعدة البيانات
    ```
3.  (اختياري ولكن موصى به) قم بتشغيل `php artisan key:generate` إذا لم يتم إنشاؤه تلقائيًا.

**الخطوة 3: إنشاء ملفات الهجرة (Migrations) والنماذج (Models)**

سنقوم بإنشاء النماذج وملفات الهجرة المصاحبة لها باستخدام الأمر `php artisan make:model ModelName -m`.

1.  **`User` Model and Migration (تعديل الموجود)**
    *   Laravel يأتي مع ملف هجرة لجدول `users` موجود في `database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`.
    *   سنقوم بتعديل هذا الملف.
    *   إذا لم يكن لديك نموذج `User.php` في `app/Models/`, أنشئه: `php artisan make:model User` (بدون `-m` إذا كان ملف الهجرة موجودًا بالفعل).

2.  **`PaymentMethod` Model and Migration**
    ```bash
    php artisan make:model PaymentMethod -m
    ```

3.  **`Faq` Model and Migration**
    ```bash
    php artisan make:model Faq -m
    ```

4.  **`Hotel` Model and Migration**
    ```bash
    php artisan make:model Hotel -m
    ```

5.  **`Room` Model and Migration**
    ```bash
    php artisan make:model Room -m
    ```

6.  **`HotelAdminRequest` Model and Migration**
    ```bash
    php artisan make:model HotelAdminRequest -m
    ```

7.  **`Booking` Model and Migration**
    ```bash
    php artisan make:model Booking -m
    ```

8.  **`Transaction` (UserBalances) Model and Migration**
    *   الجدول في الصورة يسمى `transaction_id (PK)` ولكن السياق يشير إلى "أرصدة المستخدمين" أو معاملات الرصيد. سأسمي النموذج `Transaction` والجدول `transactions`.
    ```bash
    php artisan make:model Transaction -m
    ```

**الخطوة 4: ملء ملفات الهجرة (Migrations)**

الآن، افتح ملفات الهجرة التي تم إنشاؤها في مجلد `database/migrations` وقم بتعديل دالة `up()` في كل ملف.

1.  **`xxxx_xx_xx_xxxxxx_create_users_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id'); // Primary Key as user_id
                $table->string('username')->unique();
                $table->string('password_hash'); // In Laravel, you'd store the hashed password
                $table->enum('role', ['user', 'hotel_admin', 'app_admin']);
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('phone_number')->nullable()->unique();
                $table->text('address')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->integer('age')->nullable();
                // $table->rememberToken(); // Laravel default, keep if using built-in auth features
                $table->timestamps(); // created_at and updated_at
            });
        }

        public function down()
        {
            Schema::dropIfExists('users');
        }
    };
    ```

2.  **`xxxx_xx_xx_xxxxxx_create_payment_methods_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id('payment_method_id'); // Primary Key
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('payment_methods');
        }
    };
    ```

3.  **`xxxx_xx_xx_xxxxxx_create_faqs_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id('faq_id'); // Primary Key
                $table->text('question');
                $table->text('answer');
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('faqs');
        }
    };
    ```

4.  **`xxxx_xx_xx_xxxxxx_create_hotels_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id('hotel_id'); // Primary Key
                $table->string('name');
                $table->decimal('rating', 2, 1)->nullable(); // e.g., 4.5
                $table->text('location')->nullable();
                $table->string('contact_person_phone')->nullable();
                $table->json('photos_json')->nullable();
                $table->json('videos_json')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('admin_user_id')->nullable(); // Foreign Key
                $table->timestamps();

                // Foreign key constraint
                // User with role 'hotel_admin' links here via admin_user_id
                $table->foreign('admin_user_id')
                      ->references('user_id')->on('users')
                      ->onDelete('set null'); // Or 'cascade' if a hotel must have an admin
            });
        }

        public function down()
        {
            Schema::dropIfExists('hotels');
        }
    };
    ```

5.  **`xxxx_xx_xx_xxxxxx_create_rooms_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id('room_id'); // Primary Key
                $table->unsignedBigInteger('hotel_id'); // Foreign Key
                $table->integer('max_occupancy');
                $table->decimal('price_per_night', 10, 2);
                $table->text('services_offered')->nullable();
                $table->json('photos_json')->nullable();
                $table->json('videos_json')->nullable();
                $table->string('payment_link')->nullable(); // رابط الدفع (إن وجد) – غير مطلوب
                $table->text('notes')->nullable();
                $table->timestamps();

                // Foreign key constraint
                $table->foreign('hotel_id')
                      ->references('hotel_id')->on('hotels')
                      ->onDelete('cascade'); // If a hotel is deleted, its rooms are also deleted
            });
        }

        public function down()
        {
            Schema::dropIfExists('rooms');
        }
    };
    ```

6.  **`xxxx_xx_xx_xxxxxx_create_hotel_admin_requests_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('hotel_admin_requests', function (Blueprint $table) {
                $table->id('request_id'); // Primary Key
                $table->unsignedBigInteger('user_id'); // FK: User making the request
                $table->string('requested_hotel_name');
                $table->text('requested_hotel_location')->nullable();
                $table->string('requested_contact_phone')->nullable();
                $table->json('requested_photos_json')->nullable();
                $table->json('requested_videos_json')->nullable();
                $table->text('request_notes')->nullable();
                $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('reviewed_by_user_id')->nullable(); // FK: App admin who reviewed
                $table->timestamp('review_timestamp')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')
                      ->references('user_id')->on('users')
                      ->onDelete('cascade');

                $table->foreign('reviewed_by_user_id')
                      ->references('user_id')->on('users')
                      ->onDelete('set null');
            });
        }

        public function down()
        {
            Schema::dropIfExists('hotel_admin_requests');
        }
    };
    ```

7.  **`xxxx_xx_xx_xxxxxx_create_bookings_table.php`**

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id('booking_id'); // Primary Key
                $table->unsignedBigInteger('user_id'); // FK
                $table->unsignedBigInteger('room_id'); // FK
                $table->unsignedBigInteger('hotel_id'); // FK (Denormalized for convenience as per image)
                $table->enum('booking_status', ['pending_verification', 'confirmed', 'rejected', 'cancelled']);
                $table->timestamp('booking_date')->useCurrent();
                $table->date('check_in_date');
                $table->date('check_out_date');
                $table->integer('duration_nights');
                $table->decimal('total_price', 10, 2);
                $table->text('user_notes')->nullable();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')
                      ->references('user_id')->on('users')
                      ->onDelete('cascade');

                $table->foreign('room_id')
                      ->references('room_id')->on('rooms')
                      ->onDelete('cascade'); // Or restrict if you want to prevent room deletion if booked

                $table->foreign('hotel_id')
                      ->references('hotel_id')->on('hotels')
                      ->onDelete('cascade'); // Or restrict
            });
        }

        public function down()
        {
            Schema::dropIfExists('bookings');
        }
    };
    ```

8.  **`xxxx_xx_xx_xxxxxx_create_transactions_table.php`** (لجدول UserBalances/Transactions)

    ```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id('transaction_id'); // Primary Key
                $table->unsignedBigInteger('user_id'); // FK
                $table->enum('transaction_type', ['credit', 'debit']); // دائن، مدين
                $table->decimal('amount', 10, 2);
                $table->enum('reason', [
                    'deposit',              // إيداع رصيد (تحويل رصيد)
                    'booking_payment',      // طلب أجار (دفع حجز)
                    'booking_refund',       // استرجاع مبلغ حجز (عند الرفض أو الإلغاء)
                    'hotel_commission',     // ربح من نسبة 80% (لمسؤول الفندق)
                    'admin_commission',     // ربح من نسبة 20% (لمدير التطبيق)
                    'cancellation_fee',     // رسوم إلغاء (إذا طبقت)
                    'transfer'              // تحويل رصيد عام (يمكن استخدامه لأغراض أخرى)
                ]);
                $table->unsignedBigInteger('booking_id')->nullable(); // FK, related booking if applicable
                $table->timestamp('transaction_date')->useCurrent();
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('user_id')
                      ->references('user_id')->on('users')
                      ->onDelete('cascade');

                $table->foreign('booking_id')
                      ->references('booking_id')->on('bookings')
                      ->onDelete('set null'); // If booking deleted, keep transaction record
            });
        }

        public function down()
        {
            Schema::dropIfExists('transactions');
        }
    };
    ```

**الخطوة 5: ملء ملفات النماذج (Models)**

افتح ملفات النماذج في `app/Models/` وقم بتعديلها لإضافة `$fillable`, `$primaryKey` (إذا لم يكن `id`), و دوال العلاقات.

1.  **`app/Models/User.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Contracts\Auth\MustVerifyEmail; // If you use email verification
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens; // If using Sanctum for API auth

    class User extends Authenticatable // (implements MustVerifyEmail - if needed)
    {
        use HasApiTokens, HasFactory, Notifiable;

        protected $primaryKey = 'user_id'; // Custom primary key

        protected $fillable = [
            'username',
            'password_hash', // Store hashed password here
            'role',
            'first_name',
            'last_name',
            'phone_number',
            'address',
            'gender',
            'age',
        ];

        protected $hidden = [
            'password_hash', // Or 'password' if you rename for Laravel's Auth
            'remember_token',
        ];

        protected $casts = [
            'email_verified_at' => 'datetime', // If you add this column
            'role' => 'string', // ENUMs are usually fine as strings
            'gender' => 'string',
        ];

        // Relationships
        public function hotelAdminFor() // If this user is a hotel_admin for a specific hotel
        {
            return $this->hasOne(Hotel::class, 'admin_user_id', 'user_id');
        }

        public function bookings()
        {
            return $this->hasMany(Booking::class, 'user_id', 'user_id');
        }

        public function transactions()
        {
            return $this->hasMany(Transaction::class, 'user_id', 'user_id');
        }

        public function hotelAdminRequests() // Requests made by this user
        {
            return $this->hasMany(HotelAdminRequest::class, 'user_id', 'user_id');
        }

        public function reviewedHotelAdminRequests() // Requests reviewed by this app_admin
        {
            return $this->hasMany(HotelAdminRequest::class, 'reviewed_by_user_id', 'user_id');
        }
    }
    ```
    *   **ملاحظة هامة لـ `User.php`:** إذا كنت ستستخدم نظام المصادقة المدمج في Laravel، فإنه يتوقع عمود `password`. قد تحتاج إلى تعديل `password_hash` إلى `password` وتعيين `$hidden = ['password', 'remember_token'];`. عملية Hashing لكلمة المرور تتم تلقائياً عند التسجيل إذا استخدمت نظام Laravel.

2.  **`app/Models/PaymentMethod.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class PaymentMethod extends Model
    {
        use HasFactory;

        protected $primaryKey = 'payment_method_id';

        protected $fillable = [
            'name',
            'description',
        ];
    }
    ```

3.  **`app/Models/Faq.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Faq extends Model
    {
        use HasFactory;

        protected $primaryKey = 'faq_id';

        protected $fillable = [
            'question',
            'answer',
        ];
    }
    ```

4.  **`app/Models/Hotel.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Hotel extends Model
    {
        use HasFactory;

        protected $primaryKey = 'hotel_id';

        protected $fillable = [
            'name',
            'rating',
            'location',
            'contact_person_phone',
            'photos_json',
            'videos_json',
            'notes',
            'admin_user_id',
        ];

        protected $casts = [
            'photos_json' => 'array', // Cast JSON to array
            'videos_json' => 'array',
            'rating' => 'float',
        ];

        // Relationships
        public function adminUser() // The hotel_admin user for this hotel
        {
            return $this->belongsTo(User::class, 'admin_user_id', 'user_id');
        }

        public function rooms()
        {
            return $this->hasMany(Room::class, 'hotel_id', 'hotel_id');
        }

        public function bookings()
        {
            return $this->hasMany(Booking::class, 'hotel_id', 'hotel_id');
        }
    }
    ```

5.  **`app/Models/Room.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Room extends Model
    {
        use HasFactory;

        protected $primaryKey = 'room_id';

        protected $fillable = [
            'hotel_id',
            'max_occupancy',
            'price_per_night',
            'services_offered',
            'photos_json',
            'videos_json',
            'payment_link',
            'notes',
        ];

        protected $casts = [
            'photos_json' => 'array',
            'videos_json' => 'array',
            'price_per_night' => 'decimal:2',
        ];

        // Relationships
        public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
        }

        public function bookings()
        {
            return $this->hasMany(Booking::class, 'room_id', 'room_id');
        }
    }
    ```

6.  **`app/Models/HotelAdminRequest.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class HotelAdminRequest extends Model
    {
        use HasFactory;

        protected $primaryKey = 'request_id';

        protected $fillable = [
            'user_id',
            'requested_hotel_name',
            'requested_hotel_location',
            'requested_contact_phone',
            'requested_photos_json',
            'requested_videos_json',
            'request_notes',
            'request_status',
            'reviewed_by_user_id',
            'review_timestamp',
        ];

        protected $casts = [
            'requested_photos_json' => 'array',
            'requested_videos_json' => 'array',
            'review_timestamp' => 'datetime',
            'request_status' => 'string', // ENUM
        ];

        // Relationships
        public function user() // User who made the request
        {
            return $this->belongsTo(User::class, 'user_id', 'user_id');
        }

        public function reviewer() // App admin who reviewed
        {
            return $this->belongsTo(User::class, 'reviewed_by_user_id', 'user_id');
        }
    }
    ```

7.  **`app/Models/Booking.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Booking extends Model
    {
        use HasFactory;

        protected $primaryKey = 'booking_id';

        protected $fillable = [
            'user_id',
            'room_id',
            'hotel_id',
            'booking_status',
            'booking_date',
            'check_in_date',
            'check_out_date',
            'duration_nights',
            'total_price',
            'user_notes',
        ];

        protected $casts = [
            'booking_date' => 'datetime',
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'total_price' => 'decimal:2',
            'booking_status' => 'string', // ENUM
        ];

        // Relationships
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id', 'user_id');
        }

        public function room()
        {
            return $this->belongsTo(Room::class, 'room_id', 'room_id');
        }

        public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
        }

        public function transactions()
        {
            return $this->hasMany(Transaction::class, 'booking_id', 'booking_id');
        }
    }
    ```

8.  **`app/Models/Transaction.php`**

    ```php
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Transaction extends Model
    {
        use HasFactory;

        protected $primaryKey = 'transaction_id';

        protected $fillable = [
            'user_id',
            'transaction_type',
            'amount',
            'reason',
            'booking_id',
            'transaction_date',
        ];

        protected $casts = [
            'amount' => 'decimal:2',
            'transaction_date' => 'datetime',
            'transaction_type' => 'string', // ENUM
            'reason' => 'string',           // ENUM
        ];

        // Relationships
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id', 'user_id');
        }

        public function booking() // The booking this transaction is related to (if any)
        {
            return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
        }
    }
    ```

**الخطوة 6: تشغيل ملفات الهجرة**

بعد تعديل جميع ملفات الهجرة والنماذج، عد إلى الطرفية ونفذ الأمر التالي لإنشاء الجداول في قاعدة البيانات:

```bash
php artisan migrate
```

إذا كنت تريد إعادة إنشاء الجداول من البداية (سيحذف جميع البيانات الموجودة):

```bash
php artisan migrate:fresh
```

يمكنك أيضاً إضافة بيانات أولية (Seeding) إذا أردت:

```bash
php artisan migrate:fresh --seed
```

(هذا يتطلب إنشاء ملفات Seeder أولاً).

---

بهذه الخطوات، تكون قد أنشأت مشروع Laravel، وقمت بتعريف هيكل قاعدة البيانات الخاصة بك من خلال ملفات الهجرة، وأنشأت النماذج المقابلة مع تعريف العلاقات الأساسية بينها. يمكنك الآن البدء في بناء منطق التطبيق الخاص بك (Controllers, Routes, Views, API Resources).



**الخطوة 1: إنشاء ملفات الـ Seeders**

افتح الطرفية (Terminal) في جذر مشروع Laravel الخاص بك ونفذ الأوامر التالية لإنشاء ملف Seeder لكل جدول:

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder PaymentMethodSeeder
php artisan make:seeder FaqSeeder
php artisan make:seeder HotelSeeder
php artisan make:seeder RoomSeeder
php artisan make:seeder HotelAdminRequestSeeder
php artisan make:seeder BookingSeeder
php artisan make:seeder TransactionSeeder
```

ستجد الملفات التي تم إنشاؤها في مجلد `database/seeders/`.

**الخطوة 2: ملء ملفات الـ Seeders بالبيانات**

الآن، سنقوم بملء دالة `run()` في كل ملف Seeder.

**1. `database/seeders/UserSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // تأكد من استيراد النموذج

class UserSeeder extends Seeder
{
    public function run()
    {
        // مدير التطبيق الرئيسي
        User::create([
            'username' => 'app_admin',
            'password_hash' => Hash::make('password'), // كلمة مرور قوية في الواقع
            'role' => 'app_admin',
            'first_name' => 'مدير',
            'last_name' => 'التطبيق',
            'phone_number' => '0900000001',
            'email' => 'appadmin@example.com', // إذا أضفت حقل الإيميل
        ]);

        // مسؤول فندق
        User::create([
            'username' => 'hotel_manager_A',
            'password_hash' => Hash::make('password'),
            'role' => 'hotel_admin',
            'first_name' => 'مسؤول فندق',
            'last_name' => 'أ',
            'phone_number' => '0900000002',
            'email' => 'hoteladminA@example.com',
        ]);

        // مستخدم عادي 1
        User::create([
            'username' => 'regular_user1',
            'password_hash' => Hash::make('password'),
            'role' => 'user',
            'first_name' => 'مستخدم',
            'last_name' => 'عادي1',
            'phone_number' => '0900000003',
            'address' => 'العنوان الأول، المدينة',
            'gender' => 'male',
            'age' => 30,
            'email' => 'user1@example.com',
        ]);

        // مستخدم عادي 2
        User::create([
            'username' => 'regular_user2',
            'password_hash' => Hash::make('password'),
            'role' => 'user',
            'first_name' => 'مستخدمة',
            'last_name' => 'عادية2',
            'phone_number' => '0900000004',
            'address' => 'العنوان الثاني، المدينة',
            'gender' => 'female',
            'age' => 25,
            'email' => 'user2@example.com',
        ]);
    }
}
```
*ملاحظة:* إذا لم يكن لديك حقل `email` في جدول `users` كما في الصورة الأصلية، يمكنك إزالته من الـ Seeder.

**2. `database/seeders/PaymentMethodSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::create(['name' => 'بطاقة ائتمان', 'description' => 'الدفع باستخدام بطاقة الائتمان فيزا أو ماستركارد']);
        PaymentMethod::create(['name' => 'باي بال', 'description' => 'الدفع الآمن عبر حساب باي بال']);
        PaymentMethod::create(['name' => 'تحويل بنكي', 'description' => 'الدفع عبر تحويل مباشر للحساب البنكي']);
    }
}
```

**3. `database/seeders/FaqSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        Faq::create([
            'question' => 'كيف يمكنني حجز غرفة؟',
            'answer' => 'يمكنك حجز غرفة عن طريق تصفح الفنادق المتاحة واختيار الغرفة المناسبة ثم اتباع خطوات الحجز.'
        ]);
        Faq::create([
            'question' => 'هل يمكنني إلغاء الحجز؟',
            'answer' => 'نعم، يمكنك طلب إلغاء الحجز وفقًا لسياسة الإلغاء الخاصة بالفندق. قد يتم تطبيق رسوم.'
        ]);
        Faq::create([
            'question' => 'كيف أضيف رصيد إلى حسابي؟',
            'answer' => 'يمكنك إضافة رصيد من خلال صفحة إدارة الرصيد واختيار وسيلة الدفع المناسبة.'
        ]);
    }
}
```

**4. `database/seeders/HotelSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\User;

class HotelSeeder extends Seeder
{
    public function run()
    {
        $hotelAdminUser = User::where('role', 'hotel_admin')->first(); // يفترض وجود مسؤول فندق واحد على الأقل

        if ($hotelAdminUser) {
            Hotel::create([
                'name' => 'فندق الأحلام',
                'rating' => 4.5,
                'location' => 'شارع الأحلام، مدينة السعادة',
                'contact_person_phone' => $hotelAdminUser->phone_number, // أو رقم هاتف الفندق مباشرة
                'photos_json' => json_encode(['url1.jpg', 'url2.jpg']),
                'videos_json' => json_encode(['video1.mp4']),
                'notes' => 'فندق فاخر يوفر إقامة مريحة.',
                'admin_user_id' => $hotelAdminUser->user_id,
            ]);

            Hotel::create([
                'name' => 'فندق الواحة',
                'rating' => 4.0,
                'location' => 'طريق الواحة، مدينة الهدوء',
                'contact_person_phone' => '0911111111',
                'photos_json' => json_encode(['oasis1.jpg', 'oasis2.jpg']),
                'notes' => 'فندق هادئ ومناسب للاسترخاء.',
                'admin_user_id' => null, // يمكن أن يكون فندق بدون مسؤول معين مبدئياً
            ]);
        } else {
            $this->command->info('No hotel admin user found to assign to hotels. Please seed users first.');
        }
    }
}
```

**5. `database/seeders/RoomSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Hotel;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $hotel1 = Hotel::find(1); // يفترض أن فندق الأحلام هو ID 1
        $hotel2 = Hotel::find(2); // يفترض أن فندق الواحة هو ID 2

        if ($hotel1) {
            Room::create([
                'hotel_id' => $hotel1->hotel_id,
                'max_occupancy' => 2,
                'price_per_night' => 150.00,
                'services_offered' => 'واي فاي مجاني, إفطار, تكييف',
                'photos_json' => json_encode(['room1_h1.jpg', 'room2_h1.jpg']),
                'notes' => 'غرفة مزدوجة بإطلالة رائعة',
            ]);
            Room::create([
                'hotel_id' => $hotel1->hotel_id,
                'max_occupancy' => 1,
                'price_per_night' => 100.00,
                'services_offered' => 'واي فاي مجاني, تكييف',
                'photos_json' => json_encode(['room_single_h1.jpg']),
                'notes' => 'غرفة مفردة مريحة',
            ]);
        }

        if ($hotel2) {
            Room::create([
                'hotel_id' => $hotel2->hotel_id,
                'max_occupancy' => 4,
                'price_per_night' => 250.00,
                'services_offered' => 'واي فاي, إفطار عائلي, مسبح صغير',
                'photos_json' => json_encode(['family_room_h2.jpg']),
                'notes' => 'جناح عائلي واسع',
            ]);
        }
    }
}
```

**6. `database/seeders/HotelAdminRequestSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelAdminRequest;
use App\Models\User;
use Carbon\Carbon;

class HotelAdminRequestSeeder extends Seeder
{
    public function run()
    {
        $requestingUser = User::where('username', 'regular_user2')->first();
        $appAdminUser = User::where('role', 'app_admin')->first();

        if ($requestingUser) {
            HotelAdminRequest::create([
                'user_id' => $requestingUser->user_id,
                'requested_hotel_name' => 'فندق النجوم الجديد',
                'requested_hotel_location' => 'شارع المستقبل، مدينة الغد',
                'requested_contact_phone' => '0987654321',
                'request_status' => 'pending',
            ]);
        }

        if ($requestingUser && $appAdminUser) {
             HotelAdminRequest::create([
                'user_id' => $requestingUser->user_id,
                'requested_hotel_name' => 'منتجع الشاطئ الذهبي',
                'requested_hotel_location' => 'ساحل الأحلام، جزيرة السعادة',
                'requested_contact_phone' => '0912345678',
                'request_status' => 'approved',
                'reviewed_by_user_id' => $appAdminUser->user_id,
                'review_timestamp' => Carbon::now()->subDays(2),
            ]);
        }
    }
}
```

**7. `database/seeders/BookingSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use App\Models\Hotel;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('username', 'regular_user1')->first();
        $room1_hotel1 = Room::find(1); // غرفة في فندق الأحلام

        if ($user1 && $room1_hotel1) {
            $checkIn = Carbon::now()->addDays(5);
            $checkOut = Carbon::now()->addDays(8);
            $duration = $checkOut->diffInDays($checkIn);
            $totalPrice = $duration * $room1_hotel1->price_per_night;

            Booking::create([
                'user_id' => $user1->user_id,
                'room_id' => $room1_hotel1->room_id,
                'hotel_id' => $room1_hotel1->hotel_id,
                'booking_status' => 'pending_verification',
                'booking_date' => Carbon::now(),
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'duration_nights' => $duration,
                'total_price' => $totalPrice,
                'user_notes' => 'أرجو توفير سرير إضافي للطفل.',
            ]);

            // حجز مؤكد
            $room2_hotel1 = Room::find(2); // غرفة أخرى في نفس الفندق
             if ($room2_hotel1) {
                $checkInConfirmed = Carbon::now()->subDays(10); // حجز سابق
                $checkOutConfirmed = Carbon::now()->subDays(7);
                $durationConfirmed = $checkOutConfirmed->diffInDays($checkInConfirmed);
                $totalPriceConfirmed = $durationConfirmed * $room2_hotel1->price_per_night;

                Booking::create([
                    'user_id' => $user1->user_id,
                    'room_id' => $room2_hotel1->room_id,
                    'hotel_id' => $room2_hotel1->hotel_id,
                    'booking_status' => 'confirmed',
                    'booking_date' => Carbon::now()->subDays(12),
                    'check_in_date' => $checkInConfirmed,
                    'check_out_date' => $checkOutConfirmed,
                    'duration_nights' => $durationConfirmed,
                    'total_price' => $totalPriceConfirmed,
                ]);
            }
        }
    }
}
```

**8. `database/seeders/TransactionSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking;
use App\Models\Hotel;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('username', 'regular_user1')->first();
        $appAdmin = User::where('role', 'app_admin')->first();
        $hotelAdminA = User::where('username', 'hotel_manager_A')->first();

        // إيداع رصيد للمستخدم الأول
        if ($user1) {
            Transaction::create([
                'user_id' => $user1->user_id,
                'transaction_type' => 'credit',
                'amount' => 500.00,
                'reason' => 'deposit',
                'transaction_date' => Carbon::now()->subDays(1),
            ]);
        }

        // معاملة مرتبطة بحجز مؤكد
        $confirmedBooking = Booking::where('booking_status', 'confirmed')->first();
        if ($confirmedBooking && $user1 && $appAdmin && $hotelAdminA) {
            // 1. دفع المستخدم للحجز (خصم من رصيد المستخدم)
            Transaction::create([
                'user_id' => $confirmedBooking->user_id, // المستخدم الذي قام بالحجز
                'transaction_type' => 'debit',
                'amount' => $confirmedBooking->total_price,
                'reason' => 'booking_payment',
                'booking_id' => $confirmedBooking->booking_id,
                'transaction_date' => $confirmedBooking->booking_date->addHours(1), // بعد الحجز بقليل
            ]);

            // 2. عمولة مسؤول الفندق (80%)
            $hotel = Hotel::find($confirmedBooking->hotel_id);
            if ($hotel && $hotel->adminUser) { // التأكد من وجود مسؤول للفندق
                 Transaction::create([
                    'user_id' => $hotel->adminUser->user_id, // رصيد مسؤول الفندق
                    'transaction_type' => 'credit',
                    'amount' => $confirmedBooking->total_price * 0.80,
                    'reason' => 'hotel_commission',
                    'booking_id' => $confirmedBooking->booking_id,
                    'transaction_date' => $confirmedBooking->booking_date->addHours(2),
                ]);
            }


            // 3. عمولة مدير التطبيق (20%)
            Transaction::create([
                'user_id' => $appAdmin->user_id, // رصيد مدير التطبيق
                'transaction_type' => 'credit',
                'amount' => $confirmedBooking->total_price * 0.20,
                'reason' => 'admin_commission',
                'booking_id' => $confirmedBooking->booking_id,
                'transaction_date' => $confirmedBooking->booking_date->addHours(2),
            ]);
        }

        // معاملة استرجاع لحجز ملغي (افتراضي)
        if ($user1) {
            Transaction::create([
                'user_id' => $user1->user_id,
                'transaction_type' => 'credit',
                'amount' => 75.00,
                'reason' => 'booking_refund',
                // 'booking_id' => ID_OF_A_CANCELLED_BOOKING, // إذا كان هناك حجز ملغي
                'transaction_date' => Carbon::now()->subHours(5),
            ]);
        }
    }
}
```

**الخطوة 3: تسجيل الـ Seeders في `DatabaseSeeder.php`**

افتح ملف `database/seeders/DatabaseSeeder.php` وقم بتعديل دالة `run()` لاستدعاء جميع الـ Seeders التي أنشأتها. **ترتيب الاستدعاء مهم بسبب الاعتماديات (Foreign Keys).**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PaymentMethodSeeder::class,
            FaqSeeder::class,
            HotelSeeder::class,         // يعتمد على UserSeeder (لمسؤول الفندق)
            RoomSeeder::class,          // يعتمد على HotelSeeder
            HotelAdminRequestSeeder::class, // يعتمد على UserSeeder
            BookingSeeder::class,       // يعتمد على UserSeeder, RoomSeeder, HotelSeeder
            TransactionSeeder::class,   // يعتمد على UserSeeder, BookingSeeder (اختياريًا)
        ]);
    }
}
```

**الخطوة 4: تشغيل الـ Seeders**

الآن، يمكنك تشغيل الـ Seeders من الطرفية. هذا الأمر سيحذف جميع البيانات الموجودة في الجداول (بسبب `migrate:fresh`) ثم يعيد بناء الجداول ويملؤها بالبيانات من الـ Seeders.

```bash
php artisan migrate:fresh --seed
```

إذا كنت لا تريد حذف البيانات وإعادة بناء الجداول، وتريد فقط تشغيل الـ Seeders (بافتراض أن الجداول موجودة وفارغة أو تريد إضافة المزيد من البيانات):

```bash
php artisan db:seed
```

أو لتشغيل Seeder معين:

```bash
php artisan db:seed --class=UserSeeder
```

بهذه الطريقة، سيكون لديك بيانات أولية في قاعدة البيانات يمكنك استخدامها أثناء تطوير واختبار تطبيقك. تذكر أن تعدل البيانات لتناسب احتياجاتك بشكل أفضل.


بالتأكيد، سأقوم بتصميم هيكل المتحكمات (Controllers) لمشروعك، مع التركيز على API أولاً، ثم اقتراح بعض المتحكمات للـ Web إذا كانت هناك واجهات ويب تقليدية.

**ملاحظات هامة:**

1.  **API First:** بما أن الواجهة الأمامية ستكون Flutter، فإن التركيز الأساسي سيكون على متحكمات الـ API.
2.  **Resource Controllers:** سأستخدم `php artisan make:controller --api --model=ModelName` لإنشاء متحكمات API نمطية (resourceful) حيثما أمكن ذلك، مما يوفر دوال `index`, `store`, `show`, `update`, `destroy` بشكل افتراضي.
3.  **التفويض (Authorization):** منطق التحقق من صلاحيات المستخدم (هل هو مستخدم عادي، مسؤول فندق، أو مدير تطبيق) يجب أن يتم إما عبر Middleware أو باستخدام Laravel Policies. سأشير إلى ذلك في التعليقات داخل المتحكمات، لكن التنفيذ الفعلي لها يتطلب خطوات إضافية.
4.  **التحقق من صحة الإدخال (Validation):** يجب إضافة قواعد التحقق من صحة المدخلات في كل دالة تقبل بيانات من المستخدم (مثل `store`, `update`). سأضيف تعليقات لذلك.
5.  **API Resources:** لإرجاع بيانات الـ API بشكل منظم ومتناسق، يفضل استخدام Laravel API Resources. لن أكتب كود الـ Resources هنا، لكن سأشير إلى مكان استخدامها.
6.  **Web Controllers:** إذا كان هناك جزء ويب إداري (مثلاً لمدير التطبيق)، فستكون متحكمات الـ Web مشابهة لمتحكمات الـ API ولكنها سترجع Views بدلاً من JSON.

**هيكل المجلدات المقترح للمتحكمات:**

```
app/Http/Controllers/
├── Api/
│   ├── AuthController.php
│   ├── UserController.php
│   ├── HotelController.php
│   ├── RoomController.php
│   ├── BookingController.php
│   ├── TransactionController.php
│   ├── FaqController.php
│   ├── HotelAdminRequestController.php
│   ├── PaymentMethodController.php // (إذا كنت ستديرها عبر API)
│   └── Admin/ // متحكمات خاصة بمدير التطبيق
│       ├── AdminUserController.php
│       ├── AdminHotelController.php
│       ├── AdminFaqController.php
│       ├── AdminHotelAdminRequestController.php
│       └── AdminFinancialController.php
│   └── HotelAdmin/ // متحكمات خاصة بمسؤول الفندق
│       ├── HotelAdminHotelController.php
│       ├── HotelAdminRoomController.php
│       └── HotelAdminBookingController.php
└── Web/ // (إذا كان هناك واجهة ويب)
    ├── Admin/
    │   ├── DashboardController.php
    │   ├── UserController.php
    │   ├── HotelController.php
    │   └── // ... (باقي متحكمات الويب للإدارة)
    └── // ... (متحكمات ويب عامة إذا وجدت)
```

**الخطوة 1: إنشاء ملفات المتحكمات (API)**

```bash
# Authentication
php artisan make:controller Api/AuthController

# General User accessible controllers
php artisan make:controller Api/UserController --api --model=User
php artisan make:controller Api/HotelController --api --model=Hotel
php artisan make:controller Api/RoomController --api --model=Room # Nested under hotels usually
php artisan make:controller Api/BookingController --api --model=Booking
php artisan make:controller Api/TransactionController --api --model=Transaction
php artisan make:controller Api/FaqController --api --model=Faq
php artisan make:controller Api/HotelAdminRequestController --api --model=HotelAdminRequest
php artisan make:controller Api/PaymentMethodController --api --model=PaymentMethod # Optional

# Admin Specific Controllers
php artisan make:controller Api/Admin/AdminUserController --api --model=User
php artisan make:controller Api/Admin/AdminHotelController --api --model=Hotel
php artisan make:controller Api/Admin/AdminFaqController --api --model=Faq
php artisan make:controller Api/Admin/AdminHotelAdminRequestController --api --model=HotelAdminRequest
php artisan make:controller Api/Admin/AdminFinancialController # For financial overview and management

# Hotel Admin Specific Controllers
php artisan make:controller Api/HotelAdmin/HotelAdminHotelController --api --model=Hotel # Manages their specific hotel
php artisan make:controller Api/HotelAdmin/HotelAdminRoomController --api --model=Room # Manages rooms of their hotel
php artisan make:controller Api/HotelAdmin/HotelAdminBookingController --api --model=Booking # Manages bookings for their hotel
```

---

**الخطوة 2: محتوى ملفات المتحكمات (API)**

سأقدم هيكلًا أساسيًا مع تعليقات. يجب ملء منطق التحقق من الصحة، التفويض، ومنطق الأعمال.

**1. `app/Http/Controllers/Api/AuthController.php`**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\UserResource; // مثال

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // confirmed يتطلب password_confirmation
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|unique:users,phone_number',
            // ... (باقي الحقول الاختيارية للتسجيل)
            'role' => 'sometimes|in:user', // افتراضيًا 'user' عند التسجيل من هذا المسار
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // إذا لم يتم توفيره، يكون مستخدمًا عاديًا
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            // ... (باقي الحقول)
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        // return new UserResource($user); // أو
        return response()->json([
            'user' => $user, // أو UserResource
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // محاولة تسجيل الدخول باستخدام username و password_hash
        // يجب التأكد أن Auth::attempt تستخدم الحقول الصحيحة من .env أو config/auth.php
        // إذا كنت تستخدم password_hash، قد تحتاج لتخصيص Auth::attempt أو جلب المستخدم يدويًا والتحقق من الهاش
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return response()->json(['message' => 'بيانات الاعتماد غير صحيحة'], 401);
        }

        // حذف التوكنز القديمة وإنشاء توكن جديد (اختياري ولكن جيد للأمان)
        $user->tokens()->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user, // أو UserResource
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function user(Request $request)
    {
        // return new UserResource($request->user());
        return response()->json($request->user());
    }
}
```

**2. `app/Http/Controllers/Api/HotelController.php` (للمستخدم العادي)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
// use App\Http\Resources\HotelResource;
// use App\Http\Resources\HotelCollection;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource. (عرض الفنادق)
     */
    public function index(Request $request)
    {
        // TODO: Pagination, Filtering (by location, rating, etc.)
        $hotels = Hotel::with('rooms')->paginate(15); // مثال مع الغرف
        // return new HotelCollection($hotels);
        return response()->json($hotels);
    }

    /**
     * Display the specified resource. (عرض تفاصيل فندق وغرفه)
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms'); // تحميل الغرف المتعلقة بالفندق
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }
}
```

**3. `app/Http/Controllers/Api/RoomController.php` (لعرض تفاصيل غرفة معينة - قد لا تحتاج لكل دوال الـ Resource هنا)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
// use App\Http\Resources\RoomResource;

class RoomController extends Controller
{
    /**
     * Display the specified resource. (عرض تفاصيل غرفة)
     */
    public function show(Room $room)
    {
        // return new RoomResource($room->load('hotel'));
        return response()->json($room->load('hotel'));
    }

    // قد تحتاج لدالة index إذا أردت عرض جميع الغرف بشكل مستقل (مع فلترة)
    // public function index(Request $request)
    // {
    //     // TODO: Filtering, Pagination
    //     $rooms = Room::paginate(15);
    //     return response()->json($rooms);
    // }
}
```

**4. `app/Http/Controllers/Api/BookingController.php` (للمستخدم العادي)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
// use App\Http\Resources\BookingResource;
// use App\Http\Resources\BookingCollection;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // حماية جميع الدوال
    }

    /**
     * Display a listing of the user's bookings. (عرض سجل الحجوزات الشخصية)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $bookings = Booking::where('user_id', $user->user_id)
                            ->with(['room.hotel']) // جلب معلومات الغرفة والفندق
                            ->latest()
                            ->paginate(10);
        // return new BookingCollection($bookings);
        return response()->json($bookings);
    }

    /**
     * Store a newly created booking in storage. (حجز غرفة)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,room_id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'user_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = Room::findOrFail($request->room_id);

        // TODO: Check room availability for the selected dates
        // TODO: Check user balance (هذه عملية معقدة قد تتضمن transactions)

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $durationNights = $checkOut->diffInDays($checkIn);
        $totalPrice = $durationNights * $room->price_per_night;

        // خصم الرصيد (مثال مبسط، يجب أن يكون transaction آمن)
        // if ($user->balance < $totalPrice) {
        //     return response()->json(['message' => 'رصيد غير كافٍ'], 400);
        // }
        // $user->decrement('balance', $totalPrice); // مثال

        $booking = Booking::create([
            'user_id' => $user->user_id,
            'room_id' => $room->room_id,
            'hotel_id' => $room->hotel_id, // denormalized
            'booking_status' => 'pending_verification', // الحالة الأولية
            'booking_date' => Carbon::now(),
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'duration_nights' => $durationNights,
            'total_price' => $totalPrice,
            'user_notes' => $request->user_notes,
        ]);

        // TODO: إنشاء معاملة مالية (transaction) لخصم المبلغ
        // Transaction::create([...]);

        // return new BookingResource($booking->load(['room.hotel']));
        return response()->json($booking->load(['room.hotel']), 201);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, Booking $booking)
    {
        // TODO: Authorization - Ensure the user owns this booking
        if ($request->user()->user_id !== $booking->user_id) {
            return response()->json(['message' => 'غير مصرح به'], 403);
        }
        // return new BookingResource($booking->load(['room.hotel']));
        return response()->json($booking->load(['room.hotel']));
    }


    /**
     * Request cancellation of a booking. (طلب إلغاء حجز)
     */
    public function requestCancellation(Request $request, Booking $booking)
    {
        // TODO: Authorization - Ensure the user owns this booking
        if ($request->user()->user_id !== $booking->user_id) {
            return response()->json(['message' => 'غير مصرح به'], 403);
        }

        // TODO: Logic for cancellation (e.g., check if cancellable, apply fees)
        // لا يمكن الإلغاء إلا إذا كان الحجز قيد التحقق أو مؤكد (ولم يمضِ وقت الإقامة)
        if (!in_array($booking->booking_status, ['pending_verification', 'confirmed'])) {
             return response()->json(['message' => 'لا يمكن إلغاء هذا الحجز في حالته الحالية'], 400);
        }

        $booking->booking_status = 'cancelled'; // أو 'cancellation_requested'
        $booking->save();

        // TODO: إنشاء معاملة مالية (transaction) لاسترجاع المبلغ (إذا كان ذلك ممكنًا)
        // Transaction::create([...]);

        // return new BookingResource($booking);
        return response()->json(['message' => 'تم طلب إلغاء الحجز بنجاح', 'booking' => $booking]);
    }
}
```

**5. `app/Http/Controllers/Api/TransactionController.php` (للمستخدم العادي لعرض رصيده ومعاملاته)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\TransactionCollection;
// use App\Http\Resources\UserResource; // لعرض معلومات المستخدم مع الرصيد

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the user's transactions and current balance.
     * (إدارة الرصيد الشخصي - عرض)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = Transaction::where('user_id', $user->user_id)
                                    ->latest()
                                    ->paginate(15);

        // حساب الرصيد الحالي (يمكن تحسين هذا بجعل الرصيد حقل في جدول المستخدمين يتم تحديثه)
        $credits = Transaction::where('user_id', $user->user_id)->where('transaction_type', 'credit')->sum('amount');
        $debits = Transaction::where('user_id', $user->user_id)->where('transaction_type', 'debit')->sum('amount');
        $currentBalance = $credits - $debits;

        return response()->json([
            'balance' => $currentBalance,
            'transactions' => $transactions // أو TransactionCollection
        ]);
    }

    /**
     * Add funds to user's balance. (إدارة الرصيد الشخصي - إضافة)
     * هذا يتطلب تكامل مع بوابة دفع. هنا مثال مبسط لإنشاء معاملة إيداع.
     */
    public function addFunds(Request $request)
    {
        $user = $request->user();
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:payment_methods,payment_method_id', // مثال
            // ... (بيانات بوابة الدفع مثل رقم البطاقة إلخ، لكن هذا لا يجب تخزينه مباشرة)
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: هنا يتم منطق التكامل مع بوابة الدفع
        // بعد نجاح الدفع من البوابة:
        $transaction = Transaction::create([
            'user_id' => $user->user_id,
            'transaction_type' => 'credit',
            'amount' => $request->amount,
            'reason' => 'deposit',
            // 'booking_id' => null, // لا يوجد حجز مرتبط بالإيداع المباشر
            'transaction_date' => now(),
        ]);

        return response()->json([
            'message' => 'تم إضافة الرصيد بنجاح (محاكاة)',
            'transaction' => $transaction // أو TransactionResource
        ], 201);
    }
}
```

**6. `app/Http/Controllers/Api/FaqController.php` (للمستخدم العادي - عرض فقط)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
// use App\Http\Resources\FaqCollection;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource. (عرض الأسئلة الشائعة)
     */
    public function index()
    {
        $faqs = Faq::all();
        // return new FaqCollection($faqs);
        return response()->json($faqs);
    }
}
```

**7. `app/Http/Controllers/Api/HotelAdminRequestController.php` (للمستخدم العادي لتقديم طلب)**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelAdminRequestResource;

class HotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created resource in storage. (طلب صلاحية مسؤول فندق)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'requested_hotel_name' => 'required|string|max:255',
            'requested_hotel_location' => 'required|string',
            'requested_contact_phone' => 'required|string',
            'requested_photos_json' => 'nullable|json', // أو array إذا كنت تعالج رفع الملفات
            'requested_videos_json' => 'nullable|json',
            'request_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: تحقق إذا كان المستخدم لديه طلب قائم بالفعل أو هو بالفعل مسؤول
        if ($user->role === 'hotel_admin' || $user->role === 'app_admin') {
             return response()->json(['message' => 'أنت بالفعل لديك صلاحيات إدارية.'], 400);
        }
        if (HotelAdminRequest::where('user_id', $user->user_id)->where('request_status', 'pending')->exists()) {
            return response()->json(['message' => 'لديك طلب قائم بالفعل قيد المراجعة.'], 400);
        }


        $hotelAdminRequest = HotelAdminRequest::create([
            'user_id' => $user->user_id,
            'requested_hotel_name' => $request->requested_hotel_name,
            'requested_hotel_location' => $request->requested_hotel_location,
            'requested_contact_phone' => $request->requested_contact_phone,
            'requested_photos_json' => $request->requested_photos_json,
            'requested_videos_json' => $request->requested_videos_json,
            'request_notes' => $request->request_notes,
            'request_status' => 'pending',
        ]);

        // return new HotelAdminRequestResource($hotelAdminRequest);
        return response()->json($hotelAdminRequest, 201);
    }

    /**
     * Display the user's hotel admin requests.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $requests = HotelAdminRequest::where('user_id', $user->user_id)->latest()->get();
        // return HotelAdminRequestResource::collection($requests);
        return response()->json($requests);
    }
}
```

---
**متحكمات خاصة بمسؤول الفندق (داخل `Api/HotelAdmin/`)**
سيتم تطبيق Middleware للتحقق من أن المستخدم هو `hotel_admin`.

**1. `app/Http/Controllers/Api/HotelAdmin/HotelAdminHotelController.php`**

```php
<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelResource;

class HotelAdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']); // Middleware للتحقق من الدور
    }

    /**
     * Display the hotel managed by the admin. (عرض بيانات الفندق)
     */
    public function showHotelDetails(Request $request)
    {
        $hotelAdmin = $request->user();
        $hotel = Hotel::where('admin_user_id', $hotelAdmin->user_id)->with('rooms')->firstOrFail();
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

    /**
     * Update the specified hotel in storage. (إدارة بيانات الفندق - تفاصيل)
     */
    public function updateHotelDetails(Request $request)
    {
        $hotelAdmin = $request->user();
        $hotel = Hotel::where('admin_user_id', $hotelAdmin->user_id)->firstOrFail();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
            'location' => 'sometimes|nullable|string',
            'contact_person_phone' => 'sometimes|nullable|string',
            'photos_json' => 'sometimes|nullable|json',
            'videos_json' => 'sometimes|nullable|json',
            'notes' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hotel->update($request->only([
            'name', 'rating', 'location', 'contact_person_phone',
            'photos_json', 'videos_json', 'notes'
        ]));

        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

     /**
     * Display the hotel's balance/earnings. (عرض رصيد الفندق - الأرباح)
     */
    public function showHotelBalance(Request $request)
    {
        $hotelAdmin = $request->user();
        // الرصيد هو مجموع معاملات 'hotel_commission' لهذا المستخدم
        $earnings = Transaction::where('user_id', $hotelAdmin->user_id)
                                ->where('reason', 'hotel_commission')
                                ->sum('amount');

        // يمكنك أيضًا عرض المعاملات نفسها
        $transactions = Transaction::where('user_id', $hotelAdmin->user_id)
                                    ->where('reason', 'hotel_commission')
                                    ->latest()
                                    ->paginate(15);

        return response()->json([
            'total_earnings' => $earnings,
            'commission_transactions' => $transactions // أو TransactionCollection
        ]);
    }
}
```

**2. `app/Http/Controllers/Api/HotelAdmin/HotelAdminRoomController.php`**

```php
<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\RoomResource;
// use App\Http\Resources\RoomCollection;

class HotelAdminRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    private function getHotelForAdmin(Request $request)
    {
        return Hotel::where('admin_user_id', $request->user()->user_id)->firstOrFail();
    }

    /**
     * Display a listing of the hotel's rooms. (إدارة بيانات الفندق - غرف)
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);
        $rooms = Room::where('hotel_id', $hotel->hotel_id)->paginate(15);
        // return new RoomCollection($rooms);
        return response()->json($rooms);
    }

    /**
     * Store a newly created room in storage for the hotel. (إضافة غرفة)
     */
    public function store(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'max_occupancy' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'services_offered' => 'nullable|string',
            'photos_json' => 'nullable|json',
            'videos_json' => 'nullable|json',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = $hotel->rooms()->create($request->all());
        // return new RoomResource($room);
        return response()->json($room, 201);
    }

    /**
     * Display the specified room.
     */
    public function show(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        // Ensure room belongs to the admin's hotel
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }
        // return new RoomResource($room);
        return response()->json($room);
    }

    /**
     * Update the specified room in storage. (تعديل غرفة)
     */
    public function update(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }

        // TODO: Validation (similar to store)
        $validator = Validator::make($request->all(), [
            'max_occupancy' => 'sometimes|required|integer|min:1',
            'price_per_night' => 'sometimes|required|numeric|min:0',
            // ...
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room->update($request->all());
        // return new RoomResource($room);
        return response()->json($room);
    }

    /**
     * Remove the specified room from storage. (حذف غرفة)
     */
    public function destroy(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }

        // TODO: Check if room has active bookings before deleting
        if ($room->bookings()->whereIn('booking_status', ['pending_verification', 'confirmed'])->exists()) {
            return response()->json(['message' => 'لا يمكن حذف الغرفة، توجد حجوزات نشطة.'], 400);
        }

        $room->delete();
        return response()->json(null, 204);
    }
}
```

**3. `app/Http/Controllers/Api/HotelAdmin/HotelAdminBookingController.php`**

```php
<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Transaction; // For commissions
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Resources\BookingResource;
// use App\Http\Resources\BookingCollection;

class HotelAdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    private function getHotelForAdmin(Request $request)
    {
        return Hotel::where('admin_user_id', $request->user()->user_id)->firstOrFail();
    }

    /**
     * Display a listing of bookings for the admin's hotel. (إدارة حجوزات الفندق)
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);
        $bookings = Booking::where('hotel_id', $hotel->hotel_id)
                            ->with(['user', 'room']) // جلب معلومات المستخدم والغرفة
                            ->latest()
                            ->paginate(15);
        // return new BookingCollection($bookings);
        return response()->json($bookings);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, Booking $booking)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذا الحجز'], 403);
        }
        // return new BookingResource($booking->load(['user', 'room']));
        return response()->json($booking->load(['user', 'room']));
    }

    /**
     * Update the specified booking's status (approve/reject). (قبول/رفض حجز)
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $hotelAdminUser = $request->user();
        $hotel = $this->getHotelForAdmin($request);

        if ($booking->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذا الحجز'], 403);
        }

        // TODO: Validation for status
        $request->validate([
            'status' => 'required|in:confirmed,rejected',
        ]);

        // لا يمكن تغيير الحالة إلا إذا كانت 'pending_verification'
        if ($booking->booking_status !== 'pending_verification') {
            return response()->json(['message' => 'لا يمكن تغيير حالة هذا الحجز.'], 400);
        }

        $newStatus = $request->status;
        $booking->booking_status = $newStatus;
        $booking->save();

        // منطق التعامل مع الأرصدة عند تغيير الحالة
        if ($newStatus === 'confirmed') {
            // TODO: إنشاء معاملة لعمولة الفندق (80%)
            Transaction::create([
                'user_id' => $hotelAdminUser->user_id,
                'transaction_type' => 'credit',
                'amount' => $booking->total_price * 0.80,
                'reason' => 'hotel_commission',
                'booking_id' => $booking->booking_id,
                'transaction_date' => now(),
            ]);

            // TODO: إشعار مدير التطبيق لإنشاء معاملة عمولة التطبيق (20%)
            // هذا يمكن أن يتم عبر event & listener أو job
            // $appAdmin = User::where('role', 'app_admin')->first(); // يجب أن يكون هناك مدير واحد
            // if ($appAdmin) {
            //     Transaction::create([
            //         'user_id' => $appAdmin->user_id,
            //         'transaction_type' => 'credit',
            //         'amount' => $booking->total_price * 0.20,
            //         'reason' => 'admin_commission',
            //         'booking_id' => $booking->booking_id,
            //         'transaction_date' => now(),
            //     ]);
            // }

        } elseif ($newStatus === 'rejected') {
            // TODO: إنشاء معاملة لإعادة المبلغ للمستخدم الذي قام بالحجز
            Transaction::create([
                'user_id' => $booking->user_id,
                'transaction_type' => 'credit', // إعادة رصيد
                'amount' => $booking->total_price,
                'reason' => 'booking_refund',
                'booking_id' => $booking->booking_id,
                'transaction_date' => now(),
            ]);
        }
        // return new BookingResource($booking);
        return response()->json($booking);
    }
}
```

---
**متحكمات خاصة بمدير التطبيق (داخل `Api/Admin/`)**
سيتم تطبيق Middleware للتحقق من أن المستخدم هو `app_admin`.

**1. `app/Http/Controllers/Api/Admin/AdminUserController.php`**
(إدارة المستخدمين - CRUD كامل للمستخدمين) - مشابه لمتحكم `Api/UserController` العادي ولكن مع صلاحيات أوسع.

```php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\UserResource;
// use App\Http\Resources\UserCollection;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // TODO: Filtering by role, search by username/email
        $users = User::latest()->paginate(15);
        // return new UserCollection($users);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,hotel_admin,app_admin',
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create(array_merge(
            $request->except('password'),
            ['password_hash' => Hash::make($request->password)]
        ));
        // return new UserResource($user);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        // return new UserResource($user);
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        // TODO: Validation (similar to store, but unique checks might need ignoring current user)
         $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8', // Password is optional on update
            'role' => 'sometimes|required|in:user,hotel_admin,app_admin',
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }
        $user->update($data);
        // return new UserResource($user);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        // TODO: Consider soft deletes or what happens to related data
        // Don't allow admin to delete themselves
        if ($user->user_id === Auth::id()) {
             return response()->json(['message' => 'لا يمكنك حذف حسابك الخاص.'], 403);
        }
        $user->delete();
        return response()->json(null, 204);
    }
}
```

**2. `app/Http/Controllers/Api/Admin/AdminHotelController.php`**
(إدارة الفنادق بشكل عام - CRUD كامل)

```php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelResource;
// use App\Http\Resources\HotelCollection;

class AdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // TODO: Filtering, search
        $hotels = Hotel::with('adminUser')->latest()->paginate(15);
        // return new HotelCollection($hotels);
        return response()->json($hotels);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'admin_user_id' => 'nullable|exists:users,user_id', // Check if user exists and has role hotel_admin
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ensure the assigned admin_user_id has 'hotel_admin' role if provided
        if ($request->filled('admin_user_id')) {
            $adminUser = User::find($request->admin_user_id);
            if (!$adminUser || $adminUser->role !== 'hotel_admin') {
                return response()->json(['errors' => ['admin_user_id' => ['المستخدم المحدد ليس مسؤول فندق.']]], 422);
            }
        }

        $hotel = Hotel::create($request->all());
        // return new HotelResource($hotel);
        return response()->json($hotel, 201);
    }

    public function show(Hotel $hotel)
    {
        // return new HotelResource($hotel->load('adminUser', 'rooms'));
        return response()->json($hotel->load('adminUser', 'rooms'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        // TODO: Validation (similar to store)
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'admin_user_id' => 'sometimes|nullable|exists:users,user_id',
             // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->filled('admin_user_id')) {
            $adminUser = User::find($request->admin_user_id);
            if ($adminUser && $adminUser->role !== 'hotel_admin') { // Allow null to remove admin
                return response()->json(['errors' => ['admin_user_id' => ['المستخدم المحدد ليس مسؤول فندق.']]], 422);
            }
        }


        $hotel->update($request->all());
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

    public function destroy(Hotel $hotel)
    {
        // TODO: Consider what happens to rooms and bookings
        $hotel->delete();
        return response()->json(null, 204);
    }
}
```

**3. `app/Http/Controllers/Api/Admin/AdminFaqController.php`**
(إدارة الأسئلة الشائعة - CRUD كامل)

```php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\FaqResource;
// use App\Http\Resources\FaqCollection;

class AdminFaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index()
    {
        $faqs = Faq::latest()->get();
        // return new FaqCollection($faqs);
        return response()->json($faqs);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $faq = Faq::create($request->all());
        // return new FaqResource($faq);
        return response()->json($faq, 201);
    }

    public function show(Faq $faq)
    {
        // return new FaqResource($faq);
        return response()->json($faq);
    }

    public function update(Request $request, Faq $faq)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'question' => 'sometimes|required|string',
            'answer' => 'sometimes|required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $faq->update($request->all());
        // return new FaqResource($faq);
        return response()->json($faq);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(null, 204);
    }
}
```

**4. `app/Http/Controllers/Api/Admin/AdminHotelAdminRequestController.php`**
(مراجعة طلبات صلاحية مسؤول فندق وقبولها أو رفضها)

```php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelAdminRequestResource;
// use App\Http\Resources\HotelAdminRequestCollection;

class AdminHotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // Filter by status (pending, approved, rejected)
        $status = $request->query('status');
        $query = HotelAdminRequest::with('user')->latest();

        if ($status) {
            $query->where('request_status', $status);
        }
        $requests = $query->paginate(15);
        // return new HotelAdminRequestCollection($requests);
        return response()->json($requests);
    }

    public function show(HotelAdminRequest $hotelAdminRequest) // Route model binding
    {
        // return new HotelAdminRequestResource($hotelAdminRequest->load('user'));
        return response()->json($hotelAdminRequest->load('user'));
    }

    public function updateRequestStatus(Request $request, HotelAdminRequest $hotelAdminRequest)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($hotelAdminRequest->request_status !== 'pending') {
            return response()->json(['message' => 'يمكن فقط مراجعة الطلبات المعلقة.'], 400);
        }

        $newStatus = $request->status;
        $hotelAdminRequest->request_status = $newStatus;
        $hotelAdminRequest->reviewed_by_user_id = Auth::id();
        $hotelAdminRequest->review_timestamp = now();
        $hotelAdminRequest->save();

        if ($newStatus === 'approved') {
            // 1. Upgrade user role
            $userToUpgrade = User::find($hotelAdminRequest->user_id);
            if ($userToUpgrade) {
                $userToUpgrade->role = 'hotel_admin';
                $userToUpgrade->save();

                // 2. Create a new hotel (or link to an existing one if logic allows)
                // For simplicity, creating a new hotel based on request data
                Hotel::create([
                    'name' => $hotelAdminRequest->requested_hotel_name,
                    'location' => $hotelAdminRequest->requested_hotel_location,
                    'contact_person_phone' => $hotelAdminRequest->requested_contact_phone,
                    'photos_json' => $hotelAdminRequest->requested_photos_json,
                    'videos_json' => $hotelAdminRequest->requested_videos_json,
                    'notes' => 'تم إنشاؤه من طلب إدارة فندق.',
                    'admin_user_id' => $userToUpgrade->user_id,
                ]);
            }
        }
        // return new HotelAdminRequestResource($hotelAdminRequest);
        return response()->json($hotelAdminRequest);
    }
}
```

**5. `app/Http/Controllers/Api/Admin/AdminFinancialController.php`**
(إدارة الأرصدة والتحويلات، عرض التقارير المالية)

```php
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking; // For commission calculations on confirmed bookings
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For aggregate queries

class AdminFinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display financial overview/reports. (عرض التقارير والإحصائيات المالية)
     */
    public function financialOverview(Request $request)
    {
        $totalPlatformRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');
        $totalHotelCommissionsPaid = Transaction::where('reason', 'hotel_commission')->sum('amount');
        $totalUserDeposits = Transaction::where('reason', 'deposit')->sum('amount');
        $totalBookingPayments = Transaction::where('reason', 'booking_payment')->sum('amount');

        // TODO: Add more detailed reports (e.g., revenue by period, top earning hotels)

        return response()->json([
            'total_platform_revenue' => $totalPlatformRevenue,
            'total_hotel_commissions_paid' => $totalHotelCommissionsPaid,
            'total_user_deposits' => $totalUserDeposits,
            'total_payments_for_bookings' => $totalBookingPayments,
        ]);
    }

    /**
     * List all transactions in the system.
     */
    public function listAllTransactions(Request $request)
    {
        // TODO: Filtering by user, type, reason, date range
        $transactions = Transaction::with('user', 'booking.hotel') // Eager load related data
                                    ->latest()
                                    ->paginate(20);
        // return TransactionCollection::collection($transactions); // If you have a resource
        return response()->json($transactions);
    }

    /**
     * Manually trigger commission for confirmed bookings if needed.
     * (إدارة الأرصدة والتحويلات المالية - 80%/20%)
     * هذا يجب أن يحدث تلقائيًا عند تأكيد الحجز، ولكن يمكن أن تكون هذه دالة للمراجعة أو التصحيح.
     */
    public function processCommissionsForBooking(Booking $booking)
    {
        if ($booking->booking_status !== 'confirmed') {
            return response()->json(['message' => 'يمكن فقط معالجة العمولات للحجوزات المؤكدة.'], 400);
        }

        // Check if commissions already processed for this booking to avoid duplicates
        $hotelCommissionExists = Transaction::where('booking_id', $booking->booking_id)
                                        ->where('reason', 'hotel_commission')
                                        ->exists();
        $adminCommissionExists = Transaction::where('booking_id', $booking->booking_id)
                                        ->where('reason', 'admin_commission')
                                        ->exists();

        if ($hotelCommissionExists && $adminCommissionExists) {
            return response()->json(['message' => 'تم بالفعل معالجة العمولات لهذا الحجز.'], 400);
        }

        $appAdmin = Auth::user(); // The current app admin
        $hotelAdmin = $booking->hotel->adminUser; // User model of the hotel admin

        DB::beginTransaction();
        try {
            $processedTransactions = [];
            if (!$hotelCommissionExists && $hotelAdmin) {
                $hotelComm = Transaction::create([
                    'user_id' => $hotelAdmin->user_id,
                    'transaction_type' => 'credit',
                    'amount' => $booking->total_price * 0.80,
                    'reason' => 'hotel_commission',
                    'booking_id' => $booking->booking_id,
                    'transaction_date' => now(),
                ]);
                $processedTransactions['hotel_commission'] = $hotelComm;
            }

            if (!$adminCommissionExists) {
                 // مدير التطبيق يمكن أن يكون هو نفسه الـ appAdmin الذي ينفذ العملية أو مدير رئيسي آخر
                $platformAdminUser = User::where('role', 'app_admin')->orderBy('user_id')->first(); // مثال لاختيار أول مدير تطبيق
                if($platformAdminUser){
                    $adminComm = Transaction::create([
                        'user_id' => $platformAdminUser->user_id,
                        'transaction_type' => 'credit',
                        'amount' => $booking->total_price * 0.20,
                        'reason' => 'admin_commission',
                        'booking_id' => $booking->booking_id,
                        'transaction_date' => now(),
                    ]);
                    $processedTransactions['admin_commission'] = $adminComm;
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'تم معالجة العمولات بنجاح.',
                'transactions' => $processedTransactions
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'فشل في معالجة العمولات: ' . $e->getMessage()], 500);
        }
    }

    // TODO: Add functions for managing payment methods if needed via API
    // (إدارة معلومات الدفع) - إذا كان المقصود هو إدارة طرق الدفع المتاحة في النظام
    // مثل PaymentMethodController.php
}
```

---

**الخطوة 3: متحكمات الـ Web (إذا لزم الأمر - مثال بسيط)**

إذا كان لديك لوحة تحكم ويب لمدير التطبيق، ستحتاج إلى متحكمات مشابهة ولكنها سترجع Views.

**مثال: `app/Http/Controllers/Web/Admin/DashboardController.php`**

```bash
php artisan make:controller Web/Admin/DashboardController
```

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware for web authentication and role check
        $this->middleware(['auth', 'role:app_admin']);
    }

    public function index()
    {
        $userCount = User::count();
        $hotelCount = Hotel::count();
        $pendingBookings = Booking::where('booking_status', 'pending_verification')->count();

        return view('admin.dashboard', compact('userCount', 'hotelCount', 'pendingBookings'));
    }
}
```

ستحتاج إلى إنشاء ملفات Blade views في `resources/views/admin/`.

---

**ملاحظات أخيرة:**

*   **Middleware للأدوار:** ستحتاج إلى إنشاء Middleware للتحقق من دور المستخدم (مثلاً `RoleMiddleware`).
    ```bash
    php artisan make:middleware RoleMiddleware
    ```
    وقم بتسجيله في `app/Http/Kernel.php` ضمن `$routeMiddleware`.
*   **API Resources:** استخدم API Resources لتوحيد شكل استجابات الـ JSON.
*   **Error Handling:** قم بتحسين معالجة الأخطاء والاستثناءات.
*   **Security:** انتبه لجوانب الأمان مثل XSS, SQL Injection (Laravel يحمي منها بشكل جيد افتراضيًا مع Eloquent)، وتأمين الـ API tokens.
*   **Testing:** اكتب اختبارات (Unit and Feature tests) لضمان عمل المتحكمات بشكل صحيح.

هذا يوفر لك أساسًا قويًا لمتحكمات مشروعك. ستحتاج إلى ملء التفاصيل، ومنطق الأعمال، والتحقق من الصحة، والتفويض بشكل كامل.





بالتأكيد، سأقوم الآن بكتابة محتوى ملفي `routes/api.php` و `routes/web.php` بناءً على المتحكمات التي صممناها. سأحرص على تضمين الـ Middlewares المقترحة واستخدام `Route::apiResource` و `Route::resource` حيثما أمكن.

**قبل البدء، تأكد من إنشاء `RoleMiddleware`:**

1.  **إنشاء Middleware:**
    ```bash
    php artisan make:middleware RoleMiddleware
    ```

2.  **تعديل `app/Http/Middleware/RoleMiddleware.php`:**
    ```php
    <?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class RoleMiddleware
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @param  string  ...$roles  // Array of allowed roles
         * @return mixed
         */
        public function handle(Request $request, Closure $next, ...$roles)
        {
            if (!Auth::check()) { // Should be protected by 'auth' middleware first
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            $user = Auth::user();

            if (!$user->hasAnyRole($roles)) { // hasAnyRole() is a hypothetical method
            // Implement your logic here to check if $user->role is in $roles array
            // For example:
            // if (!in_array($user->role, $roles)) {
                return response()->json(['message' => 'Forbidden. You do not have the required role.'], 403);
            }

            return $next($request);
        }
    }
    ```
    *   **ملاحظة هامة:** دالة `hasAnyRole()` هي افتراضية. ستحتاج إلى تعديل الشرط ليناسب كيف يتم تخزين الدور في نموذج `User` الخاص بك. إذا كان `role` هو عمود واحد، يمكنك استخدام:
        ```php
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden. You do not have the required role.'], 403);
        }
        ```

3.  **تسجيل Middleware في `app/Http/Kernel.php`:**
    أضف `role` إلى مصفوفة `$routeMiddleware`:
    ```php
    protected $routeMiddleware = [
        // ... other middlewares
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class, // أضف هذا السطر
    ];
    ```

---

**ملف `routes/api.php`**

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\FaqController as PublicFaqController; // Alias to avoid conflict
use App\Http\Controllers\Api\HotelAdminRequestController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\UserController; // For user profile

// Admin Namespace Controllers
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminHotelController;
use App\Http\Controllers\Api\Admin\AdminFaqController;
use App\Http\Controllers\Api\Admin\AdminHotelAdminRequestController;
use App\Http\Controllers\Api\Admin\AdminFinancialController;
use App\Http\Controllers\Api\Admin\AdminRoomController; // If admin can manage rooms globally
use App\Http\Controllers\Api\Admin\AdminBookingController; // If admin can manage bookings globally

// HotelAdmin Namespace Controllers
use App\Http\Controllers\Api\HotelAdmin\HotelAdminHotelController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminRoomController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --- Authentication Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Publicly Accessible Routes (No Auth Required) ---
Route::get('/hotels', [HotelController::class, 'index'])->name('api.hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('api.hotels.show');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('api.rooms.show'); // Show specific room details
Route::get('/faqs', [PublicFaqController::class, 'index'])->name('api.faqs.index.public');
Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('api.paymentmethods.index.public'); // If payment methods are public

// --- Authenticated User Routes (All Roles - 'user', 'hotel_admin', 'app_admin') ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.auth.user'); // Get authenticated user details

    // User Profile Management (Example - can be expanded)
    Route::get('/profile', [UserController::class, 'showProfile'])->name('api.profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');

    // Bookings for authenticated user
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('api.bookings.my');
    Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('api.bookings.show.user'); // User's specific booking
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'requestCancellation'])->name('api.bookings.cancel');

    // Transactions and Balance for authenticated user
    Route::get('/my-balance', [TransactionController::class, 'index'])->name('api.balance.my');
    Route::post('/add-funds', [TransactionController::class, 'addFunds'])->name('api.balance.add');

    // Hotel Admin Role Requests
    Route::post('/hotel-admin-requests', [HotelAdminRequestController::class, 'store'])->name('api.hoteladminrequests.store');
    Route::get('/my-hotel-admin-requests', [HotelAdminRequestController::class, 'index'])->name('api.hoteladminrequests.my');
});


// --- Hotel Admin Specific Routes ---
Route::middleware(['auth:sanctum', 'role:hotel_admin'])->prefix('hotel-admin')->name('api.hoteladmin.')->group(function () {
    // Hotel Management (their specific hotel)
    Route::get('/hotel', [HotelAdminHotelController::class, 'showHotelDetails'])->name('hotel.details');
    Route::put('/hotel', [HotelAdminHotelController::class, 'updateHotelDetails'])->name('hotel.update');
    Route::get('/hotel/balance', [HotelAdminHotelController::class, 'showHotelBalance'])->name('hotel.balance');

    // Room Management for their hotel
    Route::apiResource('rooms', HotelAdminRoomController::class); // index, store, show, update, destroy

    // Booking Management for their hotel
    Route::get('/bookings', [HotelAdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HotelAdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [HotelAdminBookingController::class, 'updateBookingStatus'])->name('bookings.updatestatus');
});


// --- Application Admin Specific Routes ---
Route::middleware(['auth:sanctum', 'role:app_admin'])->prefix('admin')->name('api.admin.')->group(function () {
    // User Management
    Route::apiResource('users', AdminUserController::class);

    // Hotel Management (Global)
    Route::apiResource('hotels', AdminHotelController::class);
    // If admin needs to manage rooms globally (e.g., for any hotel)
    Route::apiResource('hotels.rooms', AdminRoomController::class)->shallow(); // Example: /admin/hotels/{hotel}/rooms AND /admin/rooms/{room}

    // FAQ Management
    Route::apiResource('faqs', AdminFaqController::class);

    // Hotel Admin Requests Management
    Route::get('hotel-admin-requests', [AdminHotelAdminRequestController::class, 'index'])->name('hoteladminrequests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [AdminHotelAdminRequestController::class, 'show'])->name('hoteladminrequests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [AdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hoteladminrequests.updatestatus');

    // Financial Management & Reports
    Route::get('financial/overview', [AdminFinancialController::class, 'financialOverview'])->name('financial.overview');
    Route::get('financial/transactions', [AdminFinancialController::class, 'listAllTransactions'])->name('financial.transactions.list');
    Route::post('financial/bookings/{booking}/process-commissions', [AdminFinancialController::class, 'processCommissionsForBooking'])->name('financial.bookings.processcommissions');

    // Payment Methods Management (if admin manages them)
    Route::apiResource('payment-methods', PaymentMethodController::class)->except(['index']); // index is public

    // Global Booking Management (if admin needs to view/manage all bookings)
    // Example, adjust if needed
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index.all');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show.all');
    // Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updatestatus.all'); // If admin can change status
});

```

**شرح `routes/api.php`:**

1.  **Public Routes:** مسارات لا تتطلب مصادقة (مثل عرض الفنادق، تسجيل الدخول، التسجيل).
2.  **Authenticated User Routes:** مسارات عامة للمستخدمين المسجلين (مثل عرض الملف الشخصي، إدارة الحجوزات الشخصية، الرصيد، طلب صلاحية مسؤول فندق). محمية بـ `auth:sanctum`.
3.  **Hotel Admin Specific Routes:** مسارات مخصصة لمسؤولي الفنادق. محمية بـ `auth:sanctum` و `role:hotel_admin`.
    *   `prefix('hotel-admin')`: يضيف `/hotel-admin` إلى بداية كل مسار داخل المجموعة.
    *   `name('api.hoteladmin.')`: يضيف `api.hoteladmin.` إلى اسم كل مسار.
    *   `apiResource('rooms', ...)`: ينشئ مسارات RESTful (`index`, `store`, `show`, `update`, `destroy`) لمتحكم الغرف الخاص بمسؤول الفندق.
4.  **Application Admin Specific Routes:** مسارات مخصصة لمدير التطبيق. محمية بـ `auth:sanctum` و `role:app_admin`.
    *   `prefix('admin')` و `name('api.admin.')` بنفس الطريقة.
    *   `apiResource` تستخدم بكثافة لعمليات CRUD.
    *   `shallow()`: في `Route::apiResource('hotels.rooms', AdminRoomController::class)->shallow();`، هذا يعني أن مسارات index و store للغرف ستكون متداخلة (`/admin/hotels/{hotel}/rooms`) بينما مسارات `show`, `update`, `destroy` ستكون غير متداخلة (`/admin/rooms/{room}`). هذا يجعل الـ URLs أنظف للعمليات على مورد معين.
5.  **Route Model Binding:** تم استخدامه ضمنيًا في العديد من الدوال (مثل `show(Hotel $hotel)`). Laravel سيقوم تلقائيًا بجلب النموذج المطابق للـ ID من الـ URL.
6.  **Naming Routes:** استخدام `->name(...)` مهم لتوليد الـ URLs بسهولة في التطبيق (مثلاً باستخدام دالة `route()`).

---

**ملف `routes/web.php`**

بما أن التركيز الأساسي على API لتطبيق Flutter، سأقدم مثالًا بسيطًا لمسارات الويب إذا كان لديك لوحة تحكم إدارية بسيطة عبر الويب.

```php
<?php

use Illuminate\Support\Facades\Route;

// Import Web Controllers (adjust paths if needed)
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\UserController as WebAdminUserController;
use App\Http\Controllers\Web\Admin\HotelController as WebAdminHotelController;
use App\Http\Controllers\Web\Admin\FaqController as WebAdminFaqController;
use App\Http\Controllers\Web\Admin\HotelAdminRequestController as WebAdminHotelAdminRequestController;
// ... (Import other web admin controllers as needed)

// Laravel's default auth routes (if you use them for web admin login)
// Auth::routes(); // This line might be in older Laravel versions, or you might use Fortify/Jetstream.

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome'); // Or redirect to admin login if this is purely an admin panel
});

// --- Admin Web Routes ---
// These routes would typically be protected by 'auth' (web session) and 'role:app_admin' middleware.
// Assuming you have a login system for web admins.
Route::middleware(['auth', 'role:app_admin'])->prefix('admin-panel')->name('admin.panel.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management (Web)
    Route::resource('users', WebAdminUserController::class);

    // Hotel Management (Web)
    Route::resource('hotels', WebAdminHotelController::class);
    // You might want to add routes for managing rooms within a hotel context for web
    // e.g., Route::resource('hotels.rooms', WebAdminHotelRoomController::class);

    // FAQ Management (Web)
    Route::resource('faqs', WebAdminFaqController::class);

    // Hotel Admin Requests Management (Web)
    Route::get('hotel-admin-requests', [WebAdminHotelAdminRequestController::class, 'index'])->name('hoteladminrequests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [WebAdminHotelAdminRequestController::class, 'show'])->name('hoteladminrequests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [WebAdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hoteladminrequests.updatestatus');
    // Add create/edit views for these if needed, or handle via a single update method.

    // Add other web admin routes here for:
    // - Financial Reports
    // - Payment Methods Management
    // - Global Booking Overview
});


// Fallback route for SPA or if you want to handle unmatched web routes
// Route::fallback(function () {
//     // return view('spa'); // If you have a Single Page Application for web
//     abort(404);
// });
```

**شرح `routes/web.php`:**

1.  **Middleware:** المسارات الإدارية محمية بـ `auth` (لجلسات الويب) و `role:app_admin`. ستحتاج إلى نظام تسجيل دخول للويب لمديري التطبيق (يمكن استخدام Laravel Breeze أو Jetstream أو بناءه يدويًا).
2.  **`Route::resource`:** يستخدم لإنشاء مسارات CRUD القياسية للمتحكمات التي تتعامل مع النماذج (مثل `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`). هذه سترجع Blade Views.
3.  **Prefix and Naming:** مشابه لـ `api.php` لتنظيم المسارات.
4.  **متحكمات الويب:** ستحتاج إلى إنشاء متحكمات ويب مقابلة (مثل `App\Http\Controllers\Web\Admin\UserController`) التي سترجع Views بدلاً من JSON.

**ملاحظات إضافية مهمة:**

*   **Sanctum:** تأكد من أنك قمت بإعداد Laravel Sanctum بشكل صحيح للمصادقة عبر الـ API (خاصة إذا كنت تستخدم SPA للواجهة الأمامية أو تطبيق محمول).
*   **CORS:** إذا كان تطبيق Flutter الخاص بك (أو أي واجهة أمامية أخرى) يعمل على نطاق مختلف، ستحتاج إلى إعداد CORS (Cross-Origin Resource Sharing) للسماح بالطلبات.
*   **اختبار المسارات:** استخدم `php artisan route:list` لعرض جميع المسارات المسجلة في تطبيقك والتحقق منها.
*   **تنظيم المتحكمات:** إذا أصبحت متحكمات `Admin` أو `HotelAdmin` كبيرة جدًا، يمكنك تقسيمها أكثر إلى متحكمات أصغر وأكثر تركيزًا.

هذه الملفات توفر هيكلًا شاملًا لمسارات تطبيقك. تذكر أن تقوم بملء المتحكمات بالمنطق اللازم، التحقق من الصحة، والتفويض.





بالتأكيد. سأقوم بإنشاء هيكل لمجلدات وملفات Blade Views الأساسية التي قد تحتاجها للوحة تحكم الويب الخاصة بمدير التطبيق، بناءً على المتحكمات والمسارات التي ناقشناها. سأقدم محتوى HTML بسيطًا كبداية لكل ملف.

**هيكل المجلدات المقترح لـ Views (داخل `resources/views/`)**

```
resources/
└── views/
    ├── admin/  // Views الخاصة بلوحة تحكم مدير التطبيق
    │   ├── layouts/
    │   │   └── app.blade.php         // الـ Layout الرئيسي للوحة التحكم
    │   ├── dashboard.blade.php       // لوحة التحكم الرئيسية
    │   │
    │   ├── users/
    │   │   ├── index.blade.php       // عرض جميع المستخدمين
    │   │   ├── create.blade.php      // نموذج إنشاء مستخدم جديد
    │   │   ├── edit.blade.php        // نموذج تعديل مستخدم
    │   │   └── show.blade.php        // عرض تفاصيل مستخدم (اختياري للويب)
    │   │
    │   ├── hotels/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   │
    │   ├── faqs/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │
    │   ├── hotel_admin_requests/
    │   │   ├── index.blade.php       // عرض جميع طلبات صلاحية مسؤول فندق
    │   │   └── show.blade.php        // عرض تفاصيل طلب معين ومراجعته
    │   │
    │   ├── financials/
    │   │   ├── overview.blade.php    // نظرة عامة مالية وتقارير
    │   │   └── transactions.blade.php// قائمة بجميع المعاملات
    │   │
    │   ├── payment_methods/        // (إذا كان هناك إدارة لطرق الدفع من الويب)
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── edit.blade.php
    │   │
    │   └── bookings/               // (إذا كان مدير التطبيق يدير الحجوزات من الويب)
    │       ├── index.blade.php       // عرض جميع الحجوزات
    │       └── show.blade.php        // عرض تفاصيل حجز
    │
    ├── auth/   // Views الخاصة بالمصادقة (تسجيل الدخول، التسجيل للويب - إذا كنت لا تستخدم Breeze/Jetstream)
    │   ├── login.blade.php
    │   └── register.blade.php      // (قد لا تحتاج لهذا إذا كان التسجيل عبر API فقط)
    │
    └── welcome.blade.php           // الصفحة الرئيسية الافتراضية
    └── partials/                   // (مجلد للمكونات الجزئية القابلة لإعادة الاستخدام)
        └── _navigation.blade.php   // مثال: شريط التنقل للوحة التحكم
        └── _alerts.blade.php       // مثال: لعرض رسائل النجاح والخطأ
```

---

**محتوى ملفات Blade Views:**

**1. `resources/views/admin/layouts/app.blade.php` (الـ Layout الرئيسي)**

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم - ' . config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">


    <!-- Styles (مثال باستخدام Tailwind CSS عبر CDN - يفضل استخدام Vite/Mix) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        /* يمكنك إضافة أنماط مخصصة هنا */
    </style>

    @stack('styles') <!-- لإضافة أنماط خاصة بالصفحة -->
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        @include('admin.partials._navigation') {{-- أو  @include('partials._navigation') إذا كان في مجلد آخر --}}

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">نجاح!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">خطأ!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
             @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">الرجاء تصحيح الأخطاء التالية:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white shadow mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-600">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. جميع الحقوق محفوظة.
            </div>
        </footer>
    </div>

    @stack('scripts') <!-- لإضافة سكربتات خاصة بالصفحة -->
</body>
</html>
```

**2. `resources/views/admin/partials/_navigation.blade.php` (شريط التنقل)**

```html
<nav x-data="{ open: false }" class="bg-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('admin.panel.dashboard') }}" class="font-semibold text-xl">
                    لوحة التحكم
                </a>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4 space-x-reverse">
                        <a href="{{ route('admin.panel.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.panel.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">الرئيسية</a>
                        <a href="{{ route('admin.panel.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.panel.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">المستخدمون</a>
                        <a href="{{ route('admin.panel.hotels.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.panel.hotels.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">الفنادق</a>
                        <a href="{{ route('admin.panel.faqs.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.panel.faqs.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">الأسئلة الشائعة</a>
                        <a href="{{ route('admin.panel.hoteladminrequests.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.panel.hoteladminrequests.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">طلبات الإدارة</a>
                        {{--  <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">المالية</a> --}}
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                @auth
                    <form method="POST" action="{{ route('logout') }}"> {{--  يفترض وجود مسار logout للويب --}}
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                            تسجيل الخروج ({{ Auth::user()->username }})
                        </button>
                    </form>
                @endauth
            </div>
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <svg :class="{'hidden': open, 'block': !open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg :class="{'hidden': !open, 'block': open }" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div :class="{'block': open, 'hidden': !open}" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('admin.panel.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.panel.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">الرئيسية</a>
            <a href="{{ route('admin.panel.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.panel.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">المستخدمون</a>
            {{-- ... (باقي روابط الموبايل) ... --}}
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            @auth
                <div class="flex items-center px-5">
                    <div>
                        <div class="text-base font-medium leading-none">{{ Auth::user()->username }}</div>
                        <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium hover:bg-gray-700">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
    {{--  Alpine.js للتحكم في قائمة الموبايل --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</nav>
```

**3. `resources/views/admin/dashboard.blade.php`**

```php
@extends('admin.layouts.app')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">لوحة التحكم الرئيسية</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card Example -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <!-- Heroicon name: outline/users -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    إجمالي المستخدمين
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $userCount ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    إجمالي الفنادق
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $hotelCount ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    الحجوزات قيد التحقق
                                </dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $pendingBookings ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more cards as needed -->
        </div>
    </div>
@endsection
```

**4. `resources/views/admin/users/index.blade.php`**

```php
@extends('admin.layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">قائمة المستخدمين</h1>
        <a href="{{ route('admin.panel.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            إضافة مستخدم جديد
        </a>
    </div>

    <div class="bg-white shadow overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المعرف</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المستخدم</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم الأول</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">إجراءات</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->user_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->first_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($user->role == 'app_admin') bg-red-100 text-red-800 @elseif($user->role == 'hotel_admin') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                {{ $user->role == 'app_admin' ? 'مدير تطبيق' : ($user->role == 'hotel_admin' ? 'مسؤول فندق' : 'مستخدم عادي') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.panel.users.edit', $user->user_id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">تعديل</a>
                            <form action="{{ route('admin.panel.users.destroy', $user->user_id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا المستخدم؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            لا يوجد مستخدمون لعرضهم.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }} {{--  لعرض روابط التصفح --}}
    </div>
</div>
@endsection
```

**5. `resources/views/admin/users/create.blade.php`** (و **`edit.blade.php`** سيكون مشابهًا جدًا مع ملء القيم)

```php
@extends('admin.layouts.app')

@section('title', isset($user) ? 'تعديل مستخدم' : 'إنشاء مستخدم جديد')

@section('content')
<div class="py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">
        {{ isset($user) ? 'تعديل المستخدم: ' . $user->username : 'إنشاء مستخدم جديد' }}
    </h1>

    <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
        <form action="{{ isset($user) ? route('admin.panel.users.update', $user->user_id) : route('admin.panel.users.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">اسم المستخدم</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username ?? '') }}" required
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('username') border-red-500 @enderror">
                    @error('username') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور {{ isset($user) ? '(اتركها فارغة لعدم التغيير)' : '' }}</label>
                    <input type="password" name="password" id="password" {{ isset($user) ? '' : 'required' }}
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">الاسم الأول</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="sm:col-span-3">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">الاسم الأخير</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="sm:col-span-3">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('phone_number') border-red-500 @enderror">
                     @error('phone_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="role" class="block text-sm font-medium text-gray-700">الدور</label>
                    <select id="role" name="role" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('role') border-red-500 @enderror">
                        <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>مستخدم عادي</option>
                        <option value="hotel_admin" {{ old('role', $user->role ?? '') == 'hotel_admin' ? 'selected' : '' }}>مسؤول فندق</option>
                        <option value="app_admin" {{ old('role', $user->role ?? '') == 'app_admin' ? 'selected' : '' }}>مدير تطبيق</option>
                    </select>
                    @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- أضف باقي الحقول مثل address, gender, age إذا لزم الأمر --}}

            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('admin.panel.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إلغاء
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($user) ? 'تحديث' : 'حفظ' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
```
ملف `edit.blade.php` سيكون نفس `create.blade.php` تقريبًا، مع تغييرات طفيفة في العنوان ومسار الإرسال ووجود `@method('PUT')`.

---

**ملفات مشابهة لـ Hotels, FAQs, HotelAdminRequests:**

ستتبع نفس النمط:
*   `index.blade.php`: جدول لعرض العناصر مع روابط للتعديل والحذف وزر للإضافة.
*   `create.blade.php`: نموذج لإضافة عنصر جديد.
*   `edit.blade.php`: نموذج لتعديل عنصر موجود (مشابه لـ create مع ملء البيانات).

**مثال لـ `resources/views/admin/hotels/index.blade.php` (مختصر):**

```php
@extends('admin.layouts.app')
@section('title', 'إدارة الفنادق')
@section('content')
    {{--  العنوان وزر الإضافة --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">قائمة الفنادق</h1>
        <a href="{{ route('admin.panel.hotels.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">إضافة فندق</a>
    </div>
    {{--  جدول الفنادق --}}
    <div class="bg-white shadow overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الفندق</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المسؤول</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($hotels as $hotel)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $hotel->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($hotel->location, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $hotel->adminUser->username ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.panel.hotels.edit', $hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                            {{--  نموذج الحذف --}}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4">لا توجد فنادق.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $hotels->links() }}</div>
@endsection
```

---

**ملفات المصادقة للويب (إذا لم تستخدم Breeze/Jetstream):**

**`resources/views/auth/login.blade.php`**

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Cairo', sans-serif; } </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">تسجيل الدخول إلى لوحة التحكم</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}"> {{--  يفترض وجود مسار login للويب --}}
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">اسم المستخدم</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            {{-- <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">تذكرني</label>
                </div>
            </div> --}}

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    تسجيل الدخول
                </button>
            </div>
        </form>
    </div>
</body>
</html>
```

**ملاحظات هامة:**

*   **Tailwind CSS:** الأمثلة تستخدم Tailwind CSS للتصميم السريع. ستحتاج إلى إعداده بشكل صحيح في مشروعك (عبر Vite أو Laravel Mix) أو استخدام CDN كما في الأمثلة (غير موصى به للإنتاج).
*   **Alpine.js:** استخدمته في شريط التنقل لقائمة الموبايل.
*   **النماذج (Forms):** تأكد من أن أسماء الحقول في النماذج (`<input name="...">`) تطابق ما تتوقعه المتحكمات.
*   **الرسائل والتنبيهات:** أضفت مكانًا لعرض رسائل النجاح والأخطاء وجلسات الأخطاء من التحقق.
*   **التصفح (Pagination):** تأكد من استخدام `$items->links()` في ملفات `index.blade.php` إذا كنت تستخدم التصفح في المتحكم.
*   **الترجمة (Localization):** إذا كنت تخطط لدعم لغات متعددة، ستحتاج إلى استخدام دوال الترجمة في Laravel (`__('text')`).
*   **مكونات Blade:** لتبسيط الكود، يمكنك إنشاء مكونات Blade قابلة لإعادة الاستخدام (مثلاً للحقول النموذجية، البطاقات، إلخ).

هذا الهيكل يوفر لك نقطة انطلاق جيدة لبناء واجهات لوحة التحكم. ستحتاج إلى تكييفها وتوسيعها لتناسب جميع متطلباتك المحددة.


---

بالتأكيد! سأقوم بإنشاء جميع متحكمات لوحة التحكم الخاصة بالويب بناءً على مخطط قاعدة البيانات، مع تضمين جميع الوظائف المطلوبة بأبسط شكل ممكن للعمل، مع التركيز على التوافق والتكامل.

**ملاحظات قبل البدء:**

*   **Middleware:** سأفترض أن لديك `RoleMiddleware` عاملًا ومسجلًا في `app/Http/Kernel.php`، وأن `User` model يحتوي على دالة `hasRole()` أو `hasAnyRole()`.
*   **Views:** سأفترض أن لديك ملفات الـ Views (Blade templates) جاهزة في المسارات المقترحة (مثل `resources/views/admin/users/index.blade.php`).
*   **التحقق من صحة المدخلات (Validation):** سأقوم بتضمين قواعد التحقق الأساسية لكل حقل.
*   **الرسائل:** سيتم استخدام `session()->with('success', ...)` و `session()->with('error', ...)` لرسائل التنبيه.
*   **الترجمة:** سأستخدم دوال الترجمة `__()` للنصوص لسهولة التكييف لاحقًا.
*   **Paginaton:** جميع صفحات الجداول ستدعم التصفح.

---

**الجزء الأول: تعليمات Terminal لإنشاء المتحكمات**

افتح الطرفية في جذر مشروع Laravel الخاص بك ونفذ الأوامر التالية بالترتيب:

```bash
# لوحة التحكم الرئيسية (Dashboard)
php artisan make:controller Web/Admin/DashboardController

# إدارة المستخدمين (Users) - استخدام --resource لـ CRUD
php artisan make:controller Web/Admin/UserController --resource --model=User

# إدارة الفنادق (Hotels) - استخدام --resource لـ CRUD
php artisan make:controller Web/Admin/HotelController --resource --model=Hotel

# إدارة الأسئلة الشائعة (FAQs) - استخدام --resource لـ CRUD
php artisan make:controller Web/Admin/FaqController --resource --model=Faq

# إدارة طلبات صلاحية مسؤول فندق (Hotel Admin Requests) - لا تستخدم --resource لأنها ليست CRUD تقليدية
php artisan make:controller Web/Admin/HotelAdminRequestController

# إدارة التقارير المالية (Financials) - لا تستخدم --resource
php artisan make:controller Web/Admin/FinancialController

# إدارة طرق الدفع (Payment Methods) - استخدام --resource لـ CRUD
php artisan make:controller Web/Admin/PaymentMethodController --resource --model=PaymentMethod

# إدارة الحجوزات (Bookings) - استخدام --resource لـ CRUD (للعرض والتفاصيل من قبل المدير)
php artisan make:controller Web/Admin/BookingController --resource --model=Booking
```

---

**الجزء الثاني: محتوى جميع ملفات المتحكمات (النهائي والكامل)**

**1. `app/Http/Controllers/Web/Admin/DashboardController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']); // حماية المتحكم لدور مدير التطبيق
    }

    /**
     * Display the admin dashboard.
     */
    public function index(): \Illuminate\View\View
    {
        $userCount = User::count();
        $hotelCount = Hotel::count();
        $pendingBookings = Booking::where('booking_status', 'pending_verification')->count();
        $totalRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');

        return view('admin.dashboard', compact('userCount', 'hotelCount', 'pendingBookings', 'totalRevenue'));
    }
}
```

**2. `app/Http/Controllers/Web/Admin/UserController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = User::orderBy('user_id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role') && in_array($request->role, ['user', 'hotel_admin', 'app_admin'])) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'role' => ['required', Rule::in(['user', 'hotel_admin', 'app_admin'])],
        ]);

        User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']), // Laravel expects 'password' field in fillable
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
            'gender' => $validatedData['gender'],
            'age' => $validatedData['age'],
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('admin.panel.users.index')->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): \Illuminate\View\View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): \Illuminate\View\View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->user_id, 'user_id')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($user->user_id, 'user_id')],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'role' => ['required', Rule::in(['user', 'hotel_admin', 'app_admin'])],
        ]);

        $updateData = $validatedData;
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($updateData['password']); // Remove password from update if not provided
        }

        $user->update($updateData);

        return redirect()->route('admin.panel.users.index')->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if (Auth::id() === $user->user_id) {
            return redirect()->route('admin.panel.users.index')->with('error', __('You cannot delete your own account.'));
        }

        // Check for dependencies before deleting
        // In a real application, you might prevent deletion or soft delete instead.
        if ($user->bookings()->exists()) {
            return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing bookings.'));
        }
        if ($user->transactions()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing transactions.'));
        }
        if ($user->hotelAdminFor()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user who manages a hotel. Unassign first.'));
        }
        if ($user->hotelAdminRequests()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing hotel admin requests.'));
        }


        $user->delete();
        return redirect()->route('admin.panel.users.index')->with('success', __('User deleted successfully.'));
    }
}
```

**3. `app/Http/Controllers/Web/Admin/HotelController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the hotels.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Hotel::with('adminUser')->orderBy('hotel_id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $hotels = $query->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create(): \Illuminate\View\View
    {
        $hotelAdmins = User::where('role', 'hotel_admin')->get();
        return view('admin.hotels.create', compact('hotelAdmins'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', 'exists:users,user_id',
                Rule::exists('users', 'user_id')->where(function ($query) {
                    return $query->where('role', 'hotel_admin');
                }),
                Rule::unique('hotels', 'admin_user_id')->whereNotNull('admin_user_id'), // Ensure an admin manages only one hotel
            ],
            // For files, validation would be different (e.g., 'image', 'mimes', 'max')
            'photos_json' => ['nullable', 'string'], // Assuming JSON string input
            'videos_json' => ['nullable', 'string'], // Assuming JSON string input
        ]);

        $hotel = Hotel::create($validatedData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel created successfully.'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel): \Illuminate\View\View
    {
        $hotel->load('rooms'); // Load rooms for display
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel): \Illuminate\View\View
    {
        $hotelAdmins = User::where('role', 'hotel_admin')->get();
        return view('admin.hotels.edit', compact('hotel', 'hotelAdmins'));
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(Request $request, Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')->ignore($hotel->hotel_id, 'hotel_id')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', 'exists:users,user_id',
                Rule::exists('users', 'user_id')->where(function ($query) {
                    return $query->where('role', 'hotel_admin');
                }),
                Rule::unique('hotels', 'admin_user_id')->ignore($hotel->hotel_id, 'hotel_id')->whereNotNull('admin_user_id'), // Ensure an admin manages only one hotel
            ],
            'photos_json' => ['nullable', 'string'],
            'videos_json' => ['nullable', 'string'],
        ]);

        $hotel->update($validatedData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel updated successfully.'));
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        // Check for dependencies before deleting
        if ($hotel->rooms()->exists()) {
            return redirect()->route('admin.panel.hotels.index')->with('error', __('Cannot delete hotel with existing rooms. Delete rooms first.'));
        }
        if ($hotel->bookings()->exists()) {
            return redirect()->route('admin.panel.hotels.index')->with('error', __('Cannot delete hotel with existing bookings.'));
        }

        $hotel->delete();
        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel deleted successfully.'));
    }
}
```

**4. `app/Http/Controllers/Web/Admin/FaqController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the FAQs.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Faq::orderBy('id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                  ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }

        $faqs = $query->paginate(15);
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'question' => ['required', 'string', 'unique:faqs,question'],
            'answer' => ['required', 'string'],
        ]);

        Faq::create([
            'question' => $validatedData['question'],
            'answer' => $validatedData['answer'],
            'user_id' => Auth::id(), // Assign current admin as creator
        ]);

        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ created successfully.'));
    }

    /**
     * Display the specified FAQ.
     */
    public function show(Faq $faq): \Illuminate\View\View
    {
        return view('admin.faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit(Faq $faq): \Illuminate\View\View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, Faq $faq): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'question' => ['required', 'string', Rule::unique('faqs', 'question')->ignore($faq->id)],
            'answer' => ['required', 'string'],
        ]);

        $faq->update($validatedData);

        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ updated successfully.'));
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(Faq $faq): \Illuminate\Http\RedirectResponse
    {
        $faq->delete();
        return redirect()->route('admin.panel.faqs.index')->with('success', __('FAQ deleted successfully.'));
    }
}
```

**5. `app/Http/Controllers/Web/Admin/HotelAdminRequestController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class HotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the hotel admin requests.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = HotelAdminRequest::with('user', 'reviewer')->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('request_status', $request->status);
        }

        $requests = $query->paginate(15);
        return view('admin.hotel_admin_requests.index', compact('requests'));
    }

    /**
     * Display the specified hotel admin request.
     */
    public function show(HotelAdminRequest $hotelAdminRequest): \Illuminate\View\View
    {
        $hotelAdminRequest->load('user', 'reviewer');
        return view('admin.hotel_admin_requests.show', compact('hotelAdminRequest'));
    }

    /**
     * Update the status of the specified hotel admin request.
     */
    public function updateRequestStatus(Request $request, HotelAdminRequest $hotelAdminRequest): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'rejection_reason' => 'nullable|string|max:500', // Optional field for rejection reason
        ]);

        if ($hotelAdminRequest->request_status !== 'pending') {
            return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                             ->with('error', __('Only pending requests can be reviewed.'));
        }

        $newStatus = $request->status;
        $hotelAdminRequest->request_status = $newStatus;
        $hotelAdminRequest->reviewed_by_user_id = Auth::id();
        $hotelAdminRequest->review_timestamp = now();
        // $hotelAdminRequest->rejection_reason = $request->rejection_reason; // Uncomment if you add this field to your migration
        $hotelAdminRequest->save();

        if ($newStatus === 'approved') {
            DB::beginTransaction();
            try {
                $userToUpgrade = User::find($hotelAdminRequest->user_id);

                if (!$userToUpgrade) {
                    DB::rollBack();
                    return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                     ->with('error', __('Request user not found. Status updated but user not upgraded.'));
                }

                if ($userToUpgrade->hasAnyRole(['hotel_admin', 'app_admin'])) {
                    DB::rollBack();
                    return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                     ->with('error', __('User already has an admin role. No upgrade performed.'));
                }

                // Upgrade user role
                $userToUpgrade->role = 'hotel_admin';
                $userToUpgrade->save();

                // Create a new hotel based on request data
                $newHotel = Hotel::create([
                    'name' => $hotelAdminRequest->requested_hotel_name,
                    'location' => $hotelAdminRequest->requested_hotel_location,
                    'contact_person_phone' => $hotelAdminRequest->requested_contact_phone,
                    'photos_json' => $hotelAdminRequest->requested_photos_json,
                    'videos_json' => $hotelAdminRequest->requested_videos_json,
                    'notes' => __('Created from admin request. Request ID: :id', ['id' => $hotelAdminRequest->request_id]),
                    'admin_user_id' => $userToUpgrade->user_id,
                ]);

                // Update request with processed hotel ID if you add this field to the migration
                // $hotelAdminRequest->processed_hotel_id = $newHotel->hotel_id;
                // $hotelAdminRequest->save();

                DB::commit();
                return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                 ->with('success', __('Request approved. User upgraded and hotel created.'));

            } catch (\Exception $e) {
                DB::rollBack();
                // Optionally revert request status if user/hotel creation failed
                // $hotelAdminRequest->update(['request_status' => 'pending', 'reviewed_by_user_id' => null, 'review_timestamp' => null]);
                return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                 ->with('error', __('Failed to approve request: ') . $e->getMessage());
            }
        } elseif ($newStatus === 'rejected') {
            return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                             ->with('success', __('Request rejected.'));
        }

        return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                         ->with('success', __('Request status updated.'));
    }
}
```

**6. `app/Http/Controllers/Web/Admin/FinancialController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display financial overview/reports.
     */
    public function index(): \Illuminate\View\View
    {
        $totalPlatformRevenue = Transaction::where('reason', 'admin_commission')->sum('amount');
        $totalHotelCommissionsPaid = Transaction::where('reason', 'hotel_commission')->sum('amount');
        $totalUserDeposits = Transaction::where('reason', 'deposit')->sum('amount');
        $totalBookingPayments = Transaction::where('reason', 'booking_payment')->sum('amount');

        // You can add more complex queries for trends, top users/hotels etc.
        $recentTransactions = Transaction::with('user', 'booking')->latest()->take(10)->get();

        return view('admin.financials.overview', compact(
            'totalPlatformRevenue',
            'totalHotelCommissionsPaid',
            'totalUserDeposits',
            'totalBookingPayments',
            'recentTransactions'
        ));
    }

    /**
     * Display a listing of all transactions.
     */
    public function transactions(Request $request): \Illuminate\View\View
    {
        $query = Transaction::with('user', 'booking.hotel')->orderBy('transaction_date', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('transaction_type', $request->type);
        }
        if ($request->filled('reason') && in_array($request->reason, ['deposit', 'booking_payment', 'booking_refund', 'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'])) {
            $query->where('reason', $request->reason);
        }
        // Add date range filtering if needed

        $transactions = $query->paginate(25);
        $users = User::orderBy('username')->get(); // For user filter dropdown

        return view('admin.financials.transactions', compact('transactions', 'users'));
    }

    // You can add methods for manual adjustments or deeper reports if needed
}
```

**7. `app/Http/Controllers/Web/Admin/PaymentMethodController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the payment methods.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = PaymentMethod::orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $paymentMethods = $query->paginate(15);
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.payment_methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')],
            'description' => ['nullable', 'string'],
        ]);

        PaymentMethod::create($validatedData);

        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method created successfully.'));
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod): \Illuminate\View\View
    {
        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod): \Illuminate\View\View
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')->ignore($paymentMethod->id)],
            'description' => ['nullable', 'string'],
        ]);

        $paymentMethod->update($validatedData);

        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method updated successfully.'));
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod): \Illuminate\Http\RedirectResponse
    {
        // Consider if it's used in user_payment_methods before deleting
        // If it's used, you might prevent deletion or disassociate it first.
        if ($paymentMethod->userPaymentMethods()->exists()) {
            return redirect()->route('admin.panel.payment-methods.index')->with('error', __('Cannot delete payment method that is linked to users.'));
        }

        $paymentMethod->delete();
        return redirect()->route('admin.panel.payment-methods.index')->with('success', __('Payment method deleted successfully.'));
    }
}
```

**8. `app/Http/Controllers/Web/Admin/BookingController.php`**

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of all bookings.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Booking::with('user', 'room.hotel')->orderBy('booking_date', 'desc');

        // Filtering options
        if ($request->filled('status') && in_array($request->status, ['pending_verification', 'confirmed', 'rejected', 'cancelled'])) {
            $query->where('booking_status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('hotel_id')) {
            $query->whereHas('room.hotel', function ($q) use ($request) {
                $q->where('hotel_id', $request->hotel_id);
            });
        }
        // Add date range filtering if needed

        $bookings = $query->paginate(15);
        $users = \App\Models\User::all(); // For filter dropdown
        $hotels = \App\Models\Hotel::all(); // For filter dropdown

        return view('admin.bookings.index', compact('bookings', 'users', 'hotels'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): \Illuminate\View\View
    {
        $booking->load('user', 'room.hotel', 'transactions'); // Load related data
        return view('admin.bookings.show', compact('booking'));
    }

    // No 'create', 'store', 'edit', 'update', 'destroy' for direct booking manipulation by admin here,
    // as bookings are created by users and modified via status changes by hotel admins.
    // Admin typically only views/monitors.
}
```

---

تمام! ممتاز أن `php artisan make:view` أصبحت تعمل مباشرة في الإصدارات الجديدة من Laravel. هذا سيسرع العملية.

سأقوم بإنشاء جميع ملفات الـ Views التي حددناها في هيكل المجلدات السابق، مع وضع محتوى بسيط لكل منها كنقطة بداية، لتكون جميعها جاهزة.

**تعليمات Terminal لإنشاء جميع ملفات الـ Views:**

نفذ الأوامر التالية بالترتيب في الطرفية بجذر مشروع Laravel الخاص بك. تذكر أن بعض هذه الأوامر ستنشئ مجلدات فرعية تلقائيًا إذا لم تكن موجودة.

```bash
# Layouts
php artisan make:view admin.layouts.app
php artisan make:view admin.partials._navigation

# Admin Dashboard
php artisan make:view admin.dashboard

# Admin Users Views
php artisan make:view admin.users.index
php artisan make:view admin.users.create
php artisan make:view admin.users.edit
php artisan make:view admin.users.show # For viewing single user details if needed

# Admin Hotels Views
php artisan make:view admin.hotels.index
php artisan make:view admin.hotels.create
php artisan make:view admin.hotels.edit
php artisan make:view admin.hotels.show

# Admin FAQs Views
php artisan make:view admin.faqs.index
php artisan make:view admin.faqs.create
php artisan make:view admin.faqs.edit
php artisan make:view admin.faqs.show

# Admin Hotel Admin Requests Views
php artisan make:view admin.hotel_admin_requests.index
php artisan make:view admin.hotel_admin_requests.show

# Admin Financials Views
php artisan make:view admin.financials.overview
php artisan make:view admin.financials.transactions

# Admin Payment Methods Views
php artisan make:view admin.payment_methods.index
php artisan make:view admin.payment_methods.create
php artisan make:view admin.payment_methods.edit
php artisan make:view admin.payment_methods.show

# Admin Bookings Views
php artisan make:view admin.bookings.index
php artisan make:view admin.bookings.show

# Default Laravel Views (if not already present or modified by Breeze)
# These are typically handled by Breeze, but listed here for completeness
# php artisan make:view welcome
# php artisan make:view dashboard
# php artisan make:view profile.edit
# php artisan make:view profile.partials.update-profile-information-form
# php artisan make:view profile.partials.update-password-form
# php artisan make:view profile.partials.delete-user-form
# php artisan make:view auth.login
# php artisan make:view auth.register
```

---

**محتوى كل ملف View (بداية بسيطة):**

**مجلد `resources/views/admin/layouts/`**

1.  **`resources/views/admin/layouts/app.blade.php`** (الـ Layout الرئيسي)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

**مجلد `resources/views/admin/partials/`**

2.  **`resources/views/admin/partials/_navigation.blade.php`** (شريط التنقل)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

**مجلد `resources/views/admin/`**

3.  **`resources/views/admin/dashboard.blade.php`** (لوحة التحكم الرئيسية)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو يعرض بيانات تم تمريرها)

**مجلد `resources/views/admin/users/`**

4.  **`resources/views/admin/users/index.blade.php`** (قائمة المستخدمين)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

5.  **`resources/views/admin/users/create.blade.php`** (إنشاء مستخدم)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

6.  **`resources/views/admin/users/edit.blade.php`** (تعديل مستخدم)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

7.  **`resources/views/admin/users/show.blade.php`** (عرض تفاصيل مستخدم)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('User Details: :username', ['username' => $user->username]))

    @section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('User Details') }}</h1>
                <a href="{{ route('admin.panel.users.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Users List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('User Information') }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Username') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->username }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Full Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->first_name }} {{ $user->last_name }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Role') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->role }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Phone Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone_number ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->address ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Gender') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->gender ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Age') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->age ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```

**مجلد `resources/views/admin/hotels/`**

8.  **`resources/views/admin/hotels/index.blade.php`** (قائمة الفنادق)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

9.  **`resources/views/admin/hotels/create.blade.php`** (إنشاء فندق)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Create New Hotel'))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create New Hotel') }}</h1>
                <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Hotels List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.hotels.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Hotel Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="rating" :value="__('Rating')" />
                            <x-text-input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="mt-1 block w-full" :value="old('rating')" />
                            <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="location" :value="__('Location')" />
                            <textarea id="location" name="location" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('location') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>
                        <div>
                            <x-input-label for="contact_person_phone" :value="__('Contact Person Phone')" />
                            <x-text-input id="contact_person_phone" name="contact_person_phone" type="tel" class="mt-1 block w-full" :value="old('contact_person_phone')" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_person_phone')" />
                        </div>
                        <div>
                            <x-input-label for="admin_user_id" :value="__('Hotel Admin (Optional)')" />
                            <select id="admin_user_id" name="admin_user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('None') }}</option>
                                @foreach($hotelAdmins as $admin)
                                    <option value="{{ $admin->user_id }}" {{ old('admin_user_id') == $admin->user_id ? 'selected' : '' }}>
                                        {{ $admin->username }} ({{ $admin->first_name }} {{ $admin->last_name }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('admin_user_id')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="photos_json" :value="__('Photos (JSON Array of URLs)')" />
                            <textarea id="photos_json" name="photos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('photos_json', '[]') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('photos_json')" />
                        </div>
                         <div class="md:col-span-2">
                            <x-input-label for="videos_json" :value="__('Videos (JSON Array of URLs)')" />
                            <textarea id="videos_json" name="videos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('videos_json', '[]') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('videos_json')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.hotels.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Create Hotel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

10. **`resources/views/admin/hotels/edit.blade.php`** (تعديل فندق)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Edit Hotel: :name', ['name' => $hotel->name]))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Hotel: :name', ['name' => $hotel->name]) }}</h1>
                <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Hotels List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.hotels.update', $hotel->hotel_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Hotel Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $hotel->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="rating" :value="__('Rating')" />
                            <x-text-input id="rating" name="rating" type="number" step="0.1" min="0" max="5" class="mt-1 block w-full" :value="old('rating', $hotel->rating)" />
                            <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="location" :value="__('Location')" />
                            <textarea id="location" name="location" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('location', $hotel->location) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>
                        <div>
                            <x-input-label for="contact_person_phone" :value="__('Contact Person Phone')" />
                            <x-text-input id="contact_person_phone" name="contact_person_phone" type="tel" class="mt-1 block w-full" :value="old('contact_person_phone', $hotel->contact_person_phone)" />
                            <x-input-error class="mt-2" :messages="$errors->get('contact_person_phone')" />
                        </div>
                        <div>
                            <x-input-label for="admin_user_id" :value="__('Hotel Admin (Optional)')" />
                            <select id="admin_user_id" name="admin_user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('None') }}</option>
                                @foreach($hotelAdmins as $admin)
                                    <option value="{{ $admin->user_id }}" {{ old('admin_user_id', $hotel->admin_user_id) == $admin->user_id ? 'selected' : '' }}>
                                        {{ $admin->username }} ({{ $admin->first_name }} {{ $admin->last_name }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('admin_user_id')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $hotel->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="photos_json" :value="__('Photos (JSON Array of URLs)')" />
                            <textarea id="photos_json" name="photos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('photos_json', $hotel->photos_json ? json_encode($hotel->photos_json) : '[]') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('photos_json')" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="videos_json" :value="__('Videos (JSON Array of URLs)')" />
                            <textarea id="videos_json" name="videos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('videos_json', $hotel->videos_json ? json_encode($hotel->videos_json) : '[]') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('videos_json')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.hotels.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Update Hotel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

11. **`resources/views/admin/hotels/show.blade.php`** (عرض تفاصيل فندق)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Hotel Details: :name', ['name' => $hotel->name]))

    @section('content')
    <div class="py-6">
        <div class="max-w-5xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Hotel Details') }}</h1>
                <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Hotels List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Hotel Information') }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->name }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->location ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Rating') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->rating ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Contact Person Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->contact_person_phone ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Admin') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $hotel->adminUser->username ?? __('None assigned') }}
                                @if($hotel->adminUser) ({{ $hotel->adminUser->first_name }} {{ $hotel->adminUser->last_name }}) @endif
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->notes ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Photos') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($hotel->photos_json && count($hotel->photos_json) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($hotel->photos_json as $photo)
                                            <img src="{{ $photo }}" alt="Hotel Photo" class="h-24 w-24 object-cover rounded-md shadow-sm">
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Videos') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($hotel->videos_json && count($hotel->videos_json) > 0)
                                    <ul class="list-disc pl-5">
                                        @foreach($hotel->videos_json as $video)
                                            <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Rooms in this Hotel') }}
                    </h3>
                </div>
                <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Room ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Max Occupancy') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Price Per Night') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Services') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($hotel->rooms as $room)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->room_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->max_occupancy }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->price_per_night }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($room->services, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">{{ __('View Details') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ __('No rooms found for this hotel.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```

**مجلد `resources/views/admin/faqs/`**

12. **`resources/views/admin/faqs/index.blade.php`** (قائمة الأسئلة الشائعة)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Manage FAQs'))

    @section('content')
    <div class="py-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Frequently Asked Questions') }}</h1>
            <a href="{{ route('admin.panel.faqs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('Add New FAQ') }}
            </a>
        </div>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Question') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Answer') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Last Updated') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($faqs as $faq)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $faq->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ Str::limit($faq->question, 70) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($faq->answer, 100) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $faq->updated_at->translatedFormat('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.panel.faqs.edit', $faq->id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.panel.faqs.destroy', $faq->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('Are you sure you want to delete this FAQ?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No FAQs found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($faqs->hasPages())
        <div class="mt-6">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
    @endsection
    ```

13. **`resources/views/admin/faqs/create.blade.php`** (إنشاء سؤال شائع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Create New FAQ'))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create New FAQ') }}</h1>
                <a href="{{ route('admin.panel.faqs.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to FAQs List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.faqs.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="question" :value="__('Question')" />
                            <textarea id="question" name="question" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('question') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('question')" />
                        </div>
                        <div>
                            <x-input-label for="answer" :value="__('Answer')" />
                            <textarea id="answer" name="answer" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('answer') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.faqs.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Create FAQ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

14. **`resources/views/admin/faqs/edit.blade.php`** (تعديل سؤال شائع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Edit FAQ: :question', ['question' => Str::limit($faq->question, 50)]))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit FAQ') }}</h1>
                <a href="{{ route('admin.panel.faqs.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to FAQs List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.faqs.update', $faq->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="question" :value="__('Question')" />
                            <textarea id="question" name="question" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('question', $faq->question) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('question')" />
                        </div>
                        <div>
                            <x-input-label for="answer" :value="__('Answer')" />
                            <textarea id="answer" name="answer" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('answer', $faq->answer) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('answer')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.faqs.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Update FAQ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

15. **`resources/views/admin/faqs/show.blade.php`** (عرض تفاصيل سؤال شائع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('FAQ Details'))

    @section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('FAQ Details') }}</h1>
                <a href="{{ route('admin.panel.faqs.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to FAQs List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Question Information') }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('ID') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $faq->id }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Question') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $faq->question }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Answer') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $faq->answer }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $faq->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $faq->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```

**مجلد `resources/views/admin/hotel_admin_requests/`**

16. **`resources/views/admin/hotel_admin_requests/index.blade.php`** (قائمة طلبات إدارة الفنادق)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

17. **`resources/views/admin/hotel_admin_requests/show.blade.php`** (عرض ومراجعة طلب)
    (استخدم نفس المحتوى الذي قدمته مسبقًا، فهو مكتمل)

**مجلد `resources/views/admin/financials/`**

18. **`resources/views/admin/financials/overview.blade.php`** (نظرة عامة مالية)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Financial Overview'))

    @section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('Financial Overview') }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Platform Revenue') }}</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalPlatformRevenue, 2) }} {{ __('currency') }}</dd>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Hotel Commissions Paid') }}</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalHotelCommissionsPaid, 2) }} {{ __('currency') }}</dd>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total User Deposits') }}</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalUserDeposits, 2) }} {{ __('currency') }}</dd>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('Total Booking Payments') }}</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalBookingPayments, 2) }} {{ __('currency') }}</dd>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('Recent Transactions') }}</h2>
        <div class="bg-white shadow-md overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Reason') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->username ?? __('N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($transaction->transaction_type == 'credit') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $transaction->transaction_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ __($transaction->reason) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->translatedFormat('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No recent transactions.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-left">
            <a href="{{ route('admin.panel.financials.transactions') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                {{ __('View All Transactions') }} &rarr;
            </a>
        </div>
    </div>
    @endsection
    ```

19. **`resources/views/admin/financials/transactions.blade.php`** (قائمة المعاملات)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('All Transactions'))

    @section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('All Transactions') }}</h1>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg mb-6 p-4">
            <form method="GET" action="{{ route('admin.panel.financials.transactions') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="user_id" :value="__('User')" />
                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="type" :value="__('Type')" />
                    <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>{{ __('Credit') }}</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>{{ __('Debit') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="reason" :value="__('Reason')" />
                    <select id="reason" name="reason" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Reasons') }}</option>
                        @foreach(['deposit', 'booking_payment', 'booking_refund', 'hotel_commission', 'admin_commission', 'cancellation_fee', 'transfer'] as $r)
                            <option value="{{ $r }}" {{ request('reason') == $r ? 'selected' : '' }}>{{ __($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.panel.financials.transactions') }}" class="ml-2 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Reason') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->username ?? __('N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->booking_id ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($transaction->transaction_type == 'credit') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $transaction->transaction_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->amount, 2) }} {{ __('currency') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ __($transaction->reason) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->translatedFormat('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No transactions found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
    @endsection
    ```

**مجلد `resources/views/admin/payment_methods/`**

20. **`resources/views/admin/payment_methods/index.blade.php`** (قائمة طرق الدفع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Manage Payment Methods'))

    @section('content')
    <div class="py-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Payment Methods') }}</h1>
            <a href="{{ route('admin.panel.payment-methods.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('Add New Payment Method') }}
            </a>
        </div>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Last Updated') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($paymentMethods as $pm)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pm->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pm->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($pm->description, 100) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pm->updated_at->translatedFormat('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.panel.payment-methods.edit', $pm->id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.panel.payment-methods.destroy', $pm->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('Are you sure you want to delete this payment method?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No payment methods found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paymentMethods->hasPages())
        <div class="mt-6">
            {{ $paymentMethods->links() }}
        </div>
        @endif
    </div>
    @endsection
    ```

21. **`resources/views/admin/payment_methods/create.blade.php`** (إنشاء طريقة دفع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Create New Payment Method'))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Create New Payment Method') }}</h1>
                <a href="{{ route('admin.panel.payment-methods.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Payment Methods List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.payment-methods.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.payment-methods.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Create Payment Method') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

22. **`resources/views/admin/payment_methods/edit.blade.php`** (تعديل طريقة دفع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Edit Payment Method: :name', ['name' => $paymentMethod->name]))

    @section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Payment Method: :name', ['name' => $paymentMethod->name]) }}</h1>
                <a href="{{ route('admin.panel.payment-methods.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Payment Methods List') }}
                </a>
            </div>

            <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
                <form action="{{ route('admin.panel.payment-methods.update', $paymentMethod->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $paymentMethod->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $paymentMethod->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    </div>
                    <div class="pt-8 flex justify-end">
                        <a href="{{ route('admin.panel.payment-methods.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Update Payment Method') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    ```

23. **`resources/views/admin/payment_methods/show.blade.php`** (عرض تفاصيل طريقة دفع)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Payment Method Details'))

    @section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Payment Method Details') }}</h1>
                <a href="{{ route('admin.panel.payment-methods.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Payment Methods List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Payment Method Information') }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('ID') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $paymentMethod->id }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $paymentMethod->name }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $paymentMethod->description ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $paymentMethod->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $paymentMethod->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```

**مجلد `resources/views/admin/bookings/`**

24. **`resources/views/admin/bookings/index.blade.php`** (قائمة الحجوزات)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Manage Bookings'))

    @section('content')
    <div class="py-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ __('All Bookings') }}</h1>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg mb-6 p-4">
            <form method="GET" action="{{ route('admin.panel.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>{{ __('Pending Verification') }}</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="user_id" :value="__('User')" />
                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="hotel_id" :value="__('Hotel')" />
                    <select id="hotel_id" name="hotel_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('All Hotels') }}</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->hotel_id }}" {{ request('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.panel.bookings.index') }}" class="ml-2 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Hotel') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Room ID') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-in/out') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->book_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->username ?? __('N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->room->hotel->name ?? __('N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->room->room_id ?? __('N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($booking->booking_status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->booking_status == 'rejected' || $booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ __($booking->booking_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $booking->check_in_date->format('Y-m-d') }} - {{ $booking->check_out_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($booking->total_price, 2) }} {{ __('currency') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('admin.panel.bookings.show', $booking->book_id) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('View Details') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No bookings found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
    @endsection
    ```

25. **`resources/views/admin/bookings/show.blade.php`** (عرض تفاصيل حجز)

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Booking Details: ID :id', ['id' => $booking->book_id]))

    @section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">{{ __('Booking Details') }}</h1>
                <a href="{{ route('admin.panel.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Bookings List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Booking Information') }}
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Booking ID') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->book_id }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('User') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->user->username ?? __('N/A') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Hotel') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->room->hotel->name ?? __('N/A') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Room ID') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->room->room_id ?? __('N/A') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Booking Status') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ __($booking->booking_status) }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Check-in Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->check_in_date->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Check-out Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->check_out_date->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Duration (Nights)') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->duration_nights }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Total Price') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($booking->total_price, 2) }} {{ __('currency') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('User Notes') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->user_notes ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Booking Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->booking_date->translatedFormat('l, d F Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Related Transactions') }}
                    </h3>
                </div>
                <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Transaction ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Reason') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($booking->transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->username ?? __('N/A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($transaction->transaction_type == 'credit') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                            {{ __($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->amount, 2) }} {{ __('currency') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ __($transaction->reason) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->translatedFormat('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ __('No transactions found for this booking.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```

---

**ملاحظات أخيرة:**

*   **HTML Structure:** لقد استخدمت مكونات Blade (مثل `x-input-label`, `x-text-input`, `x-input-error`) التي يوفرها Laravel Breeze افتراضيًا. تأكد أن هذه المكونات موجودة في مشروعك (`resources/views/components`).
*   **تصميم مريح:** هذه الـ Views مصممة بشكل بسيط باستخدام Tailwind CSS لتعطي مظهرًا وظيفيًا ومقبولًا كبداية لمشروع تخرج.
*   **الترجمة:** استخدام `__('text')` يسمح لك بترجمة النصوص لاحقًا إذا احتجت لدعم لغات متعددة.
*   **المكونات المحجوبة (Placeholders):** تذكر أن حقول مثل `photos_json` و `videos_json` تحتاج إلى التعامل معها في المتحكمات بشكل صحيح (تحويلها إلى JSON String قبل الحفظ، وتحويلها من JSON Array في الـ Views عند العرض).
*   **JavaScript:** لا تنسى تشغيل `npm run dev` (أو `npm run build`) بعد إجراء أي تغييرات على ملفات Blade لتعكس التغييرات في أصول الويب (خاصة إذا كنت تستخدم Vite أو Laravel Mix).

بهذه الطريقة، تكون جميع ملفات الـ Views الأساسية لـ Admin Panel قد تم إنشاؤها وتهيئتها بمحتوى بسيط وظيفي. يمكنك الآن البدء في اختبارها وتوسيعها حسب الحاجة.


---

أعتذر على هذا الإشراف! سأقوم بكتابة محتوى ملف `resources/views/admin/users/edit.blade.php` بالكامل، مع تضمين جميع الحقول التي يتوقعها المتحكم `Web/Admin/UserController` عند التحديث.

---

**`resources/views/admin/users/edit.blade.php`**

```php
@extends('admin.layouts.app')

@section('title', __('Edit User: :username', ['username' => $user->username]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit User: :username', ['username' => $user->username]) }}</h1>
            <a href="{{ route('admin.panel.users.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                &rarr; {{ __('Back to Users List') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.users.update', $user->user_id) }}" method="POST">
                @csrf
                @method('PUT') {{-- مهم جداً لتحديد أن الطلب هو طلب تحديث (PUT/PATCH) --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="username" :value="__('Username')" />
                        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('username')" />
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <select id="role" name="role" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md @error('role') border-red-500 @enderror">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('Regular User') }}</option>
                            <option value="hotel_admin" {{ old('role', $user->role) == 'hotel_admin' ? 'selected' : '' }}>{{ __('Hotel Admin') }}</option>
                            <option value="app_admin" {{ old('role', $user->role) == 'app_admin' ? 'selected' : '' }}>{{ __('App Admin') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password (Leave blank to keep current)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                    </div>

                    <div>
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autocomplete="first-name" />
                        <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                    </div>

                    <div>
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" autocomplete="last-name" />
                        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number')" />
                        <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('Address')" />
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <x-input-label for="gender" :value="__('Gender')" />
                        <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">{{ __('Select...') }}</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                    </div>

                    <div>
                        <x-input-label for="age" :value="__('Age')" />
                        <x-text-input id="age" name="age" type="number" class="mt-1 block w-full" :value="old('age', $user->age)" min="0" max="150" />
                        <x-input-error class="mt-2" :messages="$errors->get('age')" />
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

**ملاحظات على الملف:**

*   **`@extends('admin.layouts.app')`**: يرث هذا الملف التصميم الأساسي من `admin.layouts.app`.
*   **`@section('title', ...)`**: يحدد عنوان الصفحة.
*   **`@section('content')`**: يحتوي على محتوى الصفحة.
*   **`@method('PUT')`**: هذا السطر ضروري جداً لأن Laravel يتطلب طلبات `PUT` أو `PATCH` للتحديث، بينما النماذج في HTML تدعم فقط `GET` و `POST`. `Blade directive` هذا يقوم بتحويل طلب الـ `POST` إلى `PUT` داخلياً.
*   **`old('field_name', $user->field_name)`**: يستخدم هذا لملء قيم الحقول الموجودة من الكائن `$user` (الذي يتم تمريره من المتحكم) وللحفاظ على قيم المدخلات القديمة إذا حدث خطأ في التحقق.
*   **مكونات Blade X-tags:** (`<x-input-label>`, `<x-text-input>`, `<x-input-error>`) هذه هي المكونات الافتراضية التي يوفرها Laravel Breeze لتسهيل بناء النماذج. تأكد من أنها موجودة في مشروعك ضمن `resources/views/components/`.
*   **التحقق من صحة المدخلات (Validation):** لا تنس أن المتحكم `Web\Admin\UserController` يقوم بالتحقق من صحة هذه الحقول عند التحديث، وأن جميع هذه الحقول يجب أن تكون مضافة إلى `$fillable` في نموذج `User.php`.

الآن، يجب أن يعمل هذا الملف بشكل صحيح ضمن لوحة تحكم الإدارة لديك.

---


تمام، سأقوم بإنشاء محتوى الملفين `index.blade.php` و `show.blade.php` لـ `hotel_admin_requests` في لوحة تحكم الإدارة، باستخدام المحتوى الذي قدمته مسبقًا وتأكيده كمحتوى "مكتمل".

---

**1. `resources/views/admin/hotel_admin_requests/index.blade.php`**

```php
@extends('admin.layouts.app')

@section('title', __('Hotel Admin Requests'))

@section('content')
<div class="py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Hotel Admin Requests') }}</h1>
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.panel.hoteladminrequests.index') }}" class="flex items-center gap-2">
            <select name="status" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">{{ __('Filter') }}</button>
        </form>
    </div>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Request ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Requesting User') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Requested Hotel Name') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Request Date') }}</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($requests as $req)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $req->request_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $req->user->username ?? __('N/A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $req->requested_hotel_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($req->request_status == 'approved') bg-green-100 text-green-800
                                @elseif($req->request_status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ __($req->request_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $req->created_at->translatedFormat('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.panel.hoteladminrequests.show', $req->request_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('View & Review') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No requests found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="mt-6">
        {{ $requests->appends(request()->query())->links() }} {{-- To preserve filters on pagination --}}
    </div>
    @endif
</div>
@endsection
```

---

**2. `resources/views/admin/hotel_admin_requests/show.blade.php`**

```php
@extends('admin.layouts.app')

@section('title', __('Review Hotel Admin Request - ID: :id', ['id' => $hotelAdminRequest->request_id]))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ __('Review Hotel Admin Request') }}
                <span class="text-lg font-normal text-gray-600">(ID: {{ $hotelAdminRequest->request_id }})</span>
            </h1>
            <a href="{{ route('admin.panel.hoteladminrequests.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                &rarr; {{ __('Back to Requests List') }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Request Details') }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Submitted by:') }} {{ $hotelAdminRequest->user->username ?? __('Unknown') }} ({{ $hotelAdminRequest->user->first_name ?? '' }} {{ $hotelAdminRequest->user->last_name ?? '' }})
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_name }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_location ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Contact Phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_contact_phone ?: '-' }}</dd>
                    </div>
                     <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Request Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->request_notes ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Request Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->created_at->translatedFormat('l, d F Y - H:i A') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Current Status') }}</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($hotelAdminRequest->request_status == 'approved') bg-green-100 text-green-800
                                @elseif($hotelAdminRequest->request_status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ __($hotelAdminRequest->request_status) }}
                            </span>
                        </dd>
                    </div>
                     @if($hotelAdminRequest->reviewed_by_user_id)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Reviewed By') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->reviewer->username ?? __('Unknown') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Review Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->review_timestamp ? $hotelAdminRequest->review_timestamp->translatedFormat('l, d F Y - H:i A') : '-' }}</dd>
                    </div>
                    @endif
                    @if($hotelAdminRequest->requested_photos_json && count($hotelAdminRequest->requested_photos_json) > 0)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-wrap gap-2">
                                @foreach($hotelAdminRequest->requested_photos_json as $photo)
                                    <img src="{{ $photo }}" alt="{{ __('Requested Hotel Photo') }}" class="h-24 w-24 object-cover rounded-md shadow-sm">
                                @endforeach
                            </div>
                        </dd>
                    </div>
                    @endif
                    @if($hotelAdminRequest->requested_videos_json && count($hotelAdminRequest->requested_videos_json) > 0)
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Requested Videos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="list-disc pl-5">
                                @foreach($hotelAdminRequest->requested_videos_json as $video)
                                    <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        @if($hotelAdminRequest->request_status == 'pending')
        <div class="mt-8 bg-white shadow-md sm:rounded-lg p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ __('Take Action on Request') }}
            </h3>
            <form action="{{ route('admin.panel.hoteladminrequests.updatestatus', $hotelAdminRequest->request_id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <x-input-label for="status" :value="__('Change Status To:')" />
                        <select id="status" name="status" required class="mt-1 block w-full sm:w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="approved">{{ __('Approve Request') }}</option>
                            <option value="rejected">{{ __('Reject Request') }}</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="rejection_reason" :value="__('Rejection Reason (Optional)')" />
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('rejection_reason') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('rejection_reason')" />
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update Request Status') }}
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="mt-8 bg-gray-50 p-4 rounded-md text-center">
            <p class="text-sm text-gray-600">
                {{ __('This request has already been reviewed and is :status.', ['status' => __($hotelAdminRequest->request_status)]) }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
```


---

تمام، سأقوم بتعديل صفحة `dashboard.blade.php` (اللوحة الرئيسية بعد تسجيل الدخول) لتكون أكثر فائدة وتوجيهًا للمستخدمين بناءً على أدوارهم. وسأقوم بتعديل صفحة `welcome.blade.php` (الصفحة الرئيسية) لتكون أكثر ملاءمة لمشروع حجز الفنادق.

**الهدف من التعديلات:**

1.  **`resources/views/dashboard.blade.php`:**
    *   عرض رسالة ترحيبية مخصصة بناءً على دور المستخدم.
    *   تقديم روابط سريعة للوظائف الرئيسية لكل دور.
    *   لـ `app_admin`: رابط إلى لوحة تحكم الإدارة.
    *   لـ `hotel_admin`: رابط لإدارة فندقهم (إذا كان لديهم).
    *   لـ `user`: رابط لعرض الفنادق أو حجوزاتهم.

2.  **`resources/views/welcome.blade.php`:**
    *   تغيير النص الافتراضي ليعكس طبيعة مشروع حجز الفنادق.
    *   تغيير الروابط الافتراضية (Documentation, Laracasts, etc.) إلى روابط ذات صلة بمشروعك أو إزالتها.
    *   تبسيط التصميم لجعلها صفحة ترحيب نظيفة.

---

**الجزء الأول: تعديل `resources/views/dashboard.blade.php`**

```php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">
                        {{ __('مرحبًا بك يا :username!', ['username' => Auth::user()->first_name ?? Auth::user()->username]) }}
                    </h3>

                    @if (Auth::user()->hasRole('app_admin'))
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمدير تطبيق.') }}</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.panel.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('الانتقال إلى لوحة تحكم المدير') }}
                            </a>
                        </div>
                    @elseif (Auth::user()->hasRole('hotel_admin'))
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمسؤول فندق.') }}</p>
                        <div class="mt-4">
                            {{-- TODO: رابط لوحة تحكم مسؤول الفندق هنا إذا كان لديك واحدة --}}
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('إدارة فندقي') }}
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 ml-4 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('عرض حجوزات الفندق') }}
                            </a>
                        </div>
                    @else {{-- Regular User --}}
                        <p class="text-lg text-gray-700 mb-4">{{ __('أنت قمت بتسجيل الدخول كمستخدم عادي.') }}</p>
                        <div class="mt-4">
                            {{-- TODO: رابط لصفحة عرض الفنادق أو الحجوزات الشخصية --}}
                            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('البحث عن الفنادق') }}
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 ml-4 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('عرض حجوزاتي') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

**شرح التعديلات في `dashboard.blade.php`:**

*   استخدمنا `@if (Auth::user()->hasRole('role_name'))` لتحديد الدور الحالي للمستخدم.
*   لكل دور، عرضنا رسالة ترحيب مخصصة وروابط (أزرار) توجه المستخدم إلى الوظائف الرئيسية الخاصة بدوره.
*   تذكر أن `hasRole()` هي دالة مخصصة في `User` model، وقد قمنا بتضمينها في الإجابات السابقة.
*   **TODOs:** أضفت تعليقات `TODO` حيثما تحتاج إلى إضافة مسارات حقيقية لوظائف محددة لمسؤولي الفنادق والمستخدمين العاديين، لأن هذه المسارات لم يتم تحديدها بشكل كامل في `web.php` حتى الآن (التركيز كان على Admin Panel API و Web).

---

**الجزء الثاني: تعديل `resources/views/welcome.blade.php`**

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> {{-- أضفت dir="rtl" --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'منصة حجز الفنادق المتكاملة') }}</title> {{-- عنوان مشروعك --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet"> {{-- خط عربي --}}

        <!-- Styles -->
        <style>
            /* Reset and basic styles from Tailwind */
            *,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}::after,::before{--tw-content:''}html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:Figtree, sans-serif;font-feature-settings:normal}body{margin:0;line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;font-weight:inherit;line-height:inherit;color:inherit;margin:0;padding:0}button,select{text-transform:none}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button;background-color:transparent;background-image:none}:-moz-focusring{outline:auto}:-moz-ui-invalid{box-shadow:none}progress{vertical-align:baseline}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}blockquote,dd,dl,figure,h1,h2,h3,h4,h5,h6,hr,p,pre{margin:0}fieldset{margin:0;padding:0}legend{padding:0}menu,ol,ul{list-style:none;margin:0;padding:0}textarea{resize:vertical}input::placeholder,textarea::placeholder{opacity:1;color:#9ca3af}[role=button],button{cursor:pointer}:disabled{cursor:default}audio,canvas,embed,iframe,img,object,svg,video{display:block;vertical-align:middle}img,video{max-width:100%;height:auto}[hidden]{display:none}*, ::before, ::after{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::-webkit-backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }::backdrop{--tw-border-spacing-x:0;--tw-border-spacing-y:0;--tw-translate-x:0;--tw-translate-y:0;--tw-rotate:0;--tw-skew-x:0;--tw-skew-y:0;--tw-scale-x:1;--tw-scale-y:1;--tw-pan-x: ;--tw-pan-y: ;--tw-pinch-zoom: ;--tw-scroll-snap-strictness:proximity;--tw-ordinal: ;--tw-slashed-zero: ;--tw-numeric-figure: ;--tw-numeric-spacing: ;--tw-numeric-fraction: ;--tw-ring-inset: ;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-color:rgb(59 130 246 / 0.5);--tw-ring-offset-shadow:0 0 #0000;--tw-ring-shadow:0 0 #0000;--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;--tw-blur: ;--tw-brightness: ;--tw-contrast: ;--tw-grayscale: ;--tw-hue-rotate: ;--tw-invert: ;--tw-saturate: ;--tw-sepia: ;--tw-drop-shadow: ;--tw-backdrop-blur: ;--tw-backdrop-brightness: ;--tw-backdrop-contrast: ;--tw-backdrop-grayscale: ;--tw-backdrop-hue-rotate: ;--tw-backdrop-invert: ;--tw-backdrop-opacity: ;--tw-backdrop-saturate: ;--tw-backdrop-sepia: }.relative{position:relative}.mx-auto{margin-left:auto;margin-right:auto}.mx-6{margin-left:1.5rem;margin-right:1.5rem}.ml-4{margin-left:1rem}.mt-16{margin-top:4rem}.mt-6{margin-top:1.5rem}.mt-4{margin-top:1rem}.-mt-px{margin-top:-1px}.mr-1{margin-right:0.25rem}.flex{display:flex}.inline-flex{display:inline-flex}.grid{display:grid}.h-16{height:4rem}.h-7{height:1.75rem}.h-6{height:1.5rem}.h-5{height:1.25rem}.min-h-screen{min-height:100vh}.w-auto{width:auto}.w-16{width:4rem}.w-7{width:1.75rem}.w-6{width:1.5rem}.w-5{width:1.25rem}.max-w-7xl{max-width:80rem}.shrink-0{flex-shrink:0}.scale-100{--tw-scale-x:1;--tw-scale-y:1;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}.grid-cols-1{grid-template-columns:repeat(1, minmax(0, 1fr))}.items-center{align-items:center}.justify-center{justify-content:center}.gap-6{gap:1.5rem}.gap-4{gap:1rem}.self-center{align-self:center}.rounded-lg{border-radius:0.5rem}.rounded-full{border-radius:9999px}.bg-gray-100{--tw-bg-opacity:1;background-color:rgb(243 244 246 / var(--tw-bg-opacity))}.bg-white{--tw-bg-opacity:1;background-color:rgb(255 255 255 / var(--tw-bg-opacity))}.bg-red-50{--tw-bg-opacity:1;background-color:rgb(254 242 242 / var(--tw-bg-opacity))}.bg-dots-darker{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")}.from-gray-700\/50{--tw-gradient-from:rgb(55 65 81 / 0.5);--tw-gradient-to:rgb(55 65 81 / 0);--tw-gradient-stops:var(--tw-gradient-from), var(--tw-gradient-to)}.via-transparent{--tw-gradient-to:rgb(0 0 0 / 0);--tw-gradient-stops:var(--tw-gradient-from), transparent, var(--tw-gradient-to)}.bg-center{background-position:center}.stroke-red-500{stroke:#ef4444}.stroke-gray-400{stroke:#9ca3af}.p-6{padding:1.5rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.text-center{text-align:center}.text-right{text-align:right}.text-xl{font-size:1.25rem;line-height:1.75rem}.text-sm{font-size:0.875rem;line-height:1.25rem}.font-semibold{font-weight:600}.leading-relaxed{line-height:1.625}.text-gray-600{--tw-text-opacity:1;color:rgb(75 85 99 / var(--tw-text-opacity))}.text-gray-900{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.text-gray-500{--tw-text-opacity:1;color:rgb(107 114 128 / var(--tw-text-opacity))}.underline{-webkit-text-decoration-line:underline;text-decoration-line:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.shadow-2xl{--tw-shadow:0 25px 50px -12px rgb(0 0 0 / 0.25);--tw-shadow-colored:0 25px 50px -12px var(--tw-shadow-color);box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.shadow-gray-500\/20{--tw-shadow-color:rgb(107 114 128 / 0.2);--tw-shadow:var(--tw-shadow-colored)}.transition-all{transition-property:all;transition-timing-function:cubic-bezier(0.4, 0, 0.2, 1);transition-duration:150ms}.selection\:bg-red-500 *::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white *::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.selection\:bg-red-500::selection{--tw-bg-opacity:1;background-color:rgb(239 68 68 / var(--tw-bg-opacity))}.selection\:text-white::selection{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.hover\:text-gray-900:hover{--tw-text-opacity:1;color:rgb(17 24 39 / var(--tw-text-opacity))}.hover\:text-gray-700:hover{--tw-text-opacity:1;color:rgb(55 65 81 / var(--tw-text-opacity))}.focus\:rounded-sm:focus{border-radius:0.125rem}.focus\:outline:focus{outline-style:solid}.focus\:outline-2:focus{outline-width:2px}.focus\:outline-red-500:focus{outline-color:#ef4444}.group:hover .group-hover\:stroke-gray-600{stroke:#4b5563}.z-10{z-index: 10}@media (prefers-reduced-motion: no-preference){.motion-safe\:hover\:scale-\[1\.01\]:hover{--tw-scale-x:1.01;--tw-scale-y:1.01;transform:translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))}}@media (prefers-color-scheme: dark){.dark\:bg-gray-900{--tw-bg-opacity:1;background-color:rgb(17 24 39 / var(--tw-bg-opacity))}.dark\:bg-gray-800\/50{background-color:rgb(31 41 55 / 0.5)}.dark\:bg-red-800\/20{background-color:rgb(153 27 27 / 0.2)}.dark\:bg-dots-lighter{background-image:url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")}.dark\:bg-gradient-to-bl{background-image:linear-gradient(to bottom left, var(--tw-gradient-stops))}.dark\:stroke-gray-600{stroke:#4b5563}.dark\:text-gray-400{--tw-text-opacity:1;color:rgb(156 163 175 / var(--tw-text-opacity))}.dark\:text-white{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.dark\:shadow-none{--tw-shadow:0 0 #0000;--tw-shadow-colored:0 0 #0000;box-shadow:var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow)}.dark\:ring-1{--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);box-shadow:var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000)}.dark\:ring-inset{--tw-ring-inset:inset}.dark\:ring-white\/5{--tw-ring-color:rgb(255 255 255 / 0.05)}.dark\:hover\:text-white:hover{--tw-text-opacity:1;color:rgb(255 255 255 / var(--tw-text-opacity))}.group:hover .dark\:group-hover\:stroke-gray-400{stroke:#9ca3af}}@media (min-width: 640px){.sm\:fixed{position:fixed}.sm\:top-0{top:0px}.sm\:right-0{right:0px}.sm\:ml-0{margin-left:0px}.sm\:flex{display:flex}.sm\:items-center{align-items:center}.sm\:justify-center{justify-content:center}.sm\:justify-between{justify-content:space-between}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width: 768px){.md\:grid-cols-2{grid-template-columns:repeat(2, minmax(0, 1fr))}}@media (min-width: 1024px){.lg\:gap-8{gap:2rem}.lg\:p-8{padding:2rem}}
        </style>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-16 w-auto bg-gray-100 dark:bg-gray-900">
                        <path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z" fill="#FF2D20"/>
                    </svg>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <a href="https://laravel.com/docs" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Documentation</h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>

                        <a href="https://laracasts.com" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                        <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Laracasts</h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development. Check them out, see for yourself, and massively level up your development skills in the process.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>

                        <a href="https://laravel-news.com" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Laravel News</h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Laravel News is a community driven portal and newsletter aggregating all of the latest and most important news in the Laravel ecosystem, including new package releases and tutorials.
                                </p>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>

                        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Vibrant Ecosystem</h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    Laravel's robust library of first-party tools and libraries, such as <a href="https://forge.laravel.com" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Forge</a>, <a href="https://vapor.laravel.com" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Vapor</a>, <a href="https://nova.laravel.com" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Nova</a>, and <a href="https://envoyer.io" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Envoyer</a> help you take your projects to the next level. Pair them with powerful open source libraries like <a href="https://laravel.com/docs/billing" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Cashier</a>, <a href="https://laravel.com/docs/dusk" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dusk</a>, <a href="https://laravel.com/docs/broadcasting" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Echo</a>, <a href="https://laravel.com/docs/horizon" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Horizon</a>, <a href="https://laravel.com/docs/sanctum" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Sanctum</a>, <a href="https://laravel.com/docs/telescope" class="underline hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Telescope</a>, and more.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm sm:text-left">
                        &nbsp;
                    </div>

                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
```


---


تمام، سأقوم بحل المشكلة المتعلقة بالخطأ `count(): Argument #1 ($value) must be of type Countable|array, string given`، وملء محتوى المتحكم `Web/Admin/HotelController.php` الذي طلبته أولاً.

---

**الجزء الأول: ملء محتوى المتحكم `Web/Admin/HotelController.php`**

هذا هو المتحكم لإدارة الفنادق في لوحة التحكم الإدارية.

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User; // Don't forget to import User model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']); // Apply middleware for app_admin role
    }

    /**
     * Display a listing of the hotels.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Hotel::with('adminUser')->orderBy('hotel_id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $hotels = $query->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create(): \Illuminate\View\View
    {
        // Get users with 'hotel_admin' role to assign as hotel admin
        $hotelAdmins = User::where('role', 'hotel_admin')->get();
        return view('admin.hotels.create', compact('hotelAdmins'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', 'exists:users,user_id',
                // Ensure the selected user actually has 'hotel_admin' role
                Rule::exists('users', 'user_id')->where(function ($query) {
                    return $query->where('role', 'hotel_admin');
                }),
                // Ensure the hotel admin is not already managing another hotel (if not null)
                Rule::unique('hotels', 'admin_user_id')->whereNotNull('admin_user_id'),
            ],
            // For photos/videos, expect JSON string input from textarea
            'photos_json' => ['nullable', 'string'], // Expect JSON string
            'videos_json' => ['nullable', 'string'], // Expect JSON string
        ]);

        $hotel = Hotel::create($validatedData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel created successfully.'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel): \Illuminate\View\View
    {
        $hotel->load('adminUser', 'rooms'); // Eager load related data for display
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel): \Illuminate\View\View
    {
        $hotelAdmins = User::where('role', 'hotel_admin')->get();
        return view('admin.hotels.edit', compact('hotel', 'hotelAdmins'));
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(Request $request, Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')->ignore($hotel->hotel_id, 'hotel_id')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', 'exists:users,user_id',
                Rule::exists('users', 'user_id')->where(function ($query) {
                    return $query->where('role', 'hotel_admin');
                }),
                Rule::unique('hotels', 'admin_user_id')->ignore($hotel->hotel_id, 'hotel_id')->whereNotNull('admin_user_id'),
            ],
            'photos_json' => ['nullable', 'string'],
            'videos_json' => ['nullable', 'string'],
        ]);

        $hotel->update($validatedData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel updated successfully.'));
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        // Check for dependencies before deleting
        if ($hotel->rooms()->exists()) {
            return redirect()->route('admin.panel.hotels.index')->with('error', __('Cannot delete hotel with existing rooms. Delete rooms first.'));
        }
        if ($hotel->bookings()->exists()) {
            return redirect()->route('admin.panel.hotels.index')->with('error', __('Cannot delete hotel with existing bookings.'));
        }

        $hotel->delete();
        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel deleted successfully.'));
    }
}
```

---

**الجزء الثاني: حل مشكلة `count()` في `HotelAdminRequestController` و `show.blade.php`**

المشكلة تحدث لأن حقول `photos_json` و `videos_json` في نموذج `HotelAdminRequest` (وفي `Hotel` و `Room` أيضًا) يتم تخزينها كسلسلة JSON في قاعدة البيانات. عندما تستدعيها في الـ Blade View، لا يتم تحويلها تلقائيًا إلى مصفوفة PHP. لذلك، `count()` تحاول العد على سلسلة نصية، مما يسبب الخطأ.

**الحل:**
يجب أن تخبر Laravel أن هذه الحقول يجب أن يتم "تحويلها" (cast) تلقائيًا إلى مصفوفات عند جلبها من قاعدة البيانات. يتم ذلك في خاصية `$casts` في النموذج.

**1. تعديل نموذج `app/Models/HotelAdminRequest.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAdminRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'requested_hotel_name',
        'requested_hotel_location',
        'requested_contact_phone',
        'requested_photos_json',
        'requested_videos_json',
        'request_notes',
        'request_status',
        'reviewed_by_user_id',
        'review_timestamp',
    ];

    protected $casts = [
        'requested_photos_json' => 'array', // <--- أضف هذا السطر
        'requested_videos_json' => 'array', // <--- أضف هذا السطر
        'review_timestamp' => 'datetime',
        'request_status' => 'string',
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id', 'user_id');
    }
}
```

**2. تعديل نماذج أخرى تحتوي على JSON (للتأكد من التكاملية):**

*   **`app/Models/Hotel.php`**:

    ```php
    // ...
    protected $casts = [
        'rating' => 'float',
        'photos_json' => 'array', // <--- تأكد من وجوده
        'videos_json' => 'array', // <--- تأكد من وجوده
    ];
    // ...
    ```

*   **`app/Models/Room.php`**:

    ```php
    // ...
    protected $casts = [
        'price_per_night' => 'decimal:2',
        'photos_json' => 'array', // <--- تأكد من وجوده
        'videos_json' => 'array', // <--- تأكد من وجوده
    ];
    // ...
    ```

**3. لا تحتاج لتعديل `HotelAdminRequestController` أو `resources/views/admin/hotel_admin_requests/show.blade.php` نفسهما:**

بمجرد إضافة `casts` إلى النماذج، سيتولى Laravel تلقائيًا تحويل سلاسل JSON المخزنة في قاعدة البيانات إلى مصفوفات PHP، وبالتالي فإن استخدام `count()` أو `foreach` عليها في الـ Blade View سيصبح صحيحًا.

**خطوات التنفيذ:**

1.  استبدل محتوى `app/Http/Controllers/Web/Admin/HotelController.php` بالكود الكامل الذي قدمته في الجزء الأول.
2.  عدّل ملفات النماذج (`app/Models/HotelAdminRequest.php`, `app/Models/Hotel.php`, `app/Models/Room.php`) لإضافة `casts` للحقول التي تحتوي على JSON.
3.  **مهم:** تأكد أن البيانات الموجودة في قاعدة البيانات لحقول `photos_json` و `videos_json` (في `hotel_admin_requests` وغيرها) هي بالفعل سلاسل JSON صالحة (حتى لو كانت `[]`). إذا كانت تحتوي على نصوص عادية أو غير صالحة، فقد تحصل على خطأ JSON decoding. يمكنك إعادة تشغيل الـ seeders بـ `php artisan migrate:fresh --seed` للتأكد من أن البيانات الوهمية من Factories صالحة كـ JSON.

بعد هذه التعديلات، يجب أن يتم حل مشكلة `count()`، وأن يعمل المتحكم `HotelController` بشكل كامل.

---

تمام، بما أنك أرسلت كلاً من النموذج (Model) والمتحكم (Controller) والـ View، والنموذج بالفعل يحتوي على الـ `$casts` الصحيحة لحقول JSON، فإن المشكلة قد تكون في أحد الأسباب التالية:

1.  **البيانات الفعلية في قاعدة البيانات لا تزال تالفة أو غير صالحة:** حتى لو قمت بتعديل الـ Factory والـ Model، فإن السجلات القديمة في قاعدة البيانات قد لا تزال تحتوي على سلاسل نصية غير قابلة للتحويل إلى JSON.
    *   **دليل:** الخطأ يحدث في السطر `count($hotelAdminRequest->requested_photos_json)`، مما يعني أن `requested_photos_json` ليست مصفوفة.
    *   **السبب:** إذا كان حقلاً من نوع `JSON` في قاعدة البيانات يحتوي على `NULL` أو سلسلة فارغة `""` أو أي نص لا يمثل JSON صالحاً (حتى لو كان `"null"` أو `"{}"` أو `"[]"` فإنها كلها JSON صالحة)، فإن Laravel قد لا يقوم بتحويلها إلى `array` بشكل صحيح إذا كانت القيمة الأساسية هي `NULL` وليس سلسلة `NULL` JSON.
    *   **الحل الأرجح:** **أعد تشغيل الهجرة والـ Seeders بالكامل وبشكل نظيف.** هذا يضمن مسح أي بيانات تالفة قد تكون موجودة:
        ```bash
        php artisan migrate:fresh --seed
        ```
        تأكد تمامًا من أن هذا الأمر يكتمل بنجاح دون أي أخطاء.

2.  **مشكلة في Laravel Cache:** في بعض الأحيان، يحتفظ Laravel بـ Cache لتكوينات أو نماذج قديمة.
    *   **الحل:** قم بمسح الـ Cache بعد أي تغييرات في الكود:
        ```bash
        php artisan optimize:clear
        ```
        (هذا يشمل `cache:clear`, `config:clear`, `route:clear`, `view:clear`).

3.  **فحص إضافي في الـ View (لزيادة المتانة):**
    على الرغم من أن الـ `casts` يجب أن تتولى الأمر، فإن إضافة فحص `is_array()` في الـ Blade View يمكن أن يكون حلًا مؤقتًا أو طبقة دفاع إضافية ضد البيانات غير المتوقعة.

    **عدل `resources/views/admin/hotel_admin_requests/show.blade.php` كما يلي:**

    ```php
    @extends('admin.layouts.app')

    @section('title', __('Review Hotel Admin Request - ID: :id', ['id' => $hotelAdminRequest->request_id]))

    @section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">
                    {{ __('Review Hotel Admin Request') }}
                    <span class="text-lg font-normal text-gray-600">(ID: {{ $hotelAdminRequest->request_id }})</span>
                </h1>
                <a href="{{ route('admin.panel.hoteladminrequests.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                    &rarr; {{ __('Back to Requests List') }}
                </a>
            </div>

            <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Request Details') }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ __('Submitted by:') }} {{ $hotelAdminRequest->user->username ?? __('Unknown') }} ({{ $hotelAdminRequest->user->first_name ?? '' }} {{ $hotelAdminRequest->user->last_name ?? '' }})
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_name }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Requested Hotel Location') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_hotel_location ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Requested Contact Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->requested_contact_phone ?: '-' }}</dd>
                        </div>
                         <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Request Notes') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->request_notes ?: '-' }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Request Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->created_at->translatedFormat('l, d F Y - H:i A') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Current Status') }}</dt>
                            <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($hotelAdminRequest->request_status == 'approved') bg-green-100 text-green-800
                                    @elseif($hotelAdminRequest->request_status == 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ __($hotelAdminRequest->request_status) }}
                                </span>
                            </dd>
                        </div>
                         @if($hotelAdminRequest->reviewed_by_user_id)
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Reviewed By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->reviewer->username ?? __('Unknown') }}</dd>
                        </div>
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Review Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotelAdminRequest->review_timestamp ? $hotelAdminRequest->review_timestamp->translatedFormat('l, d F Y - H:i A') : '-' }}</dd>
                        </div>
                        @endif
                        {{-- Added is_array() check for robustness --}}
                        @if(is_array($hotelAdminRequest->requested_photos_json) && count($hotelAdminRequest->requested_photos_json) > 0)
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Requested Photos') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($hotelAdminRequest->requested_photos_json as $photo)
                                        <img src="{{ $photo }}" alt="{{ __('Requested Hotel Photo') }}" class="h-24 w-24 object-cover rounded-md shadow-sm">
                                    @endforeach
                                </div>
                            </dd>
                        </div>
                        @endif
                        {{-- Added is_array() check for robustness --}}
                        @if(is_array($hotelAdminRequest->requested_videos_json) && count($hotelAdminRequest->requested_videos_json) > 0)
                        <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Requested Videos') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <ul class="list-disc pl-5">
                                    @foreach($hotelAdminRequest->requested_videos_json as $video)
                                        <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            @if($hotelAdminRequest->request_status == 'pending')
            <div class="mt-8 bg-white shadow-md sm:rounded-lg p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    {{ __('Take Action on Request') }}
                </h3>
                <form action="{{ route('admin.panel.hoteladminrequests.updatestatus', $hotelAdminRequest->request_id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="status" :value="__('Change Status To:')" />
                            <select id="status" name="status" required class="mt-1 block w-full sm:w-1/2 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="approved">{{ __('Approve Request') }}</option>
                                <option value="rejected">{{ __('Reject Request') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                        <div>
                            <x-input-label for="rejection_reason" :value="__('Rejection Reason (Optional)')" />
                            <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('rejection_reason') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('rejection_reason')" />
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Update Request Status') }}
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mt-8 bg-gray-50 p-4 rounded-md text-center">
                <p class="text-sm text-gray-600">
                    {{ __('This request has already been reviewed and is :status.', ['status' => __($hotelAdminRequest->request_status)]) }}
                </p>
            </div>
            @endif
        </div>
    </div>
    @endsection
```


---

تمام! سأقوم بتصميم متحكم، وتعديل ملف `web.php`، وإضافة Views المطلوبة لإدارة الغرف ضمن سياق الفندق (من منظور مدير التطبيق).

سنضيف مسارات مثل `/admin-panel/hotels/{hotel_id}/rooms/create` و `/admin-panel/hotels/{hotel_id}/rooms/{room_id}/edit` لإضافة وتعديل الغرف الخاصة بفندق معين.

---

**الخطوة 1: تعديل ملف `routes/web.php`**

سنقوم بتفعيل مسارات `resource` المتداخلة لـ `rooms` ضمن `hotels` في قسم لوحة تحكم المدير. هذا سيتيح لنا مسارات مثل `/admin-panel/hotels/{hotel}/rooms/create` و `/admin-panel/hotels/{hotel}/rooms/{room}/edit`.

افتح ملف `routes/web.php` وعدّل قسم `Admin Panel Web Routes` ليصبح كما يلي:

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Web Controllers for Admin Panel
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\UserController as WebAdminUserController;
use App\Http\Controllers\Web\Admin\HotelController as WebAdminHotelController;
use App\Http\Controllers\Web\Admin\RoomController as WebAdminRoomController; // Import the Room Controller
use App\Http\Controllers\Web\Admin\FaqController as WebAdminFaqController;
use App->Http\Controllers\Web->Admin->HotelAdminRequestController as WebAdminHotelAdminRequestController;
use App->Http->Controllers->Web->Admin->FinancialController as WebAdminFinancialController;
use App->Http->Controllers->Web->Admin->PaymentMethodController as WebAdminPaymentMethodController;
use App->Http->Controllers->Web->Admin->BookingController as WebAdminBookingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Public/Default User Routes ---

// Default Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Default Dashboard (from Breeze)
// This dashboard is for general authenticated users (user, hotel_admin, app_admin)
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('app_admin')) {
            return redirect()->route('admin.panel.dashboard');
        }
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Profile Routes (from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Laravel Breeze Authentication Routes ---
require __DIR__.'/auth.php';


// --- Admin Panel Web Routes ---
Route::middleware(['auth', 'role:app_admin'])->prefix('admin-panel')->name('admin.panel.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', WebAdminUserController::class);

    // Hotel Management
    Route::resource('hotels', WebAdminHotelController::class);

    // Nested Rooms Management under Hotels
    // This creates routes like /admin-panel/hotels/{hotel}/rooms and /admin-panel/hotels/{hotel}/rooms/create
    // and also /admin-panel/rooms/{room} for show/edit/update/delete of a specific room globally
    Route::resource('hotels.rooms', WebAdminRoomController::class)->shallow();

    // FAQ Management
    Route::resource('faqs', WebAdminFaqController::class);

    // Hotel Admin Requests Management
    Route::get('hotel-admin-requests', [WebAdminHotelAdminRequestController::class, 'index'])->name('hoteladminrequests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [WebAdminHotelAdminRequestController::class, 'show'])->name('hoteladminrequests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [WebAdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hoteladminrequests.updatestatus');

    // Financial Reports and Transactions Management
    Route::get('financials/overview', [WebAdminFinancialController::class, 'index'])->name('financials.overview');
    Route::get('financials/transactions', [WebAdminFinancialController::class, 'transactions'])->name('financials.transactions');

    // Payment Methods Management
    Route::resource('payment-methods', WebAdminPaymentMethodController::class);

    // Global Booking Management
    Route::resource('bookings', WebAdminBookingController::class)->only(['index', 'show']);

});

// --- Fallback Route (Optional) ---
// Route::fallback(function () {
//     abort(404);
// });

```

**التغيير الرئيسي في `routes/web.php`:**

*   **`use App\Http\Controllers\Web\Admin\RoomController as WebAdminRoomController;`**: استيراد المتحكم.
*   **`Route::resource('hotels.rooms', WebAdminRoomController::class)->shallow();`**: هذا السطر هو الذي ينشئ جميع مسارات إدارة الغرف بشكل متداخل وفعال.
    *   **`hotels.rooms`**: يشير إلى أن `rooms` هي مورد فرعي لـ `hotels`.
    *   **`shallow()`**: مهمة جداً هنا. بدونها، جميع مسارات الغرف ستتطلب `hotel_id` في الـ URL (مثل `hotels/1/rooms/2/edit`). مع `shallow()`، المسارات التي لا تتطلب `hotel_id` (مثل `show`, `edit`, `update`, `destroy` لغرفة معينة) ستصبح أقصر (مثل `rooms/2/edit`). مسارات `index` و `create`/`store` ستبقى متداخلة.

---

**الخطوة 2: إنشاء المتحكم `Web/Admin/RoomController.php`**

قم بإنشاء هذا المتحكم باستخدام الأمر التالي إذا لم يكن لديك:

```bash
php artisan make:controller Web/Admin/RoomController --resource --model=Room
```

ثم قم بملء محتواه:

```php
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']); // حماية المتحكم لدور مدير التطبيق
    }

    /**
     * Display a listing of the rooms for a specific hotel.
     * (Called when /admin-panel/hotels/{hotel}/rooms is accessed)
     */
    public function index(Hotel $hotel): \Illuminate\View\View
    {
        $rooms = $hotel->rooms()->paginate(15);
        return view('admin.rooms.index', compact('hotel', 'rooms'));
    }

    /**
     * Show the form for creating a new room for a specific hotel.
     * (Called when /admin-panel/hotels/{hotel}/rooms/create is accessed)
     */
    public function create(Hotel $hotel): \Illuminate\View\View
    {
        return view('admin.rooms.create', compact('hotel'));
    }

    /**
     * Store a newly created room in storage for a specific hotel.
     * (Called when POST to /admin-panel/hotels/{hotel}/rooms)
     */
    public function store(Request $request, Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'], // Validate as URL
            'photos_json' => ['nullable', 'string'], // Expecting JSON string
            'videos_json' => ['nullable', 'string'], // Expecting JSON string
        ]);

        $hotel->rooms()->create($validatedData);

        return redirect()->route('admin.panel.hotels.show', $hotel->hotel_id)->with('success', __('Room added successfully to :hotel.', ['hotel' => $hotel->name]));
    }

    /**
     * Display the specified room (globally accessible via /admin-panel/rooms/{room}).
     */
    public function show(Room $room): \Illuminate\View\View
    {
        $room->load('hotel'); // Eager load hotel details
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room (globally accessible via /admin-panel/rooms/{room}/edit).
     */
    public function edit(Room $room): \Illuminate\View\View
    {
        $room->load('hotel');
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage (globally accessible via PATCH /admin-panel/rooms/{room}).
     */
    public function update(Request $request, Room $room): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'],
            'photos_json' => ['nullable', 'string'],
            'videos_json' => ['nullable', 'string'],
        ]);

        $room->update($validatedData);

        return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('success', __('Room updated successfully.'));
    }

    /**
     * Remove the specified room from storage (globally accessible via DELETE /admin-panel/rooms/{room}).
     */
    public function destroy(Room $room): \Illuminate\Http\RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('error', __('Cannot delete room with existing bookings.'));
        }

        $room->delete();
        return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('success', __('Room deleted successfully.'));
    }
}
```

---

**الخطوة 3: إنشاء ملفات الـ Views للغرف (`resources/views/admin/rooms/`)**

**1. `resources/views/admin/rooms/index.blade.php` (قائمة الغرف لفندق معين)**

```php
@extends('admin.layouts.app')

@section('title', __('Rooms for :hotel_name', ['hotel_name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Rooms for :hotel_name', ['hotel_name' => $hotel->name]) }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.panel.hotels.show', $hotel->hotel_id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 shadow-sm whitespace-nowrap">
                &larr; {{ __('Back to Hotel Details') }}
            </a>
            <a href="{{ route('admin.panel.hotels.rooms.create', $hotel->hotel_id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('Add New Room') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md overflow-x-auto rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Room ID') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Max Occupancy') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price Per Night') }}</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Services') }}</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->room_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->max_occupancy }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($room->price_per_night, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($room->services, 70) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('admin.panel.rooms.show', $room->room_id) }}" class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out ml-2">
                                {{ __('View') }}
                            </a>
                            <a href="{{ route('admin.panel.rooms.edit', $room->room_id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.panel.rooms.destroy', $room->room_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this room?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ __('No rooms found for this hotel.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rooms->hasPages())
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
    @endif
</div>
@endsection
```

**2. `resources/views/admin/rooms/create.blade.php` (إنشاء غرفة لفندق معين)**

```php
@extends('admin.layouts.app')

@section('title', __('Add New Room to :hotel_name', ['hotel_name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Add New Room to :hotel_name', ['hotel_name' => $hotel->name]) }}</h1>
            <a href="{{ route('admin.panel.hotels.show', $hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                &rarr; {{ __('Back to Hotel Details') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.hotels.rooms.store', $hotel->hotel_id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="max_occupancy" :value="__('Maximum Occupancy')" />
                        <x-text-input id="max_occupancy" name="max_occupancy" type="number" min="1" class="mt-1 block w-full" :value="old('max_occupancy')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('max_occupancy')" />
                    </div>
                    <div>
                        <x-input-label for="price_per_night" :value="__('Price Per Night (Currency)')" />
                        <x-text-input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_night')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('price_per_night')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="services" :value="__('Services Offered')" />
                        <textarea id="services" name="services" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('services') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('services')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="payment_link" :value="__('Payment Link (Optional)')" />
                        <x-text-input id="payment_link" name="payment_link" type="url" class="mt-1 block w-full" :value="old('payment_link')" />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_link')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="photos_json" :value="__('Photos (JSON Array of URLs)')" />
                        <textarea id="photos_json" name="photos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('photos_json', '[]') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('photos_json')" />
                    </div>
                     <div class="md:col-span-2">
                        <x-input-label for="videos_json" :value="__('Videos (JSON Array of URLs)')" />
                        <textarea id="videos_json" name="videos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('videos_json', '[]') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('videos_json')" />
                    </div>
                </div>
                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.hotels.show', $hotel->hotel_id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Add Room') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

**3. `resources/views/admin/rooms/edit.blade.php` (تعديل غرفة)**

```php
@extends('admin.layouts.app')

@section('title', __('Edit Room ID :id for :hotel_name', ['id' => $room->room_id, 'hotel_name' => $room->hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Edit Room ID :id for :hotel_name', ['id' => $room->room_id, 'hotel_name' => $room->hotel->name]) }}</h1>
            <a href="{{ route('admin.panel.hotels.show', $room->hotel_id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                &rarr; {{ __('Back to Hotel Details') }}
            </a>
        </div>

        <div class="bg-white shadow-md px-6 py-8 sm:rounded-lg">
            <form action="{{ route('admin.panel.rooms.update', $room->room_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="max_occupancy" :value="__('Maximum Occupancy')" />
                        <x-text-input id="max_occupancy" name="max_occupancy" type="number" min="1" class="mt-1 block w-full" :value="old('max_occupancy', $room->max_occupancy)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('max_occupancy')" />
                    </div>
                    <div>
                        <x-input-label for="price_per_night" :value="__('Price Per Night (Currency)')" />
                        <x-text-input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price_per_night', $room->price_per_night)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('price_per_night')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="services" :value="__('Services Offered')" />
                        <textarea id="services" name="services" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('services', $room->services) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('services')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $room->notes) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="payment_link" :value="__('Payment Link (Optional)')" />
                        <x-text-input id="payment_link" name="payment_link" type="url" class="mt-1 block w-full" :value="old('payment_link', $room->payment_link)" />
                        <x-input-error class="mt-2" :messages="$errors->get('payment_link')" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="photos_json" :value="__('Photos (JSON Array of URLs)')" />
                        <textarea id="photos_json" name="photos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('photos_json', $room->photos_json ? json_encode($room->photos_json) : '[]') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('photos_json')" />
                    </div>
                     <div class="md:col-span-2">
                        <x-input-label for="videos_json" :value="__('Videos (JSON Array of URLs)')" />
                        <textarea id="videos_json" name="videos_json" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('videos_json', $room->videos_json ? json_encode($room->videos_json) : '[]') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('videos_json')" />
                    </div>
                </div>
                <div class="pt-8 flex justify-end">
                    <a href="{{ route('admin.panel.hotels.show', $room->hotel_id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="mr-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update Room') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

**4. `resources/views/admin/rooms/show.blade.php` (عرض تفاصيل غرفة)**

```php
@extends('admin.layouts.app')

@section('title', __('Room Details: ID :id', ['id' => $room->room_id]))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Room Details') }}</h1>
            <a href="{{ route('admin.panel.hotels.show', $room->hotel_id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                &rarr; {{ __('Back to :hotel_name Hotel Details', ['hotel_name' => $room->hotel->name ?? '']) }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Room Information') }}
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Room ID') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->room_id }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($room->hotel)
                                <a href="{{ route('admin.panel.hotels.show', $room->hotel->hotel_id) }}" class="text-indigo-600 hover:text-indigo-900">{{ $room->hotel->name }}</a>
                            @else
                                {{ __('N/A') }}
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Maximum Occupancy') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->max_occupancy }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Price Per Night') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($room->price_per_night, 2) }} {{ __('currency') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Services Offered') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->services ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->notes ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Payment Link') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($room->payment_link)
                                <a href="{{ $room->payment_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $room->payment_link }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($room->photos_json) && count($room->photos_json) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($room->photos_json as $photo)
                                        <img src="{{ $photo }}" alt="Room Photo" class="h-24 w-24 object-cover rounded-md shadow-sm">
                                    @endforeach
                                </div>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Videos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($room->videos_json) && count($room->videos_json) > 0)
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($room->videos_json as $video)
                                        <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $room->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Bookings for this Room') }}
                </h3>
            </div>
            <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Booking ID') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-in Date') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Check-out Date') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Total Price') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($room->bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->book_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->username ?? __('N/A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($booking->booking_status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->booking_status == 'rejected' || $booking->booking_status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ __($booking->booking_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->check_in_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->check_out_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($booking->total_price, 2) }} {{ __('currency') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ __('No bookings found for this room.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

**الخطوة 4: تعديل ملف `resources/views/admin/hotels/show.blade.php` لإضافة زر "إضافة غرفة"**

الآن، سنقوم بإضافة زر "إضافة غرفة" في صفحة تفاصيل الفندق، مع ربطه بمسار إنشاء الغرفة الجديد.

```php
{{-- Debugging section - REMOVE LATER --}}
@if(config('app.debug') && false) {{-- Changed to false to hide by default --}}
    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
        <h4 class="font-bold">Debug: photos_json from DB (raw) for Show Page</h4>
        <pre class="text-xs overflow-x-auto">{{ $hotel->getRawOriginal('photos_json') }}</pre>
        <h4 class="font-bold mt-2">Debug: photos_json from DB (casted to array by Laravel) for Show Page</h4>
        <pre class="text-xs overflow-x-auto">{{ print_r($hotel->photos_json, true) }}</pre>
         <h4 class="font-bold mt-2">Debug: photos_json for Alpine Carousel (encoded)</h4>
        <pre class="text-xs overflow-x-auto">{{ json_encode($hotel->photos_json) }}</pre>
    </div>
@endif
{{-- End Debugging section --}}

@extends('admin.layouts.app')

@section('title', __('Hotel Details: :name', ['name' => $hotel->name]))

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ __('Hotel Details') }}</h1>
            <a href="{{ route('admin.panel.hotels.index') }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out">
                → {{ __('Back to Hotels List') }}
            </a>
        </div>

        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Hotel Information') }}
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->name }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Location') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->location ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Rating') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->rating ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Contact Person Phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->contact_person_phone ?: '-' }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Hotel Admin') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $hotel->adminUser->username ?? __('None assigned') }}
                            @if($hotel->adminUser) ({{ $hotel->adminUser->first_name }} {{ $hotel->adminUser->last_name }}) @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->notes ?: '-' }}</dd>
                    </div>

                    {{-- Photos Carousel --}}
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Photos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($hotel->photos_json) && count($hotel->photos_json) > 0)
                                <div x-data="{ activeSlide: 0, photos: {{ json_encode($hotel->photos_json) }} }" class="relative w-full max-w-lg mx-auto">
                                    <div class="relative overflow-hidden rounded-lg shadow-lg h-64">
                                        <template x-for="(photo, index) in photos" :key="index">
                                            <div x-show="activeSlide === index"
                                                 x-transition:enter="transition ease-out duration-500"
                                                 x-transition:enter-start="opacity-0 transform scale-90"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 x-transition:leave="transition ease-in duration-500"
                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                 x-transition:leave-end="opacity-0 transform scale-90"
                                                 class="absolute inset-0">
                                                <img :src="photo" class="w-full h-full object-cover">
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Navigation Buttons --}}
                                    <button type="button" @click="activeSlide = (activeSlide === 0) ? photos.length - 1 : activeSlide - 1"
                                            class="absolute top-1/2 left-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="activeSlide = (activeSlide === photos.length - 1) ? 0 : activeSlide + 1"
                                            class="absolute top-1/2 right-2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    {{-- Indicators --}}
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-2">
                                        <template x-for="(photo, index) in photos" :key="index">
                                            <button type="button" @click="activeSlide = index"
                                                    class="w-3 h-3 rounded-full"
                                                    :class="{ 'bg-white': activeSlide === index, 'bg-gray-400': activeSlide !== index }">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @else
                                <p>-</p>
                            @endif
                        </dd>
                    </div>

                    {{-- Videos List --}}
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Videos') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if(is_array($hotel->videos_json) && count($hotel->videos_json) > 0)
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($hotel->videos_json as $video)
                                        <li><a href="{{ $video }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">{{ $video }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif
                        </dd>
                    </div>

                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Created At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->created_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                    <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated At') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $hotel->updated_at->translatedFormat('l, d F Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between items-center px-4 py-5 sm:px-6 border-b border-gray-200 bg-white shadow-md sm:rounded-t-lg">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Rooms in this Hotel') }}
                </h3>
                <a href="{{ route('admin.panel.hotels.rooms.create', $hotel->hotel_id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Add Room') }}
                </a>
            </div>
            <div class="bg-white shadow-md overflow-x-auto sm:rounded-b-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Room ID') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Max Occupancy') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Price Per Night') }}</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('Services') }}</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($hotel->rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->room_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $room->max_occupancy }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($room->price_per_night, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($room->services, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.panel.rooms.show', $room->room_id) }}" class="text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out ml-2">
                                        {{ __('View') }}
                                    </a>
                                    <a href="{{ route('admin.panel.rooms.edit', $room->room_id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.panel.rooms.destroy', $room->room_id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this room?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ __('No rooms found for this hotel.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
```

---





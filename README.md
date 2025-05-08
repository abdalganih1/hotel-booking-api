<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



**ملاحظات هامة قبل البدء:**

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



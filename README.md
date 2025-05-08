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




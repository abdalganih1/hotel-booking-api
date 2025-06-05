<?php

 namespace Database\Factories;

 use App\Models\User;
 use Illuminate\Database\Eloquent\Factories\Factory;
 use Illuminate\Support\Str;
 use Illuminate\Support\Facades\Hash;

 class UserFactory extends Factory
 {
     protected $model = User::class;

     public function definition()
     {
         $gender = $this->faker->randomElement(['male', 'female']);
         $firstName = $this->faker->firstName($gender);
         $lastName = $this->faker->lastName($gender);

         return [
             'username' => $this->faker->unique()->userName(), // يجب أن يكون فريدًا
             'password' => Hash::make('password'),
             'role' => $this->faker->randomElement(['user', 'user', 'user', 'hotel_admin']), // فرص أكبر للمستخدمين العاديين
             'first_name' => $firstName,
             'last_name' => $lastName,
             'email' => $this->faker->unique()->safeEmail(), // يجب أن يكون فريدًا
             'email_verified_at' => now(),
             'phone_number' => $this->faker->unique()->phoneNumber(), // يجب أن يكون فريدًا
             'address' => $this->faker->address(),
             'gender' => $gender,
             'age' => $this->faker->numberBetween(18, 70),
             'remember_token' => Str::random(10),
         ];
     }

     // Factory states for specific roles with UNIQUE data for general factory usage
     public function appAdmin()
     {
         return $this->state(function (array $attributes) {
             return [
                 'username' => 'app_admin_' . $this->faker->unique()->randomNumber(5), // اجعلها فريدة
                 'email' => 'admin_' . $this->faker->unique()->randomNumber(5) . '@example.com', // اجعلها فريدة
                 'role' => 'app_admin',
                 'first_name' => 'مدير',
                 'last_name' => 'التطبيق',
             ];
         });
     }

     public function hotelAdmin()
     {
         return $this->state(function (array $attributes) {
             return [
                 'username' => 'hotel_admin_' . $this->faker->unique()->randomNumber(5), // اجعلها فريدة
                 'email' => 'hoteladmin_' . $this->faker->unique()->randomNumber(5) . '@example.com', // اجعلها فريدة
                 'role' => 'hotel_admin',
                 'first_name' => 'مسؤول',
                 'last_name' => 'فندق',
             ];
         });
     }
 }

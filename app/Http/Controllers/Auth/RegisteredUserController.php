<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class], // استخدم username
            'first_name' => ['required', 'string', 'max:255'], // أضف الاسم الأول كحقل مطلوب
            'last_name' => ['nullable', 'string', 'max:255'], // أضف الاسم الأخير كحقل اختياري
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username, // استخدم username
            'first_name' => $request->first_name, // استخدم first_name
            'last_name' => $request->last_name, // استخدم last_name
            'email' => $request->email,
            'password' => Hash::make($request->password), // كلمة المرور
            'role' => 'user', // تعيين الدور الافتراضي 'user' عند التسجيل
            // لا حاجة لـ 'name' لأنه غير موجود في جدولنا بعد التعديل
            // باقي الحقول التي تقبل NULL في قاعدة البيانات يمكن تركها فارغة هنا أو إضافتها حسب رغبتك
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
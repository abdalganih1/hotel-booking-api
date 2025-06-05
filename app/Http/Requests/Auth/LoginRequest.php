<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // تأكد من استيراد نموذج User

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // اسم المستخدم أو البريد الإلكتروني - نقوم بالتحقق من وجود أحدهما
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = $this->input('identifier');
        $password = $this->input('password');

        $credentials = [];

        // تحديد ما إذا كان المدخل هو بريد إلكتروني أو اسم مستخدم
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $identifier;
        } else {
            $credentials['username'] = $identifier;
        }

        // بما أننا نستخدم 'password' في جدولنا، Auth::attempt سيعمل معها
        // ولكن للتأكد من أننا نتحقق مقابل 'password' وليس 'password_hash'، يجب أن يكون الحقل في نموذج User اسمه 'password'
        // وإذا كان اسم الحقل في الـ DB هو 'password'، فإن Laravel سيتعامل معه تلقائيًا.
        if (! Auth::attempt($credentials + ['password' => $password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifier' => trans('auth.failed'), // تغيير اسم الحقل هنا ليتوافق مع المدخل الجديد
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [ // تغيير اسم الحقل هنا
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // استخدم 'identifier' بدلاً من 'email'
        return Str::transliterate(Str::lower($this->string('identifier')).'|'.$this->ip());
    }
}
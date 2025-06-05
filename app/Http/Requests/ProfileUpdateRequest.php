<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
            // حقول المستخدم الجديدة/المعدلة
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->user_id, 'user_id')], // unique by user_id
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($this->user()->user_id, 'user_id')], // unique by user_id
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],

            // حقول Breeze الافتراضية
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->user_id, 'user_id')], // unique by user_id
        ];
    }
}
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
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // هذا هو Request الذي تم تعديله
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // $request->validated() سيعيد جميع الحقول التي تم التحقق منها
        // ثم fill() ستقوم بملء النموذج بهذه الحقول
        $request->user()->fill($request->validated());

        // إذا تم تغيير البريد الإلكتروني، قم بتعيين email_verified_at إلى null لإعادة التحقق
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // احفظ التغييرات في قاعدة البيانات
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Storage; // لم نعد نحتاجها بعد إلغاء الرفع
// use Illuminate\Support\Facades\Log; // لم نعد نحتاجها بعد إلغاء الرفع
// use Illuminate\Support\Str; // لم نعد نحتاجها بعد إلغاء الرفع (إلا إذا كنت تستخدمها في مكان آخر)

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
                Rule::unique('hotels', 'admin_user_id')->whereNotNull('admin_user_id'),
            ],
            // حقول الـ URLs فقط
            'photos' => ['nullable', 'array'], // سيحتوي على مصفوفة من الروابط
            'photos.*' => ['nullable', 'url', 'max:2048'], // روابط URL
            'videos' => ['nullable', 'array'], // سيحتوي على مصفوفة من الروابط
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        // تحضير البيانات لإنشاء الفندق
        $hotelData = [
            'name' => $validatedData['name'],
            'location' => $validatedData['location'],
            'rating' => $validatedData['rating'],
            'notes' => $validatedData['notes'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'admin_user_id' => $validatedData['admin_user_id'],
            'photos_json' => json_encode($validatedData['photos'] ?? []), // حفظ كـ JSON
            'videos_json' => json_encode($validatedData['videos'] ?? []), // حفظ كـ JSON
        ];
        // لا، الأفضل هو ترك الـ mutator في Model يقوم بالـ json_encode
        // لذا، إذا كانت الـ inputs هي 'photos' و 'videos' (مصفوفات PHP)
        // قم بتغيير السطرين أعلاه إلى:
        $hotelData['photos_json'] = $validatedData['photos'] ?? [];
        $hotelData['videos_json'] = $validatedData['videos'] ?? [];
        
        $hotel = Hotel::create($hotelData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel created successfully.'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel): \Illuminate\View\View
    {
        $hotel->load('adminUser', 'rooms');
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
            // حقول الـ URLs فقط
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        // تحضير البيانات للتحديث
        $hotelData = [
            'name' => $validatedData['name'],
            'location' => $validatedData['location'],
            'rating' => $validatedData['rating'],
            'notes' => $validatedData['notes'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'admin_user_id' => $validatedData['admin_user_id'],
           'photos_json' => $validatedData['photos'] ?? [], // دع الـ mutator يقوم بالـ json_encode
            'videos_json' => $validatedData['videos'] ?? [], // دع الـ mutator يقوم بالـ json_encode
        ];

        $hotel->update($hotelData);

        return redirect()->route('admin.panel.hotels.index')->with('success', __('Hotel updated successfully.'));
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        // لم نعد نحذف الملفات من التخزين لأننا لا نرفعها
        // if ($hotel->photos_json && is_array($hotel->photos_json)) { ... }
        // if ($hotel->videos_json && is_array($hotel->videos_json)) { ... }

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
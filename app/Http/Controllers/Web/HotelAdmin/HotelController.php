<?php

namespace App\Http\Controllers\Web\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:hotel_admin']);
    }

    /**
     * Get the hotel managed by the authenticated hotel admin.
     */
    private function getHotelForAdmin(): Hotel
    {
        return Auth::user()->hotelAdminFor()->firstOrFail();
    }

    /**
     * Display the specific hotel managed by the admin.
     */
    public function show(): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        $hotel->load('rooms'); // Load rooms for display in hotel details
        return view('hotel_admin.hotel.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specific hotel managed by the admin.
     */
    public function edit(): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        return view('hotel_admin.hotel.edit', compact('hotel'));
    }

    /**
     * Update the specific hotel managed by the admin.
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $hotel = $this->getHotelForAdmin();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')->ignore($hotel->hotel_id, 'hotel_id')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        $hotelData = [
            'name' => $validatedData['name'],
            'location' => $validatedData['location'],
            'rating' => $validatedData['rating'],
            'notes' => $validatedData['notes'],
            'contact_person_phone' => $validatedData['contact_person_phone'],
            'photos_json' => $validatedData['photos'] ?? [],
            'videos_json' => $validatedData['videos'] ?? [],
        ];

        $hotel->update($hotelData);

        return redirect()->route('hotel_admin.panel.hotel.show')->with('success', __('Hotel information updated successfully.'));
    }
}
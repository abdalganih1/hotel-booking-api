<?php

namespace App\Http\Controllers\Web\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
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
     * Display a listing of the rooms for the admin's hotel.
     */
    public function index(): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        $rooms = $hotel->rooms()->paginate(15);
        return view('hotel_admin.rooms.index', compact('hotel', 'rooms'));
    }

    /**
     * Show the form for creating a new room for the admin's hotel.
     * Note: Parameter is not used, as we fetch hotel from Auth::user().
     */
    public function create(): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        return view('hotel_admin.rooms.create', compact('hotel'));
    }

    /**
     * Store a newly created room in storage for the admin's hotel.
     * Note: Parameter is not used, as we fetch hotel from Auth::user().
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $hotel = $this->getHotelForAdmin();

        $validatedData = $request->validate([
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        $roomData = [
            'hotel_id' => $hotel->hotel_id, // Ensure room is linked to this hotel
            'max_occupancy' => $validatedData['max_occupancy'],
            'price_per_night' => $validatedData['price_per_night'],
            'services' => $validatedData['services'],
            'notes' => $validatedData['notes'],
            'payment_link' => $validatedData['payment_link'],
            'photos_json' => $validatedData['photos'] ?? [],
            'videos_json' => $validatedData['videos'] ?? [],
        ];

        $hotel->rooms()->create($roomData); // Use the relation for creation

        return redirect()->route('hotel_admin.panel.rooms.index')->with('success', __('Room added successfully.'));
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            abort(403, 'Unauthorized action.');
        }
        $room->load('hotel');
        return view('hotel_admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room): \Illuminate\View\View
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            abort(403, 'Unauthorized action.');
        }
        $room->load('hotel');
        return view('hotel_admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room): \Illuminate\Http\RedirectResponse
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        $roomData = [
            'max_occupancy' => $validatedData['max_occupancy'],
            'price_per_night' => $validatedData['price_per_night'],
            'services' => $validatedData['services'],
            'notes' => $validatedData['notes'],
            'payment_link' => $validatedData['payment_link'],
            'photos_json' => $validatedData['photos'] ?? [],
            'videos_json' => $validatedData['videos'] ?? [],
        ];

        $room->update($roomData);

        return redirect()->route('hotel_admin.panel.rooms.index')->with('success', __('Room updated successfully.'));
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room): \Illuminate\Http\RedirectResponse
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($room->bookings()->exists()) {
            return redirect()->route('hotel_admin.panel.rooms.index')->with('error', __('Cannot delete room with existing bookings.'));
        }

        $room->delete();
        return redirect()->route('hotel_admin.panel.rooms.index')->with('success', __('Room deleted successfully.'));
    }
}
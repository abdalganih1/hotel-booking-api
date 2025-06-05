<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    public function index(Hotel $hotel): \Illuminate\View\View
    {
        $rooms = $hotel->rooms()->paginate(15);
        return view('admin.rooms.index', compact('hotel', 'rooms'));
    }

    public function create(Hotel $hotel): \Illuminate\View\View
    {
        return view('admin.rooms.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'services' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_link' => ['nullable', 'url'],
            'photos' => ['nullable', 'array'], // Expecting array of URLs
            'photos.*' => ['nullable', 'url', 'max:2048'], // Each item in array is a URL
            'videos' => ['nullable', 'array'], // Expecting array of URLs
            'videos.*' => ['nullable', 'url', 'max:2048'], // Each item in array is a URL
        ]);

        // Pass the arrays directly to the model. Mutators will handle JSON encoding.
        $roomData = [
            'max_occupancy' => $validatedData['max_occupancy'],
            'price_per_night' => $validatedData['price_per_night'],
            'services' => $validatedData['services'],
            'notes' => $validatedData['notes'],
            'payment_link' => $validatedData['payment_link'],
            'photos_json' => $validatedData['photos'] ?? [], // Pass array, not JSON string
            'videos_json' => $validatedData['videos'] ?? [], // Pass array, not JSON string
        ];

        $hotel->rooms()->create($roomData);

        return redirect()->route('admin.panel.hotels.show', $hotel->hotel_id)->with('success', __('Room added successfully to :hotel.', ['hotel' => $hotel->name]));
    }

    public function show(Room $room): \Illuminate\View\View
    {
        $room->load('hotel');
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room): \Illuminate\View\View
    {
        $room->load('hotel');
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): \Illuminate\Http\RedirectResponse
    {
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

        // Pass the arrays directly to the model. Mutators will handle JSON encoding.
        $roomData = [
            'max_occupancy' => $validatedData['max_occupancy'],
            'price_per_night' => $validatedData['price_per_night'],
            'services' => $validatedData['services'],
            'notes' => $validatedData['notes'],
            'payment_link' => $validatedData['payment_link'],
            'photos_json' => $validatedData['photos'] ?? [], // Pass array, not JSON string
            'videos_json' => $validatedData['videos'] ?? [], // Pass array, not JSON string
        ];

        $room->update($roomData);

        return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('success', __('Room updated successfully.'));
    }

    public function destroy(Room $room): \Illuminate\Http\RedirectResponse
    {
        if ($room->bookings()->exists()) {
            return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('error', __('Cannot delete room with existing bookings.'));
        }

        $room->delete();
        return redirect()->route('admin.panel.hotels.show', $room->hotel_id)->with('success', __('Room deleted successfully.'));
    }
}
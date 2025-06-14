<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class HotelAdminRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    /**
     * Get the hotel managed by the authenticated hotel admin.
     */
    private function getHotelForAdmin(): Hotel
    {
        return Auth::user()->hotelAdminFor()->firstOrFail();
    }

    /**
     * Display a listing of the rooms for the authenticated admin's hotel.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin();
        $rooms = $hotel->rooms()->paginate($request->get('limit', 15));
        return response()->json($rooms);
    }

    /**
     * Store a newly created room for the authenticated admin's hotel.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $hotel = $this->getHotelForAdmin();

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $roomData = $validator->validated();
        $roomData['hotel_id'] = $hotel->hotel_id; // Explicitly link to admin's hotel
        $roomData['photos_json'] = $roomData['photos'] ?? [];
        $roomData['videos_json'] = $roomData['videos'] ?? [];
        unset($roomData['photos'], $roomData['videos']);

        $room = $hotel->rooms()->create($roomData); // Create via relationship

        return response()->json(['room' => $room, 'message' => 'Room created successfully.'], 201);
    }

    /**
     * Display the specified room if it belongs to the authenticated admin's hotel.
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'Unauthorized to view this room.'], 403);
        }
        $room->load('hotel');
        return response()->json($room);
    }

    /**
     * Update the specified room if it belongs to the authenticated admin's hotel.
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'Unauthorized to update this room.'], 403);
        }

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $roomData = $validator->validated();
        $roomData['photos_json'] = $roomData['photos'] ?? [];
        $roomData['videos_json'] = $roomData['videos'] ?? [];
        unset($roomData['photos'], $roomData['videos']);

        $room->update($roomData);

        return response()->json(['room' => $room, 'message' => 'Room updated successfully.']);
    }

    /**
     * Remove the specified room if it belongs to the authenticated admin's hotel.
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Room $room)
    {
        $hotel = $this->getHotelForAdmin();
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'Unauthorized to delete this room.'], 403);
        }

        if ($room->bookings()->exists()) {
            return response()->json(['message' => 'Cannot delete room with existing bookings.'], 400);
        }

        $room->delete();
        return response()->json(['message' => 'Room deleted successfully.'], 204);
    }
}
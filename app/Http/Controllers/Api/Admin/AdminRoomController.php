<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel; // Required for creating a room within a hotel context
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display a listing of rooms (can be filtered by hotel).
     * @param Request $request
     * @param Hotel|null $hotel (Optional: for nested resource context)
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Hotel $hotel = null)
    {
        $query = Room::with('hotel');

        if ($hotel) { // If accessed via /hotels/{hotel}/rooms
            $query->where('hotel_id', $hotel->hotel_id);
        } elseif ($request->filled('hotel_id')) { // Or if hotel_id is passed as query param
            $query->where('hotel_id', $request->hotel_id);
        }

        // Add other filters like max_occupancy, price range if needed
        $rooms = $query->paginate($request->get('limit', 15));
        return response()->json($rooms);
    }

    /**
     * Store a newly created room.
     * @param Request $request
     * @param Hotel|null $hotel (Optional: for nested resource context)
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Hotel $hotel = null)
    {
        $hotelId = $hotel ? $hotel->hotel_id : $request->hotel_id;

        $validator = Validator::make($request->all(), [
            'hotel_id' => [Rule::requiredIf(empty($hotel)), 'exists:hotels,hotel_id'], // Required if not provided in URL
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
        $roomData['hotel_id'] = $hotelId;
        $roomData['photos_json'] = $roomData['photos'] ?? [];
        $roomData['videos_json'] = $roomData['videos'] ?? [];
        unset($roomData['photos'], $roomData['videos']);

        $room = Room::create($roomData);

        return response()->json(['room' => $room, 'message' => 'Room created successfully.'], 201);
    }

    /**
     * Display the specified room.
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $room->load('hotel');
        return response()->json($room);
    }

    /**
     * Update the specified room.
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => ['sometimes', 'required', 'exists:hotels,hotel_id'], // Can change hotel, must exist
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
     * Remove the specified room.
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Room $room)
    {
        if ($room->bookings()->exists()) {
            return response()->json(['message' => 'Cannot delete room with existing bookings.'], 400);
        }

        $room->delete();
        return response()->json(['message' => 'Room deleted successfully.'], 204);
    }
}
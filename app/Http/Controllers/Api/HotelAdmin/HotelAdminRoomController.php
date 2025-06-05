<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\RoomResource;
// use App\Http\Resources\RoomCollection;

class HotelAdminRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']);
    }

    private function getHotelForAdmin(Request $request)
    {
        return Hotel::where('admin_user_id', $request->user()->user_id)->firstOrFail();
    }

    /**
     * Display a listing of the hotel's rooms. (إدارة بيانات الفندق - غرف)
     */
    public function index(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);
        $rooms = Room::where('hotel_id', $hotel->hotel_id)->paginate(15);
        // return new RoomCollection($rooms);
        return response()->json($rooms);
    }

    /**
     * Store a newly created room in storage for the hotel. (إضافة غرفة)
     */
    public function store(Request $request)
    {
        $hotel = $this->getHotelForAdmin($request);

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'max_occupancy' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'services_offered' => 'nullable|string',
            'photos_json' => 'nullable|json',
            'videos_json' => 'nullable|json',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = $hotel->rooms()->create($request->all());
        // return new RoomResource($room);
        return response()->json($room, 201);
    }

    /**
     * Display the specified room.
     */
    public function show(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        // Ensure room belongs to the admin's hotel
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }
        // return new RoomResource($room);
        return response()->json($room);
    }

    /**
     * Update the specified room in storage. (تعديل غرفة)
     */
    public function update(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }

        // TODO: Validation (similar to store)
        $validator = Validator::make($request->all(), [
            'max_occupancy' => 'sometimes|required|integer|min:1',
            'price_per_night' => 'sometimes|required|numeric|min:0',
            // ...
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room->update($request->all());
        // return new RoomResource($room);
        return response()->json($room);
    }

    /**
     * Remove the specified room from storage. (حذف غرفة)
     */
    public function destroy(Request $request, Room $room)
    {
        $hotel = $this->getHotelForAdmin($request);
        if ($room->hotel_id !== $hotel->hotel_id) {
            return response()->json(['message' => 'غير مصرح به لهذه الغرفة'], 403);
        }

        // TODO: Check if room has active bookings before deleting
        if ($room->bookings()->whereIn('booking_status', ['pending_verification', 'confirmed'])->exists()) {
            return response()->json(['message' => 'لا يمكن حذف الغرفة، توجد حجوزات نشطة.'], 400);
        }

        $room->delete();
        return response()->json(null, 204);
    }
}
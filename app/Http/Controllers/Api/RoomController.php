<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms (optional, might be too broad for Flutter app).
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Room::with('hotel');

        // Filter by hotel
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filter by max_occupancy
        if ($request->filled('max_occupancy')) {
            $query->where('max_occupancy', '>=', $request->max_occupancy);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $rooms = $query->paginate($request->get('limit', 15));
        return response()->json($rooms);
    }

    /**
     * Display the specified room details.
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $room->load('hotel'); // Eager load hotel details for the room
        return response()->json($room);
    }
}
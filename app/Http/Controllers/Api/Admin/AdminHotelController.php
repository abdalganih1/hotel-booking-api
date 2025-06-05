<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display a listing of the hotels.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Hotel::with('adminUser');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('admin_user_id')) {
            $query->where('admin_user_id', $request->admin_user_id);
        }

        $hotels = $query->latest()->paginate($request->get('limit', 15));
        return response()->json($hotels);
    }

    /**
     * Store a newly created hotel.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', Rule::exists('users', 'user_id')->where(function ($query) {
                return $query->where('role', 'hotel_admin');
            }), Rule::unique('hotels', 'admin_user_id')->whereNotNull('admin_user_id')],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hotelData = $validator->validated();
        $hotelData['photos_json'] = $hotelData['photos'] ?? []; // Mutator handles JSON encoding
        $hotelData['videos_json'] = $hotelData['videos'] ?? []; // Mutator handles JSON encoding
        unset($hotelData['photos'], $hotelData['videos']); // Remove original array fields

        $hotel = Hotel::create($hotelData);

        return response()->json(['hotel' => $hotel, 'message' => 'Hotel created successfully.'], 201);
    }

    /**
     * Display the specified hotel.
     * @param Hotel $hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('adminUser', 'rooms');
        return response()->json($hotel);
    }

    /**
     * Update the specified hotel.
     * @param Request $request
     * @param Hotel $hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('hotels', 'name')->ignore($hotel->hotel_id, 'hotel_id')],
            'location' => ['nullable', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'notes' => ['nullable', 'string'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'admin_user_id' => ['nullable', Rule::exists('users', 'user_id')->where(function ($query) {
                return $query->where('role', 'hotel_admin');
            }), Rule::unique('hotels', 'admin_user_id')->ignore($hotel->hotel_id, 'hotel_id')->whereNotNull('admin_user_id')],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['nullable', 'url', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hotelData = $validator->validated();
        $hotelData['photos_json'] = $hotelData['photos'] ?? [];
        $hotelData['videos_json'] = $hotelData['videos'] ?? [];
        unset($hotelData['photos'], $hotelData['videos']);

        $hotel->update($hotelData);

        return response()->json(['hotel' => $hotel, 'message' => 'Hotel updated successfully.']);
    }

    /**
     * Remove the specified hotel.
     * @param Hotel $hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Hotel $hotel)
    {
        // Check for dependencies
        if ($hotel->rooms()->exists()) {
            return response()->json(['message' => 'Cannot delete hotel with existing rooms.'], 400);
        }
        if ($hotel->bookings()->exists()) { // Check bookings via denormalized hotel_id
            return response()->json(['message' => 'Cannot delete hotel with existing bookings.'], 400);
        }

        $hotel->delete();
        return response()->json(['message' => 'Hotel deleted successfully.'], 204);
    }
}
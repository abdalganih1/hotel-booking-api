<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelResource;
// use App\Http\Resources\HotelCollection;

class AdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // TODO: Filtering, search
        $hotels = Hotel::with('adminUser')->latest()->paginate(15);
        // return new HotelCollection($hotels);
        return response()->json($hotels);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'admin_user_id' => 'nullable|exists:users,user_id', // Check if user exists and has role hotel_admin
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ensure the assigned admin_user_id has 'hotel_admin' role if provided
        if ($request->filled('admin_user_id')) {
            $adminUser = User::find($request->admin_user_id);
            if (!$adminUser || $adminUser->role !== 'hotel_admin') {
                return response()->json(['errors' => ['admin_user_id' => ['المستخدم المحدد ليس مسؤول فندق.']]], 422);
            }
        }

        $hotel = Hotel::create($request->all());
        // return new HotelResource($hotel);
        return response()->json($hotel, 201);
    }

    public function show(Hotel $hotel)
    {
        // return new HotelResource($hotel->load('adminUser', 'rooms'));
        return response()->json($hotel->load('adminUser', 'rooms'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        // TODO: Validation (similar to store)
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'admin_user_id' => 'sometimes|nullable|exists:users,user_id',
             // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->filled('admin_user_id')) {
            $adminUser = User::find($request->admin_user_id);
            if ($adminUser && $adminUser->role !== 'hotel_admin') { // Allow null to remove admin
                return response()->json(['errors' => ['admin_user_id' => ['المستخدم المحدد ليس مسؤول فندق.']]], 422);
            }
        }


        $hotel->update($request->all());
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

    public function destroy(Hotel $hotel)
    {
        // TODO: Consider what happens to rooms and bookings
        $hotel->delete();
        return response()->json(null, 204);
    }
}
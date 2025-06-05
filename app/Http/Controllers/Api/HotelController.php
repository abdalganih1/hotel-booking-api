<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the hotels.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Hotel::with('rooms');

        // Filtering by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filtering by rating (minimum)
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Pagination
        $hotels = $query->paginate($request->get('limit', 15)); // Default 15 items per page

        return response()->json($hotels);
    }

    /**
     * Display the specified hotel details.
     * @param Hotel $hotel
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms'); // Eager load rooms for the hotel
        return response()->json($hotel);
    }
}
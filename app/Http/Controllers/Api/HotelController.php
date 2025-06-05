<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
// use App\Http\Resources\HotelResource;
// use App\Http\Resources\HotelCollection;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource. (عرض الفنادق)
     */
    public function index(Request $request)
    {
        // TODO: Pagination, Filtering (by location, rating, etc.)
        $hotels = Hotel::with('rooms')->paginate(15); // مثال مع الغرف
        // return new HotelCollection($hotels);
        return response()->json($hotels);
    }

    /**
     * Display the specified resource. (عرض تفاصيل فندق وغرفه)
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms'); // تحميل الغرف المتعلقة بالفندق
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }
}
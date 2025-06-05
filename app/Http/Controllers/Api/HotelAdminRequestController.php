<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelAdminRequestResource;

class HotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created resource in storage. (طلب صلاحية مسؤول فندق)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'requested_hotel_name' => 'required|string|max:255',
            'requested_hotel_location' => 'required|string',
            'requested_contact_phone' => 'required|string',
            'requested_photos_json' => 'nullable|json', // أو array إذا كنت تعالج رفع الملفات
            'requested_videos_json' => 'nullable|json',
            'request_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: تحقق إذا كان المستخدم لديه طلب قائم بالفعل أو هو بالفعل مسؤول
        if ($user->role === 'hotel_admin' || $user->role === 'app_admin') {
             return response()->json(['message' => 'أنت بالفعل لديك صلاحيات إدارية.'], 400);
        }
        if (HotelAdminRequest::where('user_id', $user->user_id)->where('request_status', 'pending')->exists()) {
            return response()->json(['message' => 'لديك طلب قائم بالفعل قيد المراجعة.'], 400);
        }


        $hotelAdminRequest = HotelAdminRequest::create([
            'user_id' => $user->user_id,
            'requested_hotel_name' => $request->requested_hotel_name,
            'requested_hotel_location' => $request->requested_hotel_location,
            'requested_contact_phone' => $request->requested_contact_phone,
            'requested_photos_json' => $request->requested_photos_json,
            'requested_videos_json' => $request->requested_videos_json,
            'request_notes' => $request->request_notes,
            'request_status' => 'pending',
        ]);

        // return new HotelAdminRequestResource($hotelAdminRequest);
        return response()->json($hotelAdminRequest, 201);
    }

    /**
     * Display the user's hotel admin requests.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $requests = HotelAdminRequest::where('user_id', $user->user_id)->latest()->get();
        // return HotelAdminRequestResource::collection($requests);
        return response()->json($requests);
    }
}
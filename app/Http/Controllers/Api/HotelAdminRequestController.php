<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User; // To check user role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created hotel admin request.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'requested_hotel_name' => ['required', 'string', 'max:255'],
            'requested_hotel_location' => ['required', 'string', 'max:500'],
            'requested_contact_phone' => ['required', 'string', 'max:20'],
            'photos' => ['nullable', 'array'], // Expecting array of URLs
            'photos.*' => ['nullable', 'url', 'max:2048'],
            'videos' => ['nullable', 'array'], // Expecting array of URLs
            'videos.*' => ['nullable', 'url', 'max:2048'],
            'request_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user already has an admin role or a pending request
        if ($user->hasAnyRole(['hotel_admin', 'app_admin'])) {
            return response()->json(['message' => 'You already have an administrative role.'], 400);
        }
        if (HotelAdminRequest::where('user_id', $user->user_id)->where('request_status', 'pending')->exists()) {
            return response()->json(['message' => 'You already have a pending hotel admin request.'], 400);
        }

        $hotelAdminRequest = HotelAdminRequest::create([
            'user_id' => $user->user_id,
            'requested_hotel_name' => $request->requested_hotel_name,
            'requested_hotel_location' => $request->requested_hotel_location,
            'requested_contact_phone' => $request->requested_contact_phone,
            'requested_photos_json' => $request->photos ?? [], // Pass array
            'requested_videos_json' => $request->videos ?? [], // Pass array
            'request_notes' => $request->request_notes,
            'request_status' => 'pending',
        ]);

        return response()->json(['request' => $hotelAdminRequest, 'message' => 'Hotel admin request submitted successfully. Waiting for review.'], 201);
    }

    /**
     * Display a listing of the authenticated user's hotel admin requests.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $requests = HotelAdminRequest::where('user_id', $request->user()->user_id)
                                    ->latest()
                                    ->paginate($request->get('limit', 15));
        return response()->json($requests);
    }

    /**
     * Display the specified hotel admin request of the authenticated user.
     * @param Request $request
     * @param HotelAdminRequest $hotelAdminRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, HotelAdminRequest $hotelAdminRequest)
    {
        if ($request->user()->user_id !== $hotelAdminRequest->user_id) {
            return response()->json(['message' => 'Unauthorized to view this request.'], 403);
        }
        return response()->json($hotelAdminRequest->load('user', 'reviewer')); // Load user and reviewer details
    }
}
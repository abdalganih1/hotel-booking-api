<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelAdminRequestResource;
// use App\Http\Resources\HotelAdminRequestCollection;

class AdminHotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // Filter by status (pending, approved, rejected)
        $status = $request->query('status');
        $query = HotelAdminRequest::with('user')->latest();

        if ($status) {
            $query->where('request_status', $status);
        }
        $requests = $query->paginate(15);
        // return new HotelAdminRequestCollection($requests);
        return response()->json($requests);
    }

    public function show(HotelAdminRequest $hotelAdminRequest) // Route model binding
    {
        // return new HotelAdminRequestResource($hotelAdminRequest->load('user'));
        return response()->json($hotelAdminRequest->load('user'));
    }

    public function updateRequestStatus(Request $request, HotelAdminRequest $hotelAdminRequest)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($hotelAdminRequest->request_status !== 'pending') {
            return response()->json(['message' => 'يمكن فقط مراجعة الطلبات المعلقة.'], 400);
        }

        $newStatus = $request->status;
        $hotelAdminRequest->request_status = $newStatus;
        $hotelAdminRequest->reviewed_by_user_id = Auth::id();
        $hotelAdminRequest->review_timestamp = now();
        $hotelAdminRequest->save();

        if ($newStatus === 'approved') {
            // 1. Upgrade user role
            $userToUpgrade = User::find($hotelAdminRequest->user_id);
            if ($userToUpgrade) {
                $userToUpgrade->role = 'hotel_admin';
                $userToUpgrade->save();

                // 2. Create a new hotel (or link to an existing one if logic allows)
                // For simplicity, creating a new hotel based on request data
                Hotel::create([
                    'name' => $hotelAdminRequest->requested_hotel_name,
                    'location' => $hotelAdminRequest->requested_hotel_location,
                    'contact_person_phone' => $hotelAdminRequest->requested_contact_phone,
                    'photos_json' => $hotelAdminRequest->requested_photos_json,
                    'videos_json' => $hotelAdminRequest->requested_videos_json,
                    'notes' => 'تم إنشاؤه من طلب إدارة فندق.',
                    'admin_user_id' => $userToUpgrade->user_id,
                ]);
            }
        }
        // return new HotelAdminRequestResource($hotelAdminRequest);
        return response()->json($hotelAdminRequest);
    }
}
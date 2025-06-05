<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminHotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    /**
     * Display a listing of hotel admin requests.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = HotelAdminRequest::with('user', 'reviewer')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('request_status', $request->status);
        }

        $requests = $query->paginate($request->get('limit', 15));
        return response()->json($requests);
    }

    /**
     * Display the specified hotel admin request.
     * @param HotelAdminRequest $hotelAdminRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(HotelAdminRequest $hotelAdminRequest)
    {
        return response()->json($hotelAdminRequest->load('user', 'reviewer'));
    }

    /**
     * Update the status of the specified hotel admin request (Approve/Reject).
     * @param Request $request
     * @param HotelAdminRequest $hotelAdminRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRequestStatus(Request $request, HotelAdminRequest $hotelAdminRequest)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'rejection_reason' => ['nullable', 'string', 'max:500'], // Optional for rejection
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($hotelAdminRequest->request_status !== 'pending') {
            return response()->json(['message' => 'Only pending requests can be reviewed.'], 400);
        }

        $newStatus = $request->status;
        $hotelAdminRequest->request_status = $newStatus;
        $hotelAdminRequest->reviewed_by_user_id = Auth::id();
        $hotelAdminRequest->review_timestamp = now();
        // $hotelAdminRequest->rejection_reason = $request->rejection_reason; // If you add this field to migration

        DB::beginTransaction();
        try {
            $hotelAdminRequest->save();

            if ($newStatus === 'approved') {
                $userToUpgrade = User::find($hotelAdminRequest->user_id);

                if (!$userToUpgrade) {
                    DB::rollBack();
                    return response()->json(['message' => 'Request user not found. Status updated but user not upgraded.'], 404);
                }
                if ($userToUpgrade->hasAnyRole(['hotel_admin', 'app_admin'])) {
                    DB::rollBack();
                    return response()->json(['message' => 'User already has an admin role. No upgrade performed.'], 400);
                }

                $userToUpgrade->role = 'hotel_admin';
                $userToUpgrade->save();

                // Create a new hotel based on request details, assign the new hotel admin
                $newHotel = Hotel::create([
                    'name' => $hotelAdminRequest->requested_hotel_name,
                    'location' => $hotelAdminRequest->requested_hotel_location,
                    'contact_person_phone' => $hotelAdminRequest->requested_contact_phone,
                    'photos_json' => $hotelAdminRequest->requested_photos_json,
                    'videos_json' => $hotelAdminRequest->requested_videos_json,
                    'notes' => 'Created from admin request. Request ID: ' . $hotelAdminRequest->request_id,
                    'admin_user_id' => $userToUpgrade->user_id,
                ]);

                // Optionally, update the request with a link to the created hotel
                // $hotelAdminRequest->update(['processed_hotel_id' => $newHotel->hotel_id]);
            }
            DB::commit();
            return response()->json(['message' => 'Request status updated successfully.', 'request' => $hotelAdminRequest]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update request status: ' . $e->getMessage()], 500);
        }
    }
}
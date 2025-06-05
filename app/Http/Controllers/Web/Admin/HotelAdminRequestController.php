<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelAdminRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class HotelAdminRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the hotel admin requests.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = HotelAdminRequest::with('user', 'reviewer')->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('request_status', $request->status);
        }

        $requests = $query->paginate(15);
        return view('admin.hotel_admin_requests.index', compact('requests'));
    }

    /**
     * Display the specified hotel admin request.
     */
    public function show(HotelAdminRequest $hotelAdminRequest): \Illuminate\View\View
    {
        $hotelAdminRequest->load('user', 'reviewer');
        return view('admin.hotel_admin_requests.show', compact('hotelAdminRequest'));
    }

    /**
     * Update the status of the specified hotel admin request.
     */
    public function updateRequestStatus(Request $request, HotelAdminRequest $hotelAdminRequest): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'rejection_reason' => 'nullable|string|max:500', // Optional field for rejection reason
        ]);

        if ($hotelAdminRequest->request_status !== 'pending') {
            return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                             ->with('error', __('Only pending requests can be reviewed.'));
        }

        $newStatus = $request->status;
        $hotelAdminRequest->request_status = $newStatus;
        $hotelAdminRequest->reviewed_by_user_id = Auth::id();
        $hotelAdminRequest->review_timestamp = now();
        // $hotelAdminRequest->rejection_reason = $request->rejection_reason; // Uncomment if you add this field to your migration
        $hotelAdminRequest->save();

        if ($newStatus === 'approved') {
            DB::beginTransaction();
            try {
                $userToUpgrade = User::find($hotelAdminRequest->user_id);

                if (!$userToUpgrade) {
                    DB::rollBack();
                    return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                     ->with('error', __('Request user not found. Status updated but user not upgraded.'));
                }

                if ($userToUpgrade->hasAnyRole(['hotel_admin', 'app_admin'])) {
                    DB::rollBack();
                    return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                     ->with('error', __('User already has an admin role. No upgrade performed.'));
                }

                // Upgrade user role
                $userToUpgrade->role = 'hotel_admin';
                $userToUpgrade->save();

                // Create a new hotel based on request data
                $newHotel = Hotel::create([
                    'name' => $hotelAdminRequest->requested_hotel_name,
                    'location' => $hotelAdminRequest->requested_hotel_location,
                    'contact_person_phone' => $hotelAdminRequest->requested_contact_phone,
                    'photos_json' => $hotelAdminRequest->requested_photos_json,
                    'videos_json' => $hotelAdminRequest->requested_videos_json,
                    'notes' => __('Created from admin request. Request ID: :id', ['id' => $hotelAdminRequest->request_id]),
                    'admin_user_id' => $userToUpgrade->user_id,
                ]);

                // Update request with processed hotel ID if you add this field to the migration
                // $hotelAdminRequest->processed_hotel_id = $newHotel->hotel_id;
                // $hotelAdminRequest->save();

                DB::commit();
                return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                 ->with('success', __('Request approved. User upgraded and hotel created.'));

            } catch (\Exception $e) {
                DB::rollBack();
                // Optionally revert request status if user/hotel creation failed
                // $hotelAdminRequest->update(['request_status' => 'pending', 'reviewed_by_user_id' => null, 'review_timestamp' => null]);
                return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                                 ->with('error', __('Failed to approve request: ') . $e->getMessage());
            }
        } elseif ($newStatus === 'rejected') {
            return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                             ->with('success', __('Request rejected.'));
        }

        return redirect()->route('admin.panel.hoteladminrequests.show', $hotelAdminRequest->request_id)
                         ->with('success', __('Request status updated.'));
    }
}
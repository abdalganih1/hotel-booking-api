<?php

namespace App\Http\Controllers\Api\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\HotelResource;

class HotelAdminHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:hotel_admin']); // Middleware للتحقق من الدور
    }

    /**
     * Display the hotel managed by the admin. (عرض بيانات الفندق)
     */
    public function showHotelDetails(Request $request)
    {
        $hotelAdmin = $request->user();
        $hotel = Hotel::where('admin_user_id', $hotelAdmin->user_id)->with('rooms')->firstOrFail();
        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

    /**
     * Update the specified hotel in storage. (إدارة بيانات الفندق - تفاصيل)
     */
    public function updateHotelDetails(Request $request)
    {
        $hotelAdmin = $request->user();
        $hotel = Hotel::where('admin_user_id', $hotelAdmin->user_id)->firstOrFail();

        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'rating' => 'sometimes|nullable|numeric|min:0|max:5',
            'location' => 'sometimes|nullable|string',
            'contact_person_phone' => 'sometimes|nullable|string',
            'photos_json' => 'sometimes|nullable|json',
            'videos_json' => 'sometimes|nullable|json',
            'notes' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hotel->update($request->only([
            'name', 'rating', 'location', 'contact_person_phone',
            'photos_json', 'videos_json', 'notes'
        ]));

        // return new HotelResource($hotel);
        return response()->json($hotel);
    }

     /**
     * Display the hotel's balance/earnings. (عرض رصيد الفندق - الأرباح)
     */
    public function showHotelBalance(Request $request)
    {
        $hotelAdmin = $request->user();
        // الرصيد هو مجموع معاملات 'hotel_commission' لهذا المستخدم
        $earnings = Transaction::where('user_id', $hotelAdmin->user_id)
                                ->where('reason', 'hotel_commission')
                                ->sum('amount');

        // يمكنك أيضًا عرض المعاملات نفسها
        $transactions = Transaction::where('user_id', $hotelAdmin->user_id)
                                    ->where('reason', 'hotel_commission')
                                    ->latest()
                                    ->paginate(15);

        return response()->json([
            'total_earnings' => $earnings,
            'commission_transactions' => $transactions // أو TransactionCollection
        ]);
    }
}
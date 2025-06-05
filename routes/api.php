<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\FaqController as PublicFaqController; // Alias to avoid conflict
use App\Http\Controllers\Api\HotelAdminRequestController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\UserController; // For user profile

// Admin Namespace Controllers
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminHotelController;
use App\Http\Controllers\Api\Admin\AdminFaqController;
use App\Http\Controllers\Api\Admin\AdminHotelAdminRequestController;
use App\Http\Controllers\Api\Admin\AdminFinancialController;
use App\Http\Controllers\Api\Admin\AdminRoomController; // If admin can manage rooms globally
use App\Http\Controllers\Api\Admin\AdminBookingController; // If admin can manage bookings globally

// HotelAdmin Namespace Controllers
use App\Http\Controllers\Api\HotelAdmin\HotelAdminHotelController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminRoomController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// --- Authentication Routes ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Publicly Accessible Routes (No Auth Required) ---
Route::get('/hotels', [HotelController::class, 'index'])->name('api.hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('api.hotels.show');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('api.rooms.show'); // Show specific room details
Route::get('/faqs', [PublicFaqController::class, 'index'])->name('api.faqs.index.public');
Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('api.paymentmethods.index.public'); // If payment methods are public

// --- Authenticated User Routes (All Roles - 'user', 'hotel_admin', 'app_admin') ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.auth.user'); // Get authenticated user details

    // User Profile Management (Example - can be expanded)
    Route::get('/profile', [UserController::class, 'showProfile'])->name('api.profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');

    // Bookings for authenticated user
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('api.bookings.my');
    Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('api.bookings.show.user'); // User's specific booking
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'requestCancellation'])->name('api.bookings.cancel');

    // Transactions and Balance for authenticated user
    Route::get('/my-balance', [TransactionController::class, 'index'])->name('api.balance.my');
    Route::post('/add-funds', [TransactionController::class, 'addFunds'])->name('api.balance.add');

    // Hotel Admin Role Requests
    Route::post('/hotel-admin-requests', [HotelAdminRequestController::class, 'store'])->name('api.hoteladminrequests.store');
    Route::get('/my-hotel-admin-requests', [HotelAdminRequestController::class, 'index'])->name('api.hoteladminrequests.my');
});


// --- Hotel Admin Specific Routes ---
Route::middleware(['auth:sanctum', 'role:hotel_admin'])->prefix('hotel-admin')->name('api.hoteladmin.')->group(function () {
    // Hotel Management (their specific hotel)
    Route::get('/hotel', [HotelAdminHotelController::class, 'showHotelDetails'])->name('hotel.details');
    Route::put('/hotel', [HotelAdminHotelController::class, 'updateHotelDetails'])->name('hotel.update');
    Route::get('/hotel/balance', [HotelAdminHotelController::class, 'showHotelBalance'])->name('hotel.balance');

    // Room Management for their hotel
    Route::apiResource('rooms', HotelAdminRoomController::class); // index, store, show, update, destroy

    // Booking Management for their hotel
    Route::get('/bookings', [HotelAdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HotelAdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [HotelAdminBookingController::class, 'updateBookingStatus'])->name('bookings.updatestatus');
});


// --- Application Admin Specific Routes ---
Route::middleware(['auth:sanctum', 'role:app_admin'])->prefix('admin')->name('api.admin.')->group(function () {
    // User Management
    Route::apiResource('users', AdminUserController::class);

    // Hotel Management (Global)
    Route::apiResource('hotels', AdminHotelController::class);
    // If admin needs to manage rooms globally (e.g., for any hotel)
    Route::apiResource('hotels.rooms', AdminRoomController::class)->shallow(); // Example: /admin/hotels/{hotel}/rooms AND /admin/rooms/{room}

    // FAQ Management
    Route::apiResource('faqs', AdminFaqController::class);

    // Hotel Admin Requests Management
    Route::get('hotel-admin-requests', [AdminHotelAdminRequestController::class, 'index'])->name('hoteladminrequests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [AdminHotelAdminRequestController::class, 'show'])->name('hoteladminrequests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [AdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hoteladminrequests.updatestatus');

    // Financial Management & Reports
    Route::get('financial/overview', [AdminFinancialController::class, 'financialOverview'])->name('financial.overview');
    Route::get('financial/transactions', [AdminFinancialController::class, 'listAllTransactions'])->name('financial.transactions.list');
    Route::post('financial/bookings/{booking}/process-commissions', [AdminFinancialController::class, 'processCommissionsForBooking'])->name('financial.bookings.processcommissions');

    // Payment Methods Management (if admin manages them)
    Route::apiResource('payment-methods', PaymentMethodController::class)->except(['index']); // index is public

    // Global Booking Management (if admin needs to view/manage all bookings)
    // Example, adjust if needed
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index.all');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show.all');
    // Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updatestatus.all'); // If admin can change status
});

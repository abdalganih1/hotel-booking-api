<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- Import Core API Controllers ---
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\FaqController as PublicFaqController; // Alias to avoid conflict with admin FAQ controller
use App\Http\Controllers\Api\HotelAdminRequestController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\UserController; // For general user profile management by the user themselves

// --- Import Admin Specific API Controllers ---
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminHotelController;
use App\Http\Controllers\Api\Admin\AdminFaqController;
use App\Http\Controllers\Api\Admin\AdminHotelAdminRequestController;
use App\Http\Controllers\Api\Admin\AdminFinancialController;
// If you plan to manage all rooms/bookings globally via API for app admin:
use App\Http\Controllers\Api\Admin\AdminRoomController;
use App\Http\Controllers\Api\Admin\AdminBookingController;

// --- Import Hotel Admin Specific API Controllers ---
use App\Http\Controllers\Api\HotelAdmin\HotelAdminHotelController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminRoomController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminBookingController;
use App\Http\Controllers\Api\HotelAdmin\HotelAdminFinancialController; // For hotel admin's own financial overview

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

// --- 1. Authentication Routes (Publicly Accessible) ---
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

// --- 2. Publicly Accessible Data Routes (No Auth Required) ---
// Hotels: List all hotels with basic info
Route::get('/hotels', [HotelController::class, 'index'])->name('api.hotels.index');
// Hotels: Show specific hotel details (including its rooms)
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('api.hotels.show');
// Rooms: Show specific room details
Route::get('/rooms', [RoomController::class, 'index'])->name('api.rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('api.rooms.show');
// FAQs: List all frequently asked questions
Route::get('/faqs', [PublicFaqController::class, 'index'])->name('api.faqs.index');
// Payment Methods: List all available payment methods
Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('api.payment_methods.index');


// --- 3. Authenticated User Routes (Require Sanctum Token) ---
// These routes are accessible by any authenticated user (user, hotel_admin, app_admin).
Route::middleware('auth:sanctum')->group(function () {

    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('api.user'); // Get current authenticated user's details

    // User Profile Management
    Route::get('/profile', [UserController::class, 'showProfile'])->name('api.profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');
    // Route::delete('/profile', [UserController::class, 'destroyProfile'])->name('api.profile.destroy'); // If user can delete their own profile

    // Bookings: List current user's bookings
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('api.bookings.my.index');
    // Bookings: Create a new booking
    Route::post('/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    // Bookings: Show specific booking details for current user
    Route::get('/my-bookings/{booking}', [BookingController::class, 'show'])->name('api.bookings.my.show');
    // Bookings: Request cancellation for a booking
    Route::post('/my-bookings/{booking}/cancel', [BookingController::class, 'requestCancellation'])->name('api.bookings.cancel');

    // Financials: View current user's balance and transactions
    Route::get('/my-balance', [TransactionController::class, 'index'])->name('api.balance.my.index');
    // Financials: Add funds to user's balance
    Route::post('/add-funds', [TransactionController::class, 'addFunds'])->name('api.balance.add');

    // Hotel Admin Request: Submit a request to become a hotel admin
    Route::post('/hotel-admin-requests', [HotelAdminRequestController::class, 'store'])->name('api.hotel_admin_requests.store');
    // Hotel Admin Request: View current user's hotel admin requests
    Route::get('/my-hotel-admin-requests', [HotelAdminRequestController::class, 'index'])->name('api.hotel_admin_requests.my.index');
    Route::get('/my-hotel-admin-requests/{hotelAdminRequest}', [HotelAdminRequestController::class, 'show'])->name('api.hotel_admin_requests.my.show');
});


// --- 4. Hotel Admin Specific API Routes (Require 'hotel_admin' Role) ---
// These routes are for hotel administrators to manage their assigned hotel.
Route::middleware(['auth:sanctum', 'role:hotel_admin'])->prefix('hotel-admin')->name('api.hotel_admin.')->group(function () {

    // Hotel Management (Their specific hotel)
    Route::get('/hotel', [HotelAdminHotelController::class, 'showHotelDetails'])->name('hotel.show');
    Route::put('/hotel', [HotelAdminHotelController::class, 'updateHotelDetails'])->name('hotel.update');
    // Note: Deleting a hotel is typically an App Admin task.

    // Rooms Management (for their specific hotel)
    // Uses shallow nesting, so /hotel-admin/rooms/{room} is global for their rooms.
    Route::apiResource('rooms', HotelAdminRoomController::class); // index, store, show, update, destroy

    // Bookings Management (for their specific hotel)
    Route::get('/bookings', [HotelAdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HotelAdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [HotelAdminBookingController::class, 'updateBookingStatus'])->name('bookings.update_status');

    // Financial Overview (for their specific hotel earnings)
    Route::get('/financials', [HotelAdminFinancialController::class, 'index'])->name('financials.index');
});


// --- 5. Application Admin Specific API Routes (Require 'app_admin' Role) ---
// These routes are for the main application administrator to manage the entire platform.
Route::middleware(['auth:sanctum', 'role:app_admin'])->prefix('admin')->name('api.admin.')->group(function () {

    // User Management (Global)
    Route::apiResource('users', AdminUserController::class);

    // Hotel Management (Global CRUD)
    Route::apiResource('hotels', AdminHotelController::class);

    // Rooms Management (Global, potentially nested under hotels or standalone)
    // Using shallow nesting for convenience: /admin/hotels/{hotel}/rooms or /admin/rooms/{room}
    // If you need global room management: Route::apiResource('rooms', AdminRoomController::class);
    // If you need nested only: Route::apiResource('hotels.rooms', AdminRoomController::class);
    // As per previous plan, we used shallow:
    Route::apiResource('hotels.rooms', AdminRoomController::class)->shallow();

    // FAQ Management (Global CRUD)
    Route::apiResource('faqs', AdminFaqController::class);

    // Hotel Admin Requests Management (Reviewing requests)
    Route::get('hotel-admin-requests', [AdminHotelAdminRequestController::class, 'index'])->name('hotel_admin_requests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [AdminHotelAdminRequestController::class, 'show'])->name('hotel_admin_requests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [AdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hotel_admin_requests.update_status');

    // Financial Management & Reports (Global)
    Route::get('financials/overview', [AdminFinancialController::class, 'financialOverview'])->name('financials.overview');
    Route::get('financials/transactions', [AdminFinancialController::class, 'listAllTransactions'])->name('financials.transactions.list');
    Route::post('financials/bookings/{booking}/process-commissions', [AdminFinancialController::class, 'processCommissionsForBooking'])->name('financials.bookings.process_commissions');

    // Payment Methods Management (Global CRUD)
    Route::apiResource('payment-methods', PaymentMethodController::class)->except(['index']); // Index is public

    // Booking Management (Global View/Monitor)
    // If app admin needs to manage all bookings globally:
    Route::apiResource('bookings', AdminBookingController::class)->only(['index', 'show']); // Only index and show by default
    // If app admin needs to update status of any booking: Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update_status');

});
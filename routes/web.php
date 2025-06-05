<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Web Controllers for Admin Panel
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\UserController as WebAdminUserController;
use App\Http\Controllers\Web\Admin\HotelController as WebAdminHotelController;
use App\Http\Controllers\Web\Admin\FaqController as WebAdminFaqController;
use App\Http\Controllers\Web\Admin\HotelAdminRequestController as WebAdminHotelAdminRequestController;
use App\Http\Controllers\Web\Admin\FinancialController as WebAdminFinancialController; // Imported
use App\Http\Controllers\Web\Admin\PaymentMethodController as WebAdminPaymentMethodController; // Imported
use App\Http\Controllers\Web\Admin\BookingController as WebAdminBookingController; // Imported
use App\Http\Controllers\Web\Admin\RoomController as WebAdminRoomController; // Import the Room Controller

use App\Http\Controllers\Web\HotelAdmin\DashboardController as HotelAdminDashboardController;
use App\Http\Controllers\Web\HotelAdmin\HotelController as HotelAdminHotelController;
use App\Http\Controllers\Web\HotelAdmin\RoomController as HotelAdminRoomController;
use App\Http\Controllers\Web\HotelAdmin\BookingController as HotelAdminBookingController;
use App\Http\Controllers\Web\HotelAdmin\FinancialController as HotelAdminFinancialController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Public/Default User Routes ---

// Default Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Default Dashboard (from Breeze)
// This dashboard is for general authenticated users (user, hotel_admin, app_admin)
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('app_admin')) {
            return redirect()->route('admin.panel.dashboard');
        } elseif (auth()->user()->hasRole('hotel_admin')) {
            // Re-direct Hotel Admins to their specific dashboard
            return redirect()->route('hotel_admin.panel.dashboard');
        }
    }
    return view('dashboard'); // Default Breeze dashboard for regular users
})->middleware(['auth', 'verified'])->name('dashboard');

// User Profile Routes (from Breeze)
// Accessible by any authenticated user for their own profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Laravel Breeze Authentication Routes ---
// This line includes all default login, register, password reset routes provided by Breeze.
require __DIR__.'/auth.php';


// --- Admin Panel Web Routes ---
// These routes are specifically for 'app_admin' role and are prefixed.
// They use the 'auth' middleware (for web sessions) and 'role:app_admin' custom middleware.
Route::middleware(['auth', 'role:app_admin'])->prefix('admin-panel')->name('admin.panel.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management (Web CRUD for Admin)
    Route::resource('users', WebAdminUserController::class);

    // Hotel Management (Web CRUD for Admin)
    Route::resource('hotels', WebAdminHotelController::class);
    // If Admin manages Rooms globally via web, uncomment and define WebAdminHotelRoomController
    // Route::resource('rooms', WebAdminRoomController::class); // Example if you want global room management
    Route::resource('hotels.rooms', WebAdminRoomController::class)->shallow();

    // FAQ Management (Web CRUD for Admin)
    Route::resource('faqs', WebAdminFaqController::class);

    // Hotel Admin Requests Management (Web)
    Route::get('hotel-admin-requests', [WebAdminHotelAdminRequestController::class, 'index'])->name('hoteladminrequests.index');
    Route::get('hotel-admin-requests/{hotelAdminRequest}', [WebAdminHotelAdminRequestController::class, 'show'])->name('hoteladminrequests.show');
    Route::patch('hotel-admin-requests/{hotelAdminRequest}/status', [WebAdminHotelAdminRequestController::class, 'updateRequestStatus'])->name('hoteladminrequests.updatestatus');

    // Financial Reports and Transactions Management
    Route::get('financials/overview', [WebAdminFinancialController::class, 'index'])->name('financials.overview'); // Renamed from 'overview' to 'index' to fit common resource naming, but you can keep 'overview' if preferred.
    Route::get('financials/transactions', [WebAdminFinancialController::class, 'transactions'])->name('financials.transactions');

    // Payment Methods Management (Web CRUD for Admin)
    Route::resource('payment-methods', WebAdminPaymentMethodController::class);

    // Global Booking Management (Web - View/Monitor by Admin)
    Route::resource('bookings', WebAdminBookingController::class)->only(['index', 'show']); // Only index and show for admin

});
// --- Hotel Admin Panel Web Routes ---
Route::middleware(['auth', 'role:hotel_admin'])->prefix('hotel-admin-panel')->name('hotel_admin.panel.')->group(function () {

    // Hotel Admin Dashboard
    Route::get('/dashboard', [HotelAdminDashboardController::class, 'index'])->name('dashboard');

    // Hotel Data Management (their specific hotel)
    Route::get('/hotel', [HotelAdminHotelController::class, 'show'])->name('hotel.show');
    Route::get('/hotel/edit', [HotelAdminHotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotel', [HotelAdminHotelController::class, 'update'])->name('hotel.update');

    // Rooms Management for their specific hotel (nested under hotel)
    Route::resource('/rooms', HotelAdminRoomController::class)->parameters([
        'hotel' => 'hotel_id' // Ensure parameter name is 'hotel_id' from the URL
    ]); // This creates hotel-admin-panel/hotel/rooms (index, create, store, edit, update, show, destroy)

    // Booking Management for their specific hotel
    Route::get('/bookings', [HotelAdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [HotelAdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [HotelAdminBookingController::class, 'updateStatus'])->name('bookings.update_status');

    // Financial Overview for their specific hotel (earnings)
    Route::get('/financials', [HotelAdminFinancialController::class, 'index'])->name('financials.index');
});

// --- Fallback Route (Optional) ---
// Route::fallback(function () {
//     abort(404);
// });
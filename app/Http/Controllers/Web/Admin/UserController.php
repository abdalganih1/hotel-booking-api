<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:app_admin']);
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $query = User::orderBy('user_id', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                  ->orWhere('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role') && in_array($request->role, ['user', 'hotel_admin', 'app_admin'])) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): \Illuminate\View\View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'role' => ['required', Rule::in(['user', 'hotel_admin', 'app_admin'])],
        ]);

        User::create([
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']), // Laravel expects 'password' field in fillable
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
            'gender' => $validatedData['gender'],
            'age' => $validatedData['age'],
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('admin.panel.users.index')->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): \Illuminate\View\View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): \Illuminate\View\View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->user_id, 'user_id')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($user->user_id, 'user_id')],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'role' => ['required', Rule::in(['user', 'hotel_admin', 'app_admin'])],
        ]);

        $updateData = $validatedData;
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($updateData['password']); // Remove password from update if not provided
        }

        $user->update($updateData);

        return redirect()->route('admin.panel.users.index')->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if (Auth::id() === $user->user_id) {
            return redirect()->route('admin.panel.users.index')->with('error', __('You cannot delete your own account.'));
        }

        // Check for dependencies before deleting
        // In a real application, you might prevent deletion or soft delete instead.
        if ($user->bookings()->exists()) {
            return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing bookings.'));
        }
        if ($user->transactions()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing transactions.'));
        }
        if ($user->hotelAdminFor()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user who manages a hotel. Unassign first.'));
        }
        if ($user->hotelAdminRequests()->exists()) {
             return redirect()->route('admin.panel.users.index')->with('error', __('Cannot delete user with existing hotel admin requests.'));
        }


        $user->delete();
        return redirect()->route('admin.panel.users.index')->with('success', __('User deleted successfully.'));
    }
}
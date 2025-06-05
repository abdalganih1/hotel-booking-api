<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\UserResource;
// use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:app_admin']);
    }

    public function index(Request $request)
    {
        // TODO: Filtering by role, search by username/email
        $users = User::latest()->paginate(15);
        // return new UserCollection($users);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        // TODO: Validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,hotel_admin,app_admin',
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create(array_merge(
            $request->except('password'),
            ['password_hash' => Hash::make($request->password)]
        ));
        // return new UserResource($user);
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        // return new UserResource($user);
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        // TODO: Validation (similar to store, but unique checks might need ignoring current user)
         $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8', // Password is optional on update
            'role' => 'sometimes|required|in:user,hotel_admin,app_admin',
            // ... other fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }
        $user->update($data);
        // return new UserResource($user);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        // TODO: Consider soft deletes or what happens to related data
        // Don't allow admin to delete themselves
        if ($user->user_id === Auth::id()) {
             return response()->json(['message' => 'لا يمكنك حذف حسابك الخاص.'], 403);
        }
        $user->delete();
        return response()->json(null, 204);
    }
}
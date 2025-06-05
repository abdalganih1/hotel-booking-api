<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Array of allowed roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) { // Should be protected by 'auth' middleware first
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();

        if (!$user->hasAnyRole($roles)) { // hasAnyRole() is a hypothetical method
        // Implement your logic here to check if $user->role is in $roles array
        // For example:
        // if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Forbidden. You do not have the required role.'], 403);
        }

        return $next($request);
    }
}
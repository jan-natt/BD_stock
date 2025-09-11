<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Make sure user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check user role
        if (Auth::user()->user_type !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

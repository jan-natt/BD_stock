<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (!auth()->user()->hasRole($role)) {
        abort(403, 'Unauthorized access.');
    }

    return $next($request);
}
}

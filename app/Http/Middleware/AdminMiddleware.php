<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        if (!auth()->check() || auth()->user()->user_type !== 'admin') {
    return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
}


        return $next($request);
    }
}
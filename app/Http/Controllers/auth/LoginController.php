<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect role based
            if ($user->user_type == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->user_type == 'buyer') {
                return redirect()->route('buyer.dashboard');
            } elseif ($user->user_type == 'seller') {
                return redirect()->route('seller.dashboard');
            }

            return redirect('/'); // default
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}

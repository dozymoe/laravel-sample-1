<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm(Request $request)
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        if (auth()->attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
                '' => 'The provided credentials does not match our records.',
            ])
            ->onlyInput('email');
    }

    public function doLogout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

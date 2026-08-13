<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

// Login Page
public function index()
{
    return view('login');
}


// Login
public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->remember)) {

            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Welcome '.auth()->user()->name);
        }

        return back()
            ->withErrors([
                'email' => 'Invalid email or password.'
            ])
            ->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

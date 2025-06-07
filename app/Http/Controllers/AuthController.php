<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->route('home');
            } elseif ($user->role_id == 2) {
                return redirect()->route('dashboard');
            } else {
                Auth::logout(); // unknown role
                return back()->withErrors(['login' => 'Role tidak dikenali.']);
            }
        }

        return back()->withErrors(['login' => 'Email atau password salah']);
    }

    public function showRegister()
    {
        return view('auth.register'); // tidak perlu kirim roles ke view
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'phone'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role_id'  => 1, // default sebagai user
        ]);

        Auth::login($user);
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

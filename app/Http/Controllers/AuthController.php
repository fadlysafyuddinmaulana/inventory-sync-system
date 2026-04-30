<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Determine which column to use for login (username, email or name)
        try {
            if (Schema::hasColumn('users', 'username')) {
                $loginColumn = 'username';
            } elseif (Schema::hasColumn('users', 'email')) {
                $loginColumn = 'email';
            } elseif (Schema::hasColumn('users', 'name')) {
                $loginColumn = 'name';
            } else {
                return back()->withErrors([
                    'username' => 'Kolom autentikasi tidak ditemukan pada tabel users.',
                ])->onlyInput('username');
            }

            $user = User::where($loginColumn, $credentials['username'])->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                return redirect()->intended(route('dashboard'))->with('success', 'Login berhasil');
            }
        } catch (QueryException $e) {
            // If database schema is not ready or column missing, return a friendly message
            return back()->withErrors([
                'username' => 'Terjadi kesalahan database: ' . $e->getMessage(),
            ])->onlyInput('username');
        }

        return back()->withErrors([
            'username' => 'Username atau password tidak sesuai.',
        ])->onlyInput('username');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil');
    }
}

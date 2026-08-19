<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Neispravni podaci za prijavu.',
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Uspešno ste odjavljeni.']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'admin' => $request->user('admin'),
        ]);
    }
}
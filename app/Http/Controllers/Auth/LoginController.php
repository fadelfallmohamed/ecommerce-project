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
        $credentials = $request->validate([
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt([
            'nom' => $credentials['nom'],
            'prenom' => $credentials['prenom'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'nom' => 'Les informations de connexion sont incorrectes.',
        ])->onlyInput('nom', 'prenom');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
} 
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function show(): View
    {
        return view('panel.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('panel')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        if (! Auth::guard('panel')->user()->is_active) {
            Auth::guard('panel')->logout();

            throw ValidationException::withMessages([
                'email' => 'Hesabınız pasif durumda. Yöneticinizle görüşün.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/panel');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('panel')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('panel.login');
    }
}

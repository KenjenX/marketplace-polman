<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // proses login
        $request->authenticate();

        // regenerate session
        $request->session()->regenerate();

        $user = Auth::user();

        /**
         * CEK EMAIL VERIFIED
         * Jika belum verifikasi:
         * - tetap authenticated
         * - diarahkan ke halaman verify-email Laravel
         * - dashboard tetap aman karena middleware verified
         */
        if (!$user->hasVerifiedEmail()) {

            return redirect()
                ->route('verification.notice')
                ->with(
                    'warning',
                    'Silakan verifikasi email Anda terlebih dahulu.'
                );
        }

        /**
         * Jika email sudah verified
         */
        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
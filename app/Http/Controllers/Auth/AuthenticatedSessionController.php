<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
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
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('demo_mode');

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Authenticate a guest into the seeded demo admin account.
     */
    public function demoLogin(Request $request): RedirectResponse
    {
        $demoUser = User::query()
            ->where('email', 'demo-admin@reza-inventory.test')
            ->first();

        if (!$demoUser || !$demoUser->hasRole('demo-admin')) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'demo_login' => __('Demo access is not available right now.'),
                ]);
        }

        Auth::login($demoUser);

        $request->session()->regenerate();
        $request->session()->put('demo_mode', true);

        return redirect()->intended(RouteServiceProvider::HOME);
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

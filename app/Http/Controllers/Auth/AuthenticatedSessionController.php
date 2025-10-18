<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location; // For IP geolocation (optional)

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

        $user = auth()->user();

        // Initialize Agent
        $agent = new Agent();

        // Get IP and location data
        $ip = $request->ip();
        $location = Location::get($ip);

        try {
            $user->loginHistories()->create([
                'user_ip' => $ip,
                'city' => $location?->cityName ?? null,
                'country' => $location?->countryName ?? null,
                'country_code' => $location?->countryCode ?? null,
                'longitude' => $location?->longitude ?? null,
                'latitude' => $location?->latitude ?? null,
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to save login history: '.$e->getMessage());
        }

        return redirect()->intended(route('dashboard', absolute: false));
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

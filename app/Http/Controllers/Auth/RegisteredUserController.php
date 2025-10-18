<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));


        // Track registration
        $agent = new Agent();
        $ip = $request->ip();
        $location = Location::get($ip);

        $user->loginHistories()->create([
            'user_ip' => $ip,
            'city' => $location?->cityName ?? null,
            'country' => $location?->countryName ?? null,
            'country_code' => $location?->countryCode ?? null,
            'longitude' => $location?->longitude ?? null,
            'latitude' => $location?->latitude ?? null,
            'browser' => $agent->browser(),
            'os' => $agent->platform(),
            'created_at' => now(), // Explicitly set to distinguish registration
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

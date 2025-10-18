<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function completeProfile()
    {
        if (Auth::user()->completed_profile) {
            return redirect()->route('dashboard');
        }

        return view('profile.complete');
    }
    public function submitCompletion(Request $request)
    {
        $data = $request->validate([
            'username'     => 'required|string|min:6|unique:users,username,' . auth()->id(),
            'phone'        => 'required|string|unique:users,phone,' . auth()->id(),
            'country'      => 'required|string',
            'country_code' => 'required|string',
        ]);
        $normalized = Str::lower($data['username']);

        $user = auth()->user();
        $user->username     = $normalized;
        $user->phone        = $data['phone'];
        $user->country      = $data['country'];
        $user->country_code = $data['country_code'];
        $user->completed_profile = true;
        $user->save();

        return redirect()->route('dashboard');
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
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
        $identity = $request->user();
        $validated = $request->validated();
        $emailChanged = $identity->email !== $validated['email'];

        DB::transaction(function () use ($identity, $validated, $emailChanged) {
            $identity->user()->update([
                'full_name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $identity->update([
                'email' => $validated['email'],
                'is_verified' => $emailChanged ? false : $identity->is_verified,
            ]);
        });

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

        $identity = $request->user();
        $profile = $identity->user;

        Auth::logout();

        DB::transaction(function () use ($identity, $profile) {
            $identity->delete();
            $profile?->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserIdentity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.UserIdentity::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $identity = DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->name,
                'is_registration_complete' => 0,
            ]);

            return UserIdentity::create([
                'id' => $user->id,
                'email' => $request->email,
                'provider' => 'basic',
                'hash' => Hash::make($request->password),
                'role' => 'user',
                'is_verified' => 0,
                'verification_token' => Str::random(40),
                'verification_token_expiration' => now()->addDay(),
            ]);
        });

        event(new Registered($identity));

        Auth::login($identity);

        return redirect(route('dashboard', absolute: false));
    }
}

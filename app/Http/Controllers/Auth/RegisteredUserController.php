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
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Adds a brief note about showing registration form.
     * Note: Shows the form where new users can register.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Adds a note on creating a new user and authenticating.
     * Note: Validates input fields, creates a new user, then logs them in.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,tutor'],
            'category' => $request->role === 'student'
                ? ['required', 'in:primary,lower_secondary,upper_secondary']
                : ['nullable', 'in:primary,lower_secondary,upper_secondary'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'category' => $request->role === 'student'
                    ? $request->category
                    : null,
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}

<!-- /**
 * This Blade template renders the login form for the application.
 * 
 * Components used:
 * - <x-guest-layout>: Layout for guest users.
 * - <x-auth-session-status>: Displays session status messages.
 * - <x-input-label>: Label for form inputs.
 * - <x-text-input>: Text input field.
 * - <x-input-error>: Displays validation error messages.
 * - <x-primary-button>: Primary button for form submission.
 * 
 * Form fields:
 * - Username or Email: Text input for username or email.
 * - Password: Password input field.
 * - Remember Me: Checkbox to remember the user.
 * - Forgot Password: Link to reset password.
 * - Not registered?: Link to the registration page.
 * 
 * The form uses POST method and submits to the 'login' route.
 * CSRF protection is included using @csrf directive.
 */ -->

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username or Email -->
        <div>
            <x-input-label for="login" :value="__('Username or Email')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="login" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me and Forgot Password -->
        <div class="block mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 me-auto" href="{{ route('register') }}">
            {{ __('Not registered?') }}
            </a>

            <x-primary-button class="ms-3">
            {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

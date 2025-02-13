<!-- /**
 * This Blade template renders the registration form for new users.
 * 
 * The form includes fields for:
 * - Name
 * - Username
 * - Email Address
 * - Password
 * - Confirm Password
 * - Role (Student or Tutor)
 * - Category (Primary, Lower Secondary, Upper Secondary) - visible only if the role is 'Student'
 * 
 * The form uses Laravel Blade components for input fields, labels, and error messages.
 * 
 * JavaScript is used to toggle the visibility of the Category field based on the selected Role.
 * 
 * @component x-guest-layout
 * @method POST
 * @action route('register')
 * @csrf
 * 
 * @field name (text, required, autofocus, autocomplete="name")
 * @field username (text, required, autocomplete="username")
 * @field email (email, required, autocomplete="username")
 * @field password (password, required, autocomplete="new-password")
 * @field password_confirmation (password, required, autocomplete="new-password")
 * @field role (select, required, options: ['student', 'tutor'], onchange="toggleCategoryField()")
 * @field category (select, options: ['primary', 'lower_secondary', 'upper_secondary'])
 * 
 * @link route('login') - Link to the login page for users who are already registered.
 * 
 * @script toggleCategoryField - Toggles the visibility of the Category field based on the selected Role.
 * @script DOMContentLoaded - Initializes the Category field visibility based on the default selected Role.
 */ -->

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full rounded-md" required onchange="toggleCategoryField()">
                <option value="student">{{ __('Student') }}</option>
                <option value="tutor">{{ __('Tutor') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Category -->
        <div id="category-field" class="mt-4">
            <x-input-label for="category" :value="__('Category')" />
            <select id="category" name="category" class="block mt-1 w-full rounded-md">
                <option value="primary">{{ __('Primary') }}</option>
                <option value="lower_secondary">{{ __('Lower Secondary') }}</option>
                <option value="upper_secondary">{{ __('Upper Secondary') }}</option>
            </select>
            <x-input-error :messages="$errors->get('category')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleCategoryField() {
            var role = document.getElementById('role').value;
            var categoryField = document.getElementById('category-field');
            if (role === 'tutor') {
                categoryField.style.display = 'none';
            } else {
                categoryField.style.display = 'block';
            }
        }

        // Initialize the category field visibility based on the default selected role
        document.addEventListener('DOMContentLoaded', function() {
            toggleCategoryField();
        });
    </script>
</x-guest-layout>

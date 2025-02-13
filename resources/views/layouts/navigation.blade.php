<!-- /**
 * This Blade template defines the navigation layout for the application.
 * 
 * The navigation bar includes:
 * - A logo that links to the dashboard.
 * - Navigation links that are conditionally displayed based on the user's authentication status and role.
 * - A settings dropdown for authenticated users to access their profile and log out.
 * - Registration and login buttons for unauthenticated users.
 * - A responsive menu for smaller screens that includes the same links and settings options.
 * 
 * The navigation links include:
 * - Dashboard: Visible to authenticated users.
 * - Admin: Visible to authenticated users with the 'is_admin' attribute.
 * - Upload: Visible to authenticated users with the 'tutor' role.
 * - Material: Visible to all users.
 * 
 * The settings dropdown includes:
 * - Profile: Links to the profile edit page.
 * - Log Out: Logs the user out.
 * 
 * The responsive menu includes:
 * - The same navigation links as the primary menu.
 * - The same settings options as the primary menu.
 * 
 * The navigation bar uses Alpine.js for handling the responsive menu toggle.
 */ -->

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::check())    
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                        </x-nav-link>
                    
                        @if(Auth::user()->is_admin)
                            <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @endif
                        @if(Auth::user()->role === 'tutor')
                            <x-nav-link :href="route('materials.create')" :active="request()->routeIs('materials.create')">
                                {{ __('Upload') }}
                            </x-nav-link>
                        @endif
                    @endif
                    <x-nav-link :href="route('materials.index')" :active="request()->routeIs('materials.index')">
                        {{ __('Material') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(Auth::check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="mt-4">
                        <a href="{{ route('register') }}">
                            <x-primary-button>
                                {{ __('Register') }}
                            </x-primary-button>
                        </a>
                    </div>
                    <div class="mx-6">
                        &nbsp;
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('login') }}">
                            <x-secondary-button>
                                {{ __('Log In') }}
                            </x-secondary-button>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check())
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                @if(Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                @endif
            @endif
            <x-responsive-nav-link :href="route('materials.index')" :active="request()->routeIs('materials.index')">
                {{ __('Material') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @if(Auth::check())
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>
    </div>
</nav>

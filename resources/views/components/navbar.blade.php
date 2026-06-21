<nav class="bg-white w-full z-20 top-0 sticky inset-s-0 border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">

        <a href="{{ route('games.index') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-2xl font-extrabold tracking-tight text-purple-600">Game<span
                    class="text-gray-900">Pedia</span></span>
        </a>

        <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">

            @auth
                <button type="button"
                    class="flex items-center gap-2 text-sm bg-white rounded-full focus:ring-4 focus:ring-purple-300 md:me-0"
                    id="user-menu-button" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="text-purple-700 text-sm font-bold uppercase">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                    <span class="hidden md:block text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-500 hidden md:block" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div class="z-50 hidden bg-white border border-gray-200 rounded-xl shadow-lg w-48" id="user-dropdown">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <span class="block text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                        <span class="block text-xs text-gray-500 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <ul class="p-2 text-sm" aria-labelledby="user-menu-button">
                        <li>
                            <button onclick="openProfileModal()"
                                class="inline-flex items-center gap-2 w-full px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-purple-600 rounded-lg transition-colors text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Edit Profile
                            </button>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 w-full px-3 py-2 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-lg transition-colors text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @guest
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-medium text-gray-600 hover:text-purple-600 px-3 py-2 transition-colors">
                        Register
                    </a>
                </div>
            @endguest

            <button data-collapse-toggle="navbar-sticky" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14" />
                </svg>
            </button>
        </div>

        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul
                class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                <li>
                    <a href="{{ route('games.index') }}"
                        class="block py-2 px-3 md:p-0 transition-colors {{ request()->routeIs('games.*') ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                        Browse
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlist.index') }}"
                        class="block py-2 px-3 md:p-0 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                        Wishlist
                    </a>
                </li>
                <li>
                    <a href="{{ route('community.index') }}"
                        class="block py-2 px-3 md:p-0 transition-colors {{ request()->routeIs('community.*') ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                        Community
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index') }}"
                        class="block py-2 px-3 md:p-0 transition-colors {{ request()->routeIs('chat.*') ? 'text-purple-600 font-bold' : 'text-gray-700 hover:text-purple-600' }}">
                        Chat AI
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @auth
        {{-- Modal Edit Profile --}}
        <div id="profile-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Edit Profile</h3>
                    <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-6">

                    {{-- Success: profile --}}
                    @if (session('status') === 'profile-information-updated')
                        <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                            Profile updated successfully.
                        </div>
                    @endif

                    {{-- Success: password --}}
                    @if (session('status') === 'password-updated')
                        <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                            Password updated successfully.
                        </div>
                    @endif

                    {{-- Form: Name & Email --}}
                    <form action="{{ route('user-profile-information.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <p class="text-sm font-semibold text-gray-700 mb-3">Account Info</p>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                    class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500
                                    {{ $errors->updateProfileInformation->has('name') ? 'border-red-400' : 'border-gray-300' }}">
                                @if ($errors->updateProfileInformation->has('name'))
                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $errors->updateProfileInformation->first('name') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500
                                    {{ $errors->updateProfileInformation->has('email') ? 'border-red-400' : 'border-gray-300' }}">
                                @if ($errors->updateProfileInformation->has('email'))
                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $errors->updateProfileInformation->first('email') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-4 w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </form>

                    <hr class="border-gray-100">

                    {{-- Form: Password --}}
                    <form action="{{ route('user-password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <p class="text-sm font-semibold text-gray-700 mb-3">Change Password</p>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Current Password</label>
                                <input type="password" name="current_password"
                                    class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500
                                    {{ $errors->updatePassword->has('current_password') ? 'border-red-400' : 'border-gray-300' }}">
                                @if ($errors->updatePassword->has('current_password'))
                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $errors->updatePassword->first('current_password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">New Password</label>
                                <input type="password" name="password"
                                    class="w-full border rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500
                                    {{ $errors->updatePassword->has('password') ? 'border-red-400' : 'border-gray-300' }}">
                                @if ($errors->updatePassword->has('password'))
                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $errors->updatePassword->first('password') }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-4 w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 rounded-lg transition-colors">
                            Update Password
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <script>
            function openProfileModal() {
                document.getElementById('profile-modal').classList.remove('hidden');
            }

            function closeProfileModal() {
                document.getElementById('profile-modal').classList.add('hidden');
            }

            document.getElementById('profile-modal').addEventListener('click', function(e) {
                if (e.target === this) closeProfileModal();
            });

            // Auto-buka modal kalau ada error atau success dari Fortify
            @if (
                $errors->updateProfileInformation->any() ||
                    $errors->updatePassword->any() ||
                    session('status') === 'profile-information-updated' ||
                    session('status') === 'password-updated')
                document.addEventListener('DOMContentLoaded', () => openProfileModal());
            @endif
        </script>
    @endauth
</nav>

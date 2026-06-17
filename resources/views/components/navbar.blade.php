<nav class="bg-white w-full z-20 top-0 inset-s-0 border-b border-gray-200 shadow-sm">
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
            </ul>
        </div>
    </div>
</nav>

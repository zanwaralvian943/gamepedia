<footer class="bg-neutral-primary-soft rounded-base shadow-xs border border-default m-4">
    <div class="w-full mx-auto max-w-7xl p-4 md:flex md:items-center md:justify-between">
        <span class="text-sm text-body sm:text-center">© 2026 <a href="https://flowbite.com/"
                class="hover:underline">Gamepedia™</a>. All Rights Reserved.
        </span>
        <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-body sm:mt-0">
            <li>
                <a href="{{ route('games.index') }}" class="hover:underline me-4 md:me-6">Browse</a>
            </li>
            <li>
                <a href="{{ route('wishlist.index') }}" class="hover:underline me-4 md:me-6">Wishlist</a>
            </li>
            <li>
                <a href="{{ route('community.index') }}" class="hover:underline me-4 md:me-6">Community</a>
            </li>
        </ul>
    </div>
</footer>

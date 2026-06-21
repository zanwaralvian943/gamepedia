@props(['game', 'isWishlisted' => false])

@php
    $slug = $game['slug'];
    $image = $game['background_image'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600';
    $released = isset($game['released']) ? \Carbon\Carbon::parse($game['released'])->format('d M Y') : 'N/A';
    $isWishlisted =
        auth()->check() &&
        \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('game_slug', $slug)
            ->exists();
@endphp

<div class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
    <div>
        <a href="{{ route('games.show', $slug) }}">
            <img class="w-full h-48 object-cover" src="{{ $image }}" alt="{{ $game['name'] }}">
        </a>

        <div class="p-5 text-center">
            <span
                class="inline-flex items-center bg-purple-100 border border-purple-200 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                ⭐ {{ $game['rating'] }} / 5
            </span>

            <div class="flex flex-wrap justify-center gap-1.5 mt-2">
                @foreach ($game['genres'] as $genre)
                    <span
                        class="inline-flex items-center bg-purple-100 border border-purple-200 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        {{ $genre['name'] }}
                    </span>
                @endforeach
            </div>

            <a href="{{ route('games.show', $slug) }}">
                <h5
                    class="mt-3 mb-2 text-lg font-bold tracking-tight text-gray-900 hover:text-purple-600 transition-colors line-clamp-1">
                    {{ $game['name'] }}
                </h5>
            </a>

            <p class="text-xs text-gray-500 mb-4">Release date: {{ $released }}</p>
        </div>
    </div>

    <div class="p-5 pt-0">
        @auth
            <form action="{{ route('wishlist.toggle') }}" method="POST">
                @csrf
                <input type="hidden" name="game_slug" value="{{ $slug }}">
                <input type="hidden" name="game_name" value="{{ $game['name'] }}">
                <input type="hidden" name="image" value="{{ $image }}">

                <button type="submit" title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to Wishlist' }}"
                    class="inline-flex items-center justify-center w-full text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors">
                    + Wishlist
                </button>
            </form>
        @endauth

        @guest
            <a href="{{ route('login') }}"
                class="inline-flex items-center justify-center w-full text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors">
                + Wishlist
            </a>
        @endguest
    </div>
</div>

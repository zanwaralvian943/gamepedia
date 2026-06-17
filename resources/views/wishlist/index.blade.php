@extends('layouts.app')
@section('title', 'Gamepedia - My Wishlist')
@section('content')

    <div class="max-w-7xl mx-auto p-4 md:p-6">
        <div class="mb-3">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-2">
                My Wishlist
            </h1>
            <p class="text-lg font-thin text-gray-900 mb-6">Track your most anticipated gaming experiences.</p>
        </div>


        @if ($wishlists->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($wishlists as $item)
                    <div
                        class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">

                        <div>
                            <img class="w-full h-48 object-cover"
                                src="{{ $item->image ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600' }}"
                                alt="{{ $item->game_name }}">
                        </div>

                        <div class="p-5 text-center flex-grow flex flex-col justify-center">
                            <a href="{{ route('games.show', $item->game_slug) }}">
                                <h5
                                    class="mb-2 text-lg font-bold tracking-tight text-gray-900 hover:text-purple-600 transition-colors line-clamp-2">
                                    {{ $item->game_name }}
                                </h5>
                            </a>

                            <p class="text-xs text-gray-400 mt-1">
                                Saved on: {{ $item->created_at->format('d M Y') }}
                            </p>

                        </div>

                        <div class="p-5 pt-0 flex gap-2">
                            <a href="{{ route('games.show', $item->game_slug) }}"
                                class="flex-grow inline-flex items-center text-white bg-purple-600 hover:bg-purple-700 font-semibold rounded-xl text-sm px-4 py-2.5 justify-center transition-colors">
                                View Details
                            </a>
                            <form action="{{ route('wishlist.toggle') }}" method="POST" class="inline m-0">
                                @csrf
                                <input type="hidden" name="game_slug" value="{{ $item->game_slug }}">
                                <input type="hidden" name="game_name" value="{{ $item->game_name }}">
                                <button type="submit"
                                    class="inline-flex items-center p-2.5 text-sm font-medium rounded-xl border border-red-200 bg-red-50 text-red-500 hover:bg-red-100 transition-colors cursor-pointer">
                                    Remove
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-gray-500 font-medium">Belum ada game yang kamu tambahkan ke wishlist.</p>
                <a href="{{ route('games.index') }}"
                    class="inline-block mt-4 text-sm font-bold text-purple-600 hover:underline">Mulai Jelajahi Game →</a>
            </div>
        @endif
    </div>

@endsection

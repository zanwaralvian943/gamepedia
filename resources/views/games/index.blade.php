@extends('layouts.app')
@section('title', 'Gamepedia - Discover Games')

@section('content')
    <div class="py-8 px-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-2">Discover Games</h1>
                <p class="text-lg font-thin text-gray-900">Explore the latest releases, top-rated classics, and hidden indie
                    gems.</p>
            </div>

            <form action="{{ route('games.index') }}" method="GET" class="w-full max-w-xl">
                <div
                    class="flex items-stretch h-11 rounded-xl overflow-hidden border border-gray-300 shadow-sm divide-x divide-gray-300">

                    <select name="genre"
                        class="w-36 shrink-0 bg-white text-gray-900 text-sm px-3 border-0 focus:outline-none cursor-pointer appearance-none">
                        <option value="">All genres</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre['slug'] }}" @selected(request('genre') === $genre['slug'])>
                                {{ $genre['name'] }}
                            </option>
                        @endforeach
                    </select>

                    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search the game"
                        class="flex-1 min-w-0 bg-white text-gray-900 text-sm px-3 border-0 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500">

                    <button type="submit"
                        class="shrink-0 inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-5 transition-colors focus:outline-none">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        Search
                    </button>

                </div>
            </form>
        </div>


        @if ($games)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($games as $game)
                    <x-game-card :game="$game" :isWishlisted="in_array($game['slug'], $wishlistedSlugs)" />
                @endforeach
            </div>
        @else
            <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 border border-yellow-200" role="alert">
                <span class="font-medium">Failed</span> to get game data.
            </div>
        @endif

    </div>


    <x-pagination :currentPage="$currentPage" :totalPages="$totalPages" />

@endsection

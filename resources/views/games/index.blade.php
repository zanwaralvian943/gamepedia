@extends('layouts.app')
@section('title', 'Gamepedia - Discover Games')
@section('content')
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">

        <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-2">
            Discover Games
        </h1>
        <p class="text-lg font-thin text-gray-900 mb-6">Explore the latest releases, top-rated classics, and hidden indie
            gems.</p>

        @if (count($games) > 0)

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @foreach ($games as $game)
                    <div
                        class="bg-gray-50 border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <a href="#">
                                <img class="w-full h-48 object-cover"
                                    src="{{ $game['background_image'] ?? 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=600' }}"
                                    alt="{{ $game['name'] }}" />
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


                                <a href="#">
                                    <h5
                                        class="mt-3 mb-2 text-lg font-bold tracking-tight text-gray-900 hover:text-purple-600 transition-colors line-clamp-1">
                                        {{ $game['name'] }}
                                    </h5>
                                </a>

                                <p class="text-xs text-gray-500 mb-4">
                                    Release date:
                                    {{ isset($game['released']) ? \Carbon\Carbon::parse($game['released'])->format('d M Y') : 'N/A' }}
                                </p>

                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <a href="#"
                                class="inline-flex items-center text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 w-full justify-center transition-colors">
                                + Wishlist
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        @else
            <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 border border-yellow-200" role="alert">
                <span class="font-medium">Waduh!</span> Gagal mengambil data game.
            </div>
        @endif

    </div>
    <nav aria-label="Page navigation Gamepedia" class="flex justify-center mt-8 pt-6 border-t border-gray-200">
        <ul class="flex -space-x-px text-sm">

            <li>
                @if ($currentPage > 1)
                    <a href="{{ route('games.index', ['page' => $currentPage - 1]) }}"
                        class="flex items-center justify-center text-gray-500 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-purple-600 font-medium rounded-s-lg text-sm w-10 h-10 focus:outline-none transition-colors">
                        <span class="sr-only">Previous</span>
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </a>
                @else
                    <button disabled
                        class="flex items-center justify-center text-gray-300 bg-gray-50 box-border border border-gray-200 font-medium rounded-s-lg text-sm w-10 h-10 cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="w-4 h-4 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </button>
                @endif
            </li>

            @php
                $current = (int) $currentPage;
                $total = (int) $totalPages;

                $start = max(1, $current - 2);
                $end = min($total, $start + 4);

                if ($end - $start < 4) {
                    $start = max(1, $end - 4);
                }
            @endphp

            @for ($i = $start; $i <= $end; $i++)
                <li>
                    @if ($i == $currentPage)
                        <a href="#" aria-current="page"
                            class="flex items-center justify-center text-purple-600 bg-purple-50 box-border border border-purple-300 font-bold text-sm w-10 h-10 focus:outline-none">
                            {{ $i }}
                        </a>
                    @else
                        <a href="{{ route('games.index', ['page' => $i]) }}"
                            class="flex items-center justify-center text-gray-700 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-purple-600 font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                            {{ $i }}
                        </a>
                    @endif
                </li>
            @endfor

            <li>
                @if ($currentPage < $totalPages)
                    <a href="{{ route('games.index', ['page' => $currentPage + 1]) }}"
                        class="flex items-center justify-center text-gray-500 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-purple-600 font-medium rounded-e-lg text-sm w-10 h-10 focus:outline-none transition-colors">
                        <span class="sr-only">Next</span>
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <button disabled
                        class="flex items-center justify-center text-gray-300 bg-gray-50 box-border border border-gray-200 font-medium rounded-e-lg text-sm w-10 h-10 cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </button>
                @endif
            </li>

        </ul>
    </nav>
@endsection

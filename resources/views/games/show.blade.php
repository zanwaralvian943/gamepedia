@extends('layouts.app')
@section('title', 'Gamepedia - Discover Games')
@section('content')


    <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <div class="relative h-56 overflow-hidden rounded-base md:h-96">
            @if (count($screenshots) > 0)
                @foreach ($screenshots as $index => $screen)
                    <div class="hidden duration-200 ease-linear" data-carousel-item="{{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ $screen['image'] }}"
                            class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Screenshot {{ $game['name'] }}">
                    </div>
                @endforeach
            @else
                <div class="duration-200 ease-linear" data-carousel-item="active">
                    <img src="{{ $game['background_image'] }}"
                        class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                        alt="{{ $game['name'] }}">
                </div>
            @endif
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            <button type="button" class="w-3 h-3 rounded-base" aria-current="true" aria-label="Slide 1"
                data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 2"
                data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 3"
                data-carousel-slide-to="2"></button>
            <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 4"
                data-carousel-slide-to="3"></button>
            <button type="button" class="w-3 h-3 rounded-base" aria-current="false" aria-label="Slide 5"
                data-carousel-slide-to="4"></button>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-base bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-5 h-5 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-base bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-5 h-5 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m9 5 7 7-7 7" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
    <section class="bg-white dark:bg-gray-900 mb-20">
        <div
            class="gap-16 pt-2 items-center pb-8 px-4 mx-auto max-w-screen-xl lg:grid lg:grid-cols-2 lg:pb-16 lg:pt-2 lg:px-6">
            <div class="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                <div class="">
                    @foreach ($game['genres'] as $genre)
                        <span
                            class="inline-flex items-center bg-purple-100 border border-purple-200 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $genre['name'] }}
                        </span>
                    @endforeach
                </div>
                <h2 class="text-3xl font-bold ">{{ $game['name'] }}</h2>
                <div class="flex gap-5">
                    <h2>⭐ {{ $game['rating'] }} / 5</h2>
                    |
                    <h2> {{ isset($game['released']) ? \Carbon\Carbon::parse($game['released'])->format('d M Y') : 'N/A' }}
                    </h2>
                    |
                    <h2 class="flex items-center gap-1.5">
                        @foreach ($game['platforms'] ?? [] as $p)
                            @php
                                $slug = $p['platform']['slug'] ?? '';
                                $name = $p['platform']['name'] ?? '';

                                $icon = match (true) {
                                    str_starts_with($slug, 'playstation') => 'fa-playstation',
                                    str_starts_with($slug, 'xbox') => 'fa-xbox',
                                    $slug === 'pc' => 'fa-windows',
                                    $slug === 'nintendo-switch' => 'fa-nintendo-switch',
                                    str_contains($slug, 'ios') => 'fa-apple',
                                    str_contains($slug, 'android') => 'fa-android',
                                    default => null,
                                };
                            @endphp

                            @if ($icon)
                                <i class="fab {{ $icon }} text-base" title="{{ $name }}"></i>
                            @endif
                        @endforeach
                    </h2>
                </div>
                <div class="py-3">
                    <h2 class="text2xl font-bold ">
                        About The Game
                    </h2>
                    <p class='text-base font-thin'>{{ strip_tags($game['description']) }}</p>
                </div>
                <div class="py-3">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        System Requirements
                    </h2>

                    @php
                        $pcPlatform = collect($game['platforms'] ?? [])->first(function ($p) {
                            return strtolower($p['platform']['slug'] ?? '') === 'pc';
                        });
                        $minimum = $pcPlatform['requirements']['minimum'] ?? null;
                        $recommended = $pcPlatform['requirements']['recommended'] ?? null;
                    @endphp

                    @if ($minimum || $recommended)
                        {{-- Tab Buttons --}}
                        <div class="flex gap-2 mb-4" id="req-tabs">
                            @if ($minimum)
                                <button onclick="showTab('minimum')" id="tab-minimum"
                                    class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 border border-purple-200 text-purple-800 cursor-pointer">
                                    Minimum PC Specifications
                                </button>
                            @endif
                            @if ($recommended)
                                <button onclick="showTab('recommended')" id="tab-recommended"
                                    class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-600 cursor-pointer">
                                    Recommended PC Specifications
                                </button>
                            @endif
                        </div>

                        {{-- Minimum --}}
                        @if ($minimum)
                            <div id="req-minimum" class="req-panel">
                                @include('games.partials.requirements', ['raw' => $minimum])
                            </div>
                        @endif

                        {{-- Recommended --}}
                        @if ($recommended)
                            <div id="req-recommended" class="req-panel hidden">
                                @include('games.partials.requirements', ['raw' => $recommended])
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-gray-400">System requirements tidak tersedia.</p>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-8">
                {{-- community  --}}
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        function showTab(tab) {
            document.querySelectorAll('.req-panel').forEach(el => el.classList.add('hidden'));
            document.getElementById('req-' + tab).classList.remove('hidden');

            document.querySelectorAll('#req-tabs button').forEach(btn => {
                btn.classList.remove('bg-purple-100', 'border-purple-200', 'text-purple-800');
                btn.classList.add('bg-gray-100', 'border-gray-200', 'text-gray-600');
            });
            const active = document.getElementById('tab-' + tab);
            active.classList.remove('bg-gray-100', 'border-gray-200', 'text-gray-600');
            active.classList.add('bg-purple-100', 'border-purple-200', 'text-purple-800');
        }
    </script>
@endpush

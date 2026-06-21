@props(['currentPage', 'totalPages'])

@php
    $current = (int) $currentPage;
    $total = (int) $totalPages;
    $start = max(1, min($current - 2, $total - 4));
    $end = min($total, $start + 4);

    $baseParams = array_filter(['search' => request('search'), 'genre' => request('genre')]);
    $pageUrl = fn($page) => route('games.index', array_merge($baseParams, ['page' => $page]));
@endphp

<nav aria-label="Page navigation" class="flex justify-center mt-8 pt-6 border-t border-gray-200">
    <ul class="flex -space-x-px text-sm">

        {{-- Prev --}}
        <li>
            @if ($current > 1)
                <a href="{{ $pageUrl($current - 1) }}"
                    class="flex items-center justify-center w-10 h-10 border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-purple-600 rounded-s-lg transition-colors">
                    <span class="sr-only">Previous</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m15 19-7-7 7-7" />
                    </svg>
                </a>
            @else
                <span
                    class="flex items-center justify-center w-10 h-10 border border-gray-200 bg-gray-50 text-gray-300 rounded-s-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m15 19-7-7 7-7" />
                    </svg>
                </span>
            @endif
        </li>

        {{-- Pages --}}
        @for ($i = $start; $i <= $end; $i++)
            <li>
                @if ($i === $current)
                    <span aria-current="page"
                        class="flex items-center justify-center w-10 h-10 border border-purple-300 bg-purple-50 text-purple-600 font-bold">{{ $i }}</span>
                @else
                    <a href="{{ $pageUrl($i) }}"
                        class="flex items-center justify-center w-10 h-10 border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 hover:text-purple-600 font-medium transition-colors">{{ $i }}</a>
                @endif
            </li>
        @endfor

        {{-- Next --}}
        <li>
            @if ($current < $total)
                <a href="{{ $pageUrl($current + 1) }}"
                    class="flex items-center justify-center w-10 h-10 border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-purple-600 rounded-e-lg transition-colors">
                    <span class="sr-only">Next</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m9 5 7 7-7 7" />
                    </svg>
                </a>
            @else
                <span
                    class="flex items-center justify-center w-10 h-10 border border-gray-200 bg-gray-50 text-gray-300 rounded-e-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m9 5 7 7-7 7" />
                    </svg>
                </span>
            @endif
        </li>

    </ul>
</nav>

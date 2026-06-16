@php
    $lines = preg_split('/\r\n|\r|\n/', strip_tags($raw));
    $parsed = [];
    foreach ($lines as $line) {
        if (str_contains($line, ':')) {
            [$key, $val] = explode(':', $line, 2);
            $key = trim(preg_replace('/^(Minimum|Recommended)\s*/i', '', $key));
            $val = trim($val);
            if ($key && $val) {
                $parsed[$key] = $val;
            }
        }
    }
    $icons = [
        'OS' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        'Processor' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H7a2 2 0 00-2 2v2M9 3h6M9 3v2m6-2h2a2 2 0 012 2v2m0 0V7m0 0h2M3 9v6m18-6v6M3 15v2a2 2 0 002 2h2m0 0h6m-6 0v2m6-2h2a2 2 0 002-2v-2m0 0V9"/></svg>',
        'Memory' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 8v4l3 3"/></svg>',
        'Graphics' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87V15.13a1 1 0 01-1.447.9L15 14M3 8h12a2 2 0 012 2v4a2 2 0 01-2 2H3a2 2 0 01-2-2v-4a2 2 0 012-2z"/></svg>',
        'Storage' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/></svg>',
        'DirectX' =>
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @forelse ($parsed as $key => $value)
        <div
            class="flex items-start gap-3 bg-gray-50 dark:bg-gray-800 rounded-xl p-3 border border-gray-100 dark:border-gray-700">
            <div class="text-purple-600 mt-0.5 shrink-0">
                {!! $icons[$key] ?? $icons['DirectX'] !!}
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">{{ $key }}</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $value }}</p>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 col-span-2">Data tidak tersedia.</p>
    @endforelse
</div>

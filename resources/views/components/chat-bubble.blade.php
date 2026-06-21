@props(['type', 'time' => null, 'msgId' => null])

@if ($type === 'user')
    <div class="flex justify-end gap-3 md:gap-4">
        <div class="max-w-[90%] md:max-w-[85%] bg-purple-600 text-white rounded-2xl p-4 shadow-sm relative">
            <p class="text-sm leading-relaxed pb-3">{{ $slot }}</p>
            @if ($time)
                <span class="absolute bottom-1.5 right-4 text-[9px] text-purple-200">{{ $time }}</span>
            @endif
        </div>
    </div>
@else
    <div class="flex justify-start gap-3 md:gap-4">
        <div
            class="w-8 h-8 rounded-full bg-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
            <i class="fas fa-headset text-xs"></i>
        </div>
        <div
            class="max-w-[90%] md:max-w-[85%] bg-white border border-gray-200 text-gray-800 rounded-2xl p-4 md:p-5 shadow-sm relative">
            <div class="ai-bubble text-sm leading-relaxed pb-3"
                @if ($msgId) data-msg-id="{{ $msgId }}" @endif>
                {{ $slot }}
            </div>
            @if ($time)
                <span class="absolute bottom-1.5 left-4 text-[9px] text-gray-400">{{ $time }}</span>
            @endif
        </div>
    </div>
@endif

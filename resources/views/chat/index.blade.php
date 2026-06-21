@extends('layouts.chat_layout')
@section('title', 'Gamepedia AI - Room')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
@endpush

@section('content')
    <div class="flex h-screen w-screen relative overflow-hidden">

        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden cursor-pointer transition-opacity">
        </div>

        <div id="sidebar"
            class="w-72 bg-white border-r border-gray-200 flex flex-col absolute md:relative z-40 h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-gamepad text-sm"></i>
                    </div>
                    <h2 class="text-base font-extrabold text-gray-900 tracking-tight">GamePedia AI</h2>
                </div>
                <button id="close-sidebar"
                    class="md:hidden w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-600 transition-colors focus:outline-none">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-4">
                <a href="{{ route('chat.index') }}"
                    class="flex items-center justify-center gap-2 w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition-colors">
                    <i class="fas fa-plus text-xs"></i> New Chat
                </a>
            </div>

            <div class="flex-1 overflow-y-auto px-3 pb-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-2">History</p>
                <ul class="space-y-1" id="session-list">
                    @foreach ($sessions as $session)
                        @php $isActive = isset($activeSession) && $activeSession->id === $session->id; @endphp
                        <li class="group relative" data-session-id="{{ $session->id }}">
                            <a href="{{ route('chat.index', $session->id) }}"
                                class="session-link flex items-center gap-3 px-3 py-2.5 pr-16 rounded-lg text-sm transition-colors border-l-4
                                    {{ $isActive ? 'bg-purple-50 text-purple-700 font-bold border-purple-600' : 'text-gray-600 font-medium hover:bg-gray-50 border-transparent' }}">
                                <span class="text-sm flex-shrink-0 {{ $isActive ? 'text-purple-600' : 'text-gray-400' }}">
                                    <i class="{{ $isActive ? 'fas fa-comment-dots' : 'far fa-message' }}"></i>
                                </span>
                                <span class="session-title truncate flex-1">{{ $session->title }}</span>
                            </a>
                            <div
                                class="absolute right-2 top-1/2 -translate-y-1/2 hidden group-hover:flex items-center gap-1">
                                <button onclick="Chat.startRename(this, {{ $session->id }})" title="Rename"
                                    class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-pencil text-[10px]"></i>
                                </button>
                                <button onclick="Chat.deleteSession(this, {{ $session->id }})" title="Hapus"
                                    class="w-6 h-6 flex items-center justify-center rounded text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50">
                <a href="/"
                    class="flex items-center justify-center gap-2 w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 text-sm font-medium py-2 rounded-lg transition-colors shadow-xs">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-gray-50/50 relative h-full">
            <div
                class="px-4 md:px-6 py-3 border-b border-gray-200 bg-white flex justify-between items-center shadow-xs z-10">
                <div class="flex items-center gap-3 md:gap-4">
                    <button id="open-sidebar" class="md:hidden text-gray-600 hover:text-purple-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-base md:text-lg font-extrabold text-gray-900 leading-tight">Gamepedia Assistant</h1>
                </div>
            </div>

            <div class="flex-1 p-4 md:p-8 overflow-y-auto space-y-6" id="chat-container">
                <x-chat-bubble type="ai">
                    Halo! Gua GamePedia AI. Ada yang bisa dibantu untuk memaksimalkan pengalaman gaming kamu hari ini?
                </x-chat-bubble>

                @foreach ($messages as $msg)
                    <x-chat-bubble type="user" :time="$msg->created_at->setTimezone('Asia/Jakarta')->format('H:i')">
                        {{ $msg->prompt }}
                    </x-chat-bubble>
                    <x-chat-bubble type="ai" :time="$msg->created_at->setTimezone('Asia/Jakarta')->format('H:i')" :msgId="$msg->id">
                    </x-chat-bubble>
                @endforeach
            </div>

            <script>
                const _msgData = @json($messages->pluck('response', 'id'));
                const _streamUrl = '{{ route('chat.stream') }}';
            </script>

            <div class="p-3 md:p-6 bg-white border-t border-gray-200">
                <div class="max-w-4xl mx-auto">
                    <div
                        class="flex gap-2 items-center bg-white border border-gray-300 p-1.5 md:p-2 rounded-xl focus-within:ring-2 focus-within:ring-purple-200 focus-within:border-purple-400 transition-all shadow-sm">
                        <input type="hidden" id="session-id" value="{{ $activeSession->id ?? '' }}">
                        <input type="text" id="prompt-input" autocomplete="off"
                            placeholder="Ketik pesan atau pertanyaan kamu di sini..."
                            class="flex-grow border-0 focus:ring-0 text-gray-900 text-sm px-2 md:px-3 py-2 placeholder:text-gray-400 bg-transparent w-full">
                        <button id="send-btn"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors cursor-pointer shadow-sm">
                            <i class="fas fa-paper-plane" id="send-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/chat.js')
@endpush

@extends('layouts.chat_layout')
@section('title', 'Gamepedia AI - Room')
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
                <ul class="space-y-1">
                    @foreach ($sessions as $session)
                        <li>
                            <a href="{{ route('chat.index', $session->id) }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors 
                               {{ isset($activeSession) && $activeSession->id == $session->id
                                   ? 'bg-purple-50 text-purple-700 font-bold border-l-4 border-purple-600'
                                   : 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-transparent' }}">
                                <span
                                    class="text-sm {{ isset($activeSession) && $activeSession->id == $session->id ? 'text-purple-600' : 'text-gray-400' }}">
                                    @if (isset($activeSession) && $activeSession->id == $session->id)
                                        <i class="fas fa-comment-dots"></i>
                                    @else
                                        <i class="far fa-message"></i>
                                    @endif
                                </span>
                                <span class="truncate">{{ $session->title }}</span>
                            </a>
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
                    <div
                        class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center hidden md:flex">
                        <i class="fas fa-bolt text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-base md:text-lg font-extrabold text-gray-900 leading-tight">Gamepedia Assistant</h1>
                        <p class="text-[10px] md:text-xs text-gray-500 font-medium truncate w-48 md:w-auto">Analyzing game
                            data, patch notes, and strategies...</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 p-4 md:p-8 overflow-y-auto space-y-6" id="chat-container">
                <div class="flex justify-start gap-3 md:gap-4">
                    <div
                        class="w-8 h-8 rounded-full bg-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
                        <i class="fas fa-headset text-xs"></i>
                    </div>
                    <div
                        class="max-w-[90%] md:max-w-[85%] bg-white border border-gray-200 text-gray-800 rounded-2xl p-4 md:p-5 shadow-sm">
                        <p class="text-sm leading-relaxed">Halo! Gua GamePedia AI. Ada yang bisa dibantu untuk memaksimalkan
                            pengalaman gaming kamu hari ini?</p>
                    </div>
                </div>

                @foreach ($messages as $msg)
                    <div class="flex justify-end gap-3 md:gap-4">
                        <div class="max-w-[90%] md:max-w-[85%] bg-purple-600 text-white rounded-2xl p-4 shadow-sm relative">
                            <p class="text-sm leading-relaxed pb-3">{{ $msg->prompt }}</p>
                            <span class="absolute bottom-1.5 right-4 text-[9px] text-purple-200">
                                {{ $msg->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-start gap-3 md:gap-4">
                        <div
                            class="w-8 h-8 rounded-full bg-purple-600 flex-shrink-0 flex items-center justify-center text-white text-sm mt-1 shadow-sm">
                            <i class="fas fa-headset text-xs"></i>
                        </div>
                        <div
                            class="max-w-[90%] md:max-w-[85%] bg-white border border-gray-200 text-gray-800 rounded-2xl p-4 md:p-5 shadow-sm relative">
                            <div class="text-sm leading-relaxed whitespace-pre-wrap pb-3">{{ $msg->response }}</div>
                            <span class="absolute bottom-1.5 left-4 text-[9px] text-gray-400">
                                {{ $msg->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-3 md:p-6 bg-white border-t border-gray-200">
                <div class="max-w-4xl mx-auto">
                    <form action="{{ route('chat.send') }}" method="POST"
                        class="flex gap-2 m-0 items-center bg-white border border-gray-300 p-1.5 md:p-2 rounded-xl focus-within:ring-2 focus-within:ring-purple-200 focus-within:border-purple-400 transition-all shadow-sm"
                        id="chat-form">
                        @csrf
                        <input type="hidden" name="chat_session_id" value="{{ $activeSession->id ?? '' }}">
                        <input type="text" name="prompt" required autocomplete="off"
                            placeholder="Ketik pesan atau pertanyaan kamu di sini..."
                            class="flex-grow border-0 focus:ring-0 text-gray-900 text-sm px-2 md:px-3 py-2 placeholder:text-gray-400 bg-transparent w-full">
                        <button type="submit"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors cursor-pointer shadow-sm">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const container = document.getElementById('chat-container');
        container.scrollTop = container.scrollHeight;

        document.getElementById('chat-form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });

        // Sidebar Toggle Mobile
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const btnOpen = document.getElementById('open-sidebar');
        const btnClose = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        btnOpen.addEventListener('click', toggleSidebar);
        btnClose.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
@endpush

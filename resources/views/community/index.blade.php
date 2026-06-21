@extends('layouts.app')
@section('title', 'Gamepedia - Community')
@section('content')

    <section class="bg-white min-h-screen pb-20">
        <div class="max-w-4xl px-4 py-10 mx-auto lg:px-6">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Community Forum</h2>
                <p class="text-sm text-gray-400 mt-1">{{ $posts->total() }} diskusi dari semua game</p>
            </div>
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($posts as $post)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4 hover:border-purple-200 transition-colors">
                    <div class="flex gap-4">
                        <a href="{{ route('community.game', $post->game_slug) }}" class="shrink-0">
                            @if ($post->game_image)
                                <img src="{{ $post->game_image }}"
                                    class="w-16 h-16 rounded-xl object-cover border border-gray-100"
                                    alt="{{ $post->game_name }}">
                            @else
                                <div
                                    class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <div class="flex-1 min-w-0">
                            <a href="{{ route('community.game', $post->game_slug) }}"
                                class="inline-block text-xs font-semibold text-purple-700 bg-purple-100 px-2.5 py-0.5 rounded-full mb-2 hover:bg-purple-200 transition-colors">
                                {{ $post->game_name }}
                            </a>
                            <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1">{{ $post->title }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed line-clamp-2 mb-3">{{ $post->body }}</p>

                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-purple-700 text-xs font-bold uppercase">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-gray-600">{{ $post->user->name }}</span>
                                </div>
                                <span>•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span>{{ $post->all_comments_count }} Comment</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-sm">Belum ada diskusi.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

@endsection

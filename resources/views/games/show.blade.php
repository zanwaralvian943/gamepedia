@extends('layouts.app')
@section('title', 'Gamepedia - ' . $game['name'])
@section('content')

    <div class="w-full bg-gray-200">
        <div class="w-full bg-gray-100 overflow-hidden">
            <div id="default-carousel" class="relative w-full max-w-7xl mx-auto" data-carousel="slide">
                <div class="relative h-64 sm:h-80 md:h-112.5 overflow-hidden w-full">
                    @if (count($screenshots) > 0)
                        @foreach ($screenshots as $index => $screen)
                            <div class="hidden duration-200 ease-linear w-full h-full"
                                data-carousel-item="{{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ $screen['image'] }}" class="w-full h-full object-cover object-center"
                                    alt="Screenshot {{ $game['name'] }}">
                            </div>
                        @endforeach
                    @else
                        <div class="duration-200 ease-linear w-full h-full" data-carousel-item="active">
                            <img src="{{ $game['background_image'] }}" class="w-full h-full object-cover object-center"
                                alt="{{ $game['name'] }}">
                        </div>
                    @endif
                </div>
                <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                    @foreach ($screenshots as $index => $screen)
                        <button type="button" class="w-3 h-3 rounded-full bg-black/30"
                            aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"
                            data-carousel-slide-to="{{ $index }}"></button>
                    @endforeach
                </div>
                <button type="button"
                    class="absolute top-0 inset-s-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/60 group-hover:bg-white/80 transition-all">
                        <svg class="w-5 h-5 text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 inset-e-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/60 group-hover:bg-white/80 transition-all">
                        <svg class="w-5 h-5 text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <section class="bg-white">
        <div class="max-w-4xl px-4 py-8 mx-auto lg:px-6">

            <div class="mb-2">
                @foreach ($game['genres'] as $genre)
                    <span
                        class="inline-flex items-center bg-purple-100 border border-purple-200 text-purple-700 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-1 mb-1">
                        {{ $genre['name'] }}
                    </span>
                @endforeach
            </div>

            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $game['name'] }}</h2>
            <div class="flex flex-wrap gap-4 items-center text-sm font-medium text-gray-500 mb-4">
                <span>⭐ {{ $game['rating'] }} / 5</span>
                <span class="text-gray-300">|</span>
                <span>📅
                    {{ isset($game['released']) ? \Carbon\Carbon::parse($game['released'])->format('d M Y') : 'N/A' }}</span>
                <span class="text-gray-300">|</span>
                <span class="flex items-center gap-2">
                    @php
                        $uniquePlatforms = collect($game['platforms'] ?? [])->unique(function ($p) {
                            $slug = $p['platform']['slug'] ?? '';
                            return match (true) {
                                str_starts_with($slug, 'playstation') => 'playstation',
                                str_starts_with($slug, 'xbox') => 'xbox',
                                $slug === 'pc' => 'pc',
                                $slug === 'nintendo-switch' => 'nintendo',
                                str_contains($slug, 'ios') => 'ios',
                                str_contains($slug, 'android') => 'android',
                                default => $slug,
                            };
                        });
                    @endphp
                    @foreach ($uniquePlatforms as $p)
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
                            <i class="fab {{ $icon }} text-base me-1 text-gray-500"
                                title="{{ $name }}"></i>
                        @endif
                    @endforeach
                </span>
            </div>


            <div class="mb-6">
                @auth
                    <form action="{{ route('wishlist.toggle') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="game_slug" value="{{ $game['slug'] }}">
                        <input type="hidden" name="game_name" value="{{ $game['name'] }}">
                        <input type="hidden" name="image" value="{{ $game['background_image'] ?? '' }}">
                        @php
                            $isWishlisted = \App\Models\Wishlist::where('user_id', Auth::id())
                                ->where('game_slug', $game['slug'])
                                ->exists();
                        @endphp
                        <button type="submit"
                            class="inline-flex items-center text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            {{ $isWishlisted ? 'Remove from wishlist' : '+ Add to Wishlist' }}
                        </button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        + Add to Wishlist
                    </a>
                @endguest
            </div>

            <div class="py-5 border-t border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">About The Game</h3>
                <p class="text-base text-gray-600 leading-relaxed">
                    {{ strip_tags($game['description']) }}
                </p>
            </div>

            <div class="py-5 border-t border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">System Requirements</h3>
                @php
                    $pcPlatform = collect($game['platforms'] ?? [])->first(
                        fn($p) => strtolower($p['platform']['slug'] ?? '') === 'pc',
                    );
                    $minimum = $pcPlatform['requirements']['minimum'] ?? null;
                    $recommended = $pcPlatform['requirements']['recommended'] ?? null;
                @endphp
                @if ($minimum || $recommended)
                    <div class="flex gap-2 mb-4" id="req-tabs">
                        @if ($minimum)
                            <button onclick="showTab('minimum')" id="tab-minimum"
                                class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 border border-purple-200 text-purple-700 cursor-pointer">
                                Minimum PC Specs
                            </button>
                        @endif
                        @if ($recommended)
                            <button onclick="showTab('recommended')" id="tab-recommended"
                                class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-500 cursor-pointer">
                                Recommended PC Specs
                            </button>
                        @endif
                    </div>
                    @if ($minimum)
                        <div id="req-minimum" class="req-panel text-sm">
                            @include('games.partials.requirements', ['raw' => $minimum])
                        </div>
                    @endif
                    @if ($recommended)
                        <div id="req-recommended" class="req-panel text-sm hidden">
                            @include('games.partials.requirements', ['raw' => $recommended])
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-400">System requirements tidak tersedia.</p>
                @endif
            </div>

        </div>
    </section>

    <section class="bg-gray-50 border-t border-gray-200 pb-20">
        <div class="max-w-4xl px-4 py-10 mx-auto lg:px-6">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">💬 Community Forum</h3>
                    <p class="text-sm text-gray-400 mt-1">
                        {{ $totalPosts }} diskusi untuk <span
                            class="font-medium text-gray-600">{{ $game['name'] }}</span>
                    </p>
                </div>
                @auth
                    <button onclick="toggleNewPost()"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Post
                    </button>
                @endauth
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div id="new-post-form" class="hidden mb-8">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5">
                        <h4 class="font-semibold text-gray-900 mb-4">Add New Post</h4>
                        <form action="{{ route('community.store', $game['slug']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="game_name" value="{{ $game['name'] }}">
                            <input type="hidden" name="game_image" value="{{ $game['background_image'] ?? '' }}">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" required maxlength="150" placeholder="Judul diskusi..."
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none">
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                                <textarea name="body" required rows="4" maxlength="5000" placeholder="Tulis diskusimu di sini..."
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none resize-none"></textarea>
                                @error('body')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                                    Post
                                </button>
                                <button type="button" onclick="toggleNewPost()"
                                    class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2 rounded-lg transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth

            @if ($recentPosts->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-sm">Belum ada diskusi. Jadilah yang pertama!</p>
                </div>
            @else
                <div class="space-y-4 mb-6">
                    @foreach ($recentPosts as $post)
                        <div class="bg-white border border-gray-200 rounded-2xl p-5">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div
                                        class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                        <span class="text-purple-700 text-xs font-bold uppercase">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-semibold text-gray-800">{{ $post->user->name }}</span>
                                        <span
                                            class="text-xs text-gray-400 ml-2">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @auth
                                    @if (Auth::id() === $post->user_id)
                                        <form action="{{ route('community.post.destroy', $post->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus diskusi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-red-500 hover:text-red-700 shrink-0">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>

                            <h4 class="font-semibold text-gray-900 mb-1">{{ $post->title }}</h4>
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $post->body }}</p>

                            <div class="mt-3 flex items-center gap-4">
                                <button onclick="toggleComments({{ $post->id }})"
                                    class="text-xs text-purple-600 hover:underline font-medium">
                                    {{ $post->allComments->count() }} Comment
                                </button>
                                @auth
                                    <button onclick="toggleReplyForm('post-reply-{{ $post->id }}')"
                                        class="text-xs text-gray-400 hover:text-gray-600 font-medium">
                                        Reply
                                    </button>
                                @endauth
                            </div>

                            @auth
                                <div id="post-reply-{{ $post->id }}" class="hidden mt-3">
                                    <form action="{{ route('community.comment', $post->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="">
                                        <textarea name="body" rows="2" required maxlength="2000" placeholder="Tulis komentarmu..."
                                            class="w-full rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none resize-none"></textarea>
                                        <div class="flex gap-2 mt-2">
                                            <button type="submit"
                                                class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                                Send
                                            </button>
                                            <button type="button"
                                                onclick="toggleReplyForm('post-reply-{{ $post->id }}')"
                                                class="text-gray-400 hover:text-gray-600 text-xs px-3 py-1.5 rounded-lg">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            <div id="comments-{{ $post->id }}"
                                class="hidden mt-4 space-y-3 border-t border-gray-100 pt-4">
                                @forelse ($post->comments as $comment)
                                    @include('community.partials.comment', [
                                        'comment' => $comment,
                                        'depth' => 0,
                                    ])
                                @empty
                                    <p class="text-xs text-gray-400">Belum ada komentar.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($totalPosts > 3)
                    <div class="text-center">
                        <a href="{{ route('community.index', $game['slug']) }}"
                            class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 text-sm font-medium">
                            Lihat semua {{ $totalPosts }} diskusi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function showTab(tab) {
            document.querySelectorAll('.req-panel').forEach(el => el.classList.add('hidden'));
            document.getElementById('req-' + tab).classList.remove('hidden');
            document.querySelectorAll('#req-tabs button').forEach(btn => {
                btn.classList.remove('bg-purple-100', 'border-purple-200', 'text-purple-700');
                btn.classList.add('bg-gray-100', 'border-gray-200', 'text-gray-500');
            });
            const active = document.getElementById('tab-' + tab);
            active.classList.remove('bg-gray-100', 'border-gray-200', 'text-gray-500');
            active.classList.add('bg-purple-100', 'border-purple-200', 'text-purple-700');
        }

        function toggleNewPost() {
            const el = document.getElementById('new-post-form');
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) el.querySelector('input[name="title"]').focus();
        }

        function toggleComments(postId) {
            document.getElementById('comments-' + postId).classList.toggle('hidden');
        }

        function toggleReplyForm(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) el.querySelector('textarea').focus();
        }

        @if ($errors->any())
            document.getElementById('new-post-form')?.classList.remove('hidden');
        @endif
    </script>
@endpush

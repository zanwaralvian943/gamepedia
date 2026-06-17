@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('content')

    <section class="bg-white  min-h-screen pb-20">
        <div class="max-w-4xl px-4 py-10 mx-auto lg:px-6">

            <a href="{{ route('games.show', $slug) }}"
                class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-purple-600  mb-6 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to detail game
            </a>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 ">💬 Forum Diskusi</h2>
                    <p class="text-sm text-gray-500  mt-1">{{ $posts->total() }} diskusi</p>
                </div>
                <button onclick="toggleNewPost()"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Post
                </button>
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div id="new-post-form" class="hidden mb-8">
                <div class="bg-gray-50  border border-gray-200  rounded-2xl p-5">
                    <h4 class="font-semibold text-gray-900  mb-4">Add New Post</h4>
                    <form action="{{ route('community.store', $slug) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700  mb-1">Title</label>
                            <input type="text" name="title" required maxlength="150" value="{{ old('title') }}"
                                placeholder="Judul diskusi..."
                                class="w-full rounded-lg border border-gray-300  bg-white  text-gray-900  text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700  mb-1">Body</label>
                            <textarea name="body" required rows="4" maxlength="5000" placeholder="Tulis diskusimu..."
                                class="w-full rounded-lg border border-gray-300  bg-white  text-gray-900  text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none resize-none">{{ old('body') }}</textarea>
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
                                class="text-gray-500 text-sm px-4 py-2 rounded-lg hover:text-gray-700 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @forelse ($posts as $post)
                <div class="bg-white  border border-gray-200  rounded-2xl p-5 mb-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-purple-100  flex items-center justify-center shrink-0">
                                <span class="text-purple-700  text-xs font-bold uppercase">
                                    {{ substr($post->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800 ">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-400 ml-2">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if (Auth::id() === $post->user_id)
                            <form action="{{ route('community.post.destroy', $post->id) }}" method="POST"
                                onsubmit="return confirm('Hapus diskusi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600 shrink-0">Delete</button>
                            </form>
                        @endif
                    </div>

                    <h4 class="font-semibold text-gray-900  mb-1">{{ $post->title }}</h4>
                    <p class="text-sm text-gray-600  leading-relaxed">{{ $post->body }}</p>

                    <div class="mt-3 flex items-center gap-4">
                        <button onclick="toggleComments({{ $post->id }})"
                            class="text-xs text-purple-600  hover:underline font-medium">
                            {{ $post->all_comments_count }} Comment
                        </button>
                        <button onclick="toggleReplyForm('post-reply-{{ $post->id }}')"
                            class="text-xs text-gray-500 hover:text-gray-700  font-medium">
                            Reply
                        </button>
                    </div>

                    <div id="post-reply-{{ $post->id }}" class="hidden mt-3">
                        <form action="{{ route('community.comment', $post->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="">
                            <textarea name="body" rows="2" required maxlength="2000" placeholder="Tulis komentarmu..."
                                class="w-full rounded-lg border border-gray-300  bg-gray-50  text-gray-900  text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none resize-none"></textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                    Send
                                </button>
                                <button type="button" onclick="toggleReplyForm('post-reply-{{ $post->id }}')"
                                    class="text-gray-400 text-xs px-3 py-1.5 rounded-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="comments-{{ $post->id }}" class="hidden mt-4 space-y-3 border-t border-gray-100  pt-4">
                        @forelse ($post->comments as $comment)
                            @include('community.partials.comment', ['comment' => $comment, 'depth' => 0])
                        @empty
                            <p class="text-xs text-gray-400">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center py-20 text-gray-400 ">
                    <p class="text-sm">Belum ada diskusi untuk game ini.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function toggleNewPost() {
            const el = document.getElementById('new-post-form');
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) el.querySelector('input').focus();
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

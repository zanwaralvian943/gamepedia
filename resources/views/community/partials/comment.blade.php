<div class="flex gap-3 {{ $depth > 0 ? 'ml-6 pl-4 border-l-2 border-purple-100 ' : '' }}">
    <div class="w-7 h-7 rounded-full bg-gray-100  flex items-center justify-center shrink-0 mt-0.5">
        <span class="text-gray-600  text-xs font-bold uppercase">
            {{ substr($comment->user->name, 0, 1) }}
        </span>
    </div>

    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-semibold text-gray-800 ">{{ $comment->user->name }}</span>
            <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <p class="text-sm text-gray-700  leading-relaxed">{{ $comment->body }}</p>

        <div class="flex items-center gap-3 mt-1.5">
            @auth
                @if ($depth < 1)
                    <button onclick="toggleReplyForm('comment-reply-{{ $comment->id }}')"
                        class="text-xs text-gray-400 hover:text-purple-600  transition-colors">
                        ↩ Balas
                    </button>
                @endif
                @if (Auth::id() === $comment->user_id)
                    <form action="{{ route('community.comment.destroy', $comment->id) }}" method="POST"
                        onsubmit="return confirm('Hapus komentar ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">
                            Hapus
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        @auth
            @if ($depth < 1)
                <div id="comment-reply-{{ $comment->id }}" class="hidden mt-2">
                    <form action="{{ route('community.comment', $comment->post_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="body" rows="2" required maxlength="2000" placeholder="Balas {{ $comment->user->name }}..."
                            class="w-full rounded-lg border border-gray-300  bg-gray-50  text-gray-900  text-sm px-3 py-2 focus:ring-2 focus:ring-purple-500 outline-none resize-none"></textarea>
                        <div class="flex gap-2 mt-1.5">
                            <button type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                Kirim
                            </button>
                            <button type="button" onclick="toggleReplyForm('comment-reply-{{ $comment->id }}')"
                                class="text-gray-400 text-xs px-3 py-1.5 rounded-lg">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endauth

        @if ($comment->replies->isNotEmpty())
            <div class="mt-3 space-y-3">
                @foreach ($comment->replies as $reply)
                    @include('community.partials.comment', ['comment' => $reply, 'depth' => $depth + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>

<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    private const MODEL_EXTRACT = 'llama-3.1-8b-instant';
    private const MODEL_MAIN    = 'llama-3.3-70b-versatile';
    private const HISTORY_LIMIT = 10;

    private const SYSTEM_INSTRUCTION = <<<PROMPT
        Kamu adalah AI Assistant resmi Gamepedia. ATURAN MUTLAK: Kamu HANYA boleh menjawab topik seputar video game, konsol, PC gaming, dan e-sports.
        PENTING: Pertanyaan lanjutan seperti 'berapa ratingnya?', 'ceritanya gimana?', 'siapa karakternya?', 'harganya berapa?'
        HARUS dijawab selama konteks percakapan sebelumnya membahas tentang game. Gunakan history chat untuk memahami konteks.
        HANYA tolak jika pertanyaan JELAS-JELAS di luar topik gaming sama sekali (contoh: resep masakan, matematika, politik).
        Jika ragu, JAWAB saja karena kemungkinan besar masih relevan dengan game yang sedang dibahas.
        Jika benar-benar di luar topik, balas: 'Maaf bro, gue cuma asisten Gamepedia yang paham soal game aja nih. Tanya seputar game aja ya!'
        PROMPT;

    public function index($id = null)
    {
        $sessions      = ChatSession::where('user_id', Auth::id())->latest()->get();
        $activeSession = null;
        $messages      = collect();

        if ($id) {
            $activeSession = ChatSession::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $messages = ChatMessage::where('chat_session_id', $id)->oldest()->get();
        }

        return view('chat.index', compact('sessions', 'activeSession', 'messages'));
    }

    public function stream(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);

        $prompt    = $request->prompt;
        $userId    = Auth::id();
        $sessionId = $this->resolveSession($request->chat_session_id, $userId, $prompt);

        $gameContext = $this->fetchGameContext($prompt);
        $messages    = $this->buildMessages($sessionId, $prompt, $gameContext);
        $aiResponse  = $this->callMainModel($messages);

        ChatMessage::create([
            'chat_session_id' => $sessionId,
            'user_id'         => $userId,
            'prompt'          => $prompt,
            'response'        => $aiResponse,
        ]);

        return $this->streamResponse($aiResponse, $sessionId);
    }

    public function deleteSession($id)
    {
        $session = ChatSession::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        ChatMessage::where('chat_session_id', $id)->delete();
        $session->delete();

        return response()->json(['success' => true]);
    }

    public function renameSession(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:100']);

        $session = ChatSession::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $session->update(['title' => $request->title]);

        return response()->json(['success' => true, 'title' => $session->title]);
    }

    private function resolveSession(?string $sessionId, int $userId, string $prompt): string
    {
        if ($sessionId) {
            return $sessionId;
        }

        return ChatSession::create([
            'user_id' => $userId,
            'title'   => Str::limit($prompt, 30),
        ])->id;
    }

    private function fetchGameContext(string $prompt): string
    {
        $gameName = $this->extractGameName($prompt);

        if (blank($gameName) || $gameName === 'NONE') {
            return '';
        }

        $result = Http::withoutVerifying()
            ->get('https://api.rawg.io/api/games', [
                'search'    => $gameName,
                'key'       => config('services.rawg.key'),
                'page_size' => 1,
            ])
            ->json('results.0');

        if (empty($result)) {
            return '';
        }

        return implode("\n", [
            " DATA RESMI DARI DATABASE RAWG API ",
            "- Nama Game: "     . ($result['name']     ?? 'N/A'),
            "- Tanggal Rilis: " . ($result['released'] ?? 'N/A'),
            "- Rating Pemain: " . ($result['rating']   ?? 'N/A') . "/5",
            '',
        ]);
    }

    private function extractGameName(string $prompt): string
    {
        $response = Http::withoutVerifying()
            ->withHeaders(['Authorization' => 'Bearer ' . config('services.groq.key')])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => self::MODEL_EXTRACT,
                'messages'    => [[
                    'role'    => 'user',
                    'content' => "Ekstrak nama video game dari kalimat ini. Jika tidak ada nama game, balas dengan kata 'NONE' saja tanpa tanda kutip. Kalimat: {$prompt}",
                ]],
                'max_tokens'  => 50,
                'temperature' => 0,
            ]);

        return trim($response->json('choices.0.message.content') ?? 'NONE');
    }

    private function buildMessages(string $sessionId, string $prompt, string $gameContext): array
    {
        $history = ChatMessage::where('chat_session_id', $sessionId)
            ->oldest()
            ->take(self::HISTORY_LIMIT)
            ->get();

        $messages = [];

        foreach ($history as $msg) {
            $messages[] = ['role' => 'user',      'content' => $msg->prompt];
            $messages[] = ['role' => 'assistant', 'content' => $msg->response];
        }

        $messages[] = ['role' => 'user', 'content' => $gameContext . "Pertanyaan User: {$prompt}"];

        return $messages;
    }

    private function callMainModel(array $messages): string
    {
        $response = Http::withoutVerifying()
            ->withHeaders(['Authorization' => 'Bearer ' . config('services.groq.key')])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => self::MODEL_MAIN,
                'messages'    => array_merge(
                    [['role' => 'system', 'content' => self::SYSTEM_INSTRUCTION]],
                    $messages
                ),
                'max_tokens'  => 1024,
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return "Waduh bro, server AI lagi bermasalah nih. Sabar bentar ya, coba chat lagi dalam beberapa menit! (Error: {$response->status()})";
        }

        $content = trim($response->json('choices.0.message.content') ?? '');

        return $content ?: 'Maaf bro, AI nggak bisa kasih jawaban buat pertanyaan ini. Coba tanya dengan cara lain ya!';
    }

    private function streamResponse(string $aiResponse, string $sessionId)
    {
        $words = explode(' ', $aiResponse);

        return response()->stream(function () use ($words, $sessionId) {
            $this->emit(['session_id' => $sessionId]);

            foreach ($words as $i => $word) {
                $this->emit(['chunk' => ($i === 0 ? '' : ' ') . $word]);
                usleep(40_000);
            }

            $this->emit(['done' => true]);
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function emit(array $data): void
    {
        echo 'data: ' . json_encode($data) . "\n\n";
        ob_flush();
        flush();
    }
}

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
    public function index($id = null)
    {
        $sessions = ChatSession::where('user_id', Auth::id())->latest()->get();
        $activeSession = null;
        $messages = collect();

        if ($id) {
            $activeSession = ChatSession::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $messages = ChatMessage::where('chat_session_id', $id)->oldest()->get();
        }

        return view('chat.index', compact('sessions', 'activeSession', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $prompt = $request->prompt;
        $sessionId = $request->chat_session_id;
        if (!$sessionId) {
            $session = ChatSession::create([
                'user_id' => Auth::id(),
                'title' => Str::limit($prompt, 20)
            ]);
            $sessionId = $session->id;
        }
        $geminiKey = env('GEMINI_API_KEY');
        $rawgKey = env('RAWG_API_KEY');

        if (!$geminiKey) {
            dd('ERROR: GEMINI_API_KEY di file .env kosong atau belum terbaca! Jalankan php artisan config:clear');
        }
        $extractResponse = \Illuminate\Support\Facades\Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiKey}", [
            'contents' => [
                ['parts' => [['text' => "Ekstrak nama video game dari kalimat ini. Jika tidak ada nama game, balas dengan kata 'NONE' saja tanpa tanda kutip. Kalimat: {$prompt}"]]]
            ]
        ]);

        $extractedGame = trim($extractResponse->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'NONE');
        $rawgContext = "";
        if ($extractedGame !== 'NONE' && $extractedGame !== '') {
            $rawgResponse = \Illuminate\Support\Facades\Http::withoutVerifying()->get("https://api.rawg.io/api/games", [
                'search' => $extractedGame,
                'key' => $rawgKey,
                'page_size' => 1
            ]);

            $rawgData = $rawgResponse->json();

            if (!empty($rawgData['results'])) {
                $gameInfo = $rawgData['results'][0];
                $rawgContext = "=== DATA RESMI DARI DATABASE RAWG API ===\n"
                    . "- Nama Game: " . ($gameInfo['name'] ?? 'N/A') . "\n"
                    . "- Tanggal Rilis: " . ($gameInfo['released'] ?? 'N/A') . "\n"
                    . "- Rating Pemain: " . ($gameInfo['rating'] ?? 'N/A') . "/5\n";
            }
        }

        $systemInstruction = "Kamu adalah AI Assistant resmi Gamepedia. ATURAN MUTLAK: Kamu HANYA boleh menjawab topik seputar video game, konsol, PC gaming, dan e-sports. "
            . "JIKA pengguna bertanya di luar topik tersebut (seperti cara membuat kopi, resep makanan, matematika, coding umum, dll), "
            . "kamu WAJIB MENOLAKNYA dengan membalas: 'Maaf bro, gue cuma asisten Gamepedia yang paham soal game aja nih. Tanya seputar game aja ya!' "
            . "DILARANG KERAS memberikan jawaban/tutorial non-game meskipun kamu menggunakan analogi atau istilah gaming (seperti buff, item, atau crafting).";
        $finalPrompt = $rawgContext . "Pertanyaan User: " . $prompt;

        $apiResponse = \Illuminate\Support\Facades\Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$geminiKey}", [
            'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
            'contents' => [['parts' => [['text' => $finalPrompt]]]]
        ]);
        if ($apiResponse->failed()) {
            $aiResponse = "Waduh bro, server AI Google lagi penuh banget nih (High Demand). Sabar bentar ya, coba chat lagi dalam beberapa menit!";
        } else {
            $result = $apiResponse->json();
            if (isset($result['candidates'][0]['finishReason']) && $result['candidates'][0]['finishReason'] !== 'STOP') {
                $aiResponse = "Maaf, jawaban diblokir oleh sistem keamanan AI.";
            } else {
                $aiResponse = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, struktur data yang dikembalikan Google tidak sesuai.';
            }
        }

        ChatMessage::create([
            'chat_session_id' => $sessionId,
            'user_id' => Auth::id(),
            'prompt' => $prompt,
            'response' => $aiResponse
        ]);
        return redirect()->route('chat.index', $sessionId);
    }
}

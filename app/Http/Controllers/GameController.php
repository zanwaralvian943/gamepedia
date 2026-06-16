<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as FacadesHttp;

class GameController extends Controller
{

    public function index(Request $request)
    {
        $currentPage = $request->query('page', 1);
        $apiKey = env('RAWG_API_KEY');
        $baseUrl = env('BASE_URL');
        $pageSize = 12;
        $response = FacadesHttp::get("${baseUrl}/games", [
            'key' => $apiKey,
            'page' => $currentPage,
            'page_size' => $pageSize
        ]);
        if ($response->successful()) {
            $data = $response->json();
            $games = $data['results'];
            $totalGames = (int) $data['count'];
            $totalPages = (int) ceil($totalGames / $pageSize);
        } else {
            $games = [];
            $totalPages = 1;
        }
        return view('games.index', compact('games', 'currentPage', 'totalPages'));
    }

    public function show($id)
    {

        $apiKey = env('RAWG_API_KEY');
        $baseUrl = env('BASE_URL');

        $response = FacadesHttp::get("${baseUrl}/games/{$id}", [
            'key' => $apiKey
        ]);
        $responseSS = FacadesHttp::get("${baseUrl}/games/{$id}/screenshots", [
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            $game = $response->json();
            $screenshots = $responseSS->successful() ? ($responseSS->json()['results'] ?? []) : [];
        } else {
            abort(404, 'Game tidak ditemukan di RAWG API');
        }
        return view('games.show', compact('game', 'screenshots'));
    }
}

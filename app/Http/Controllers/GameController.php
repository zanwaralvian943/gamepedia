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
}

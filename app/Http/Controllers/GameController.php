<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Wishlist;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    private const PAGE_SIZE = 12;

    private function rawg(string $endpoint, array $params = []): Response
    {
        return Http::withoutVerifying()->get(config('services.rawg.base_url') . $endpoint, [
            'key' => config('services.rawg.key'),
            ...$params,
        ]);
    }

    public function index(Request $request)
    {
        $search      = $request->query('search');
        $genre       = $request->query('genre');
        $currentPage = $request->query('page', 1);

        $params = array_filter([
            'page'      => $currentPage,
            'page_size' => self::PAGE_SIZE,
            'search'    => $search,
            'genres'    => $genre,
        ]);

        $response      = $this->rawg('/games', $params);
        $genreResponse = $this->rawg('/genres');

        $genres     = $genreResponse->successful() ? $genreResponse->json('results') : [];
        $games      = [];
        $totalPages = 1;
        $wishlistedSlugs = auth()->check()
            ? Wishlist::where('user_id', auth()->id())->pluck('game_slug')->toArray()
            : [];

        if ($response->successful()) {
            $data       = $response->json();
            $games      = $data['results'];
            $totalPages = (int) ceil($data['count'] / self::PAGE_SIZE);
        }

        return view('games.index', compact('games', 'currentPage', 'totalPages', 'search', 'genres', 'genre', 'wishlistedSlugs'));
    }

    public function show(string $slug)
    {
        $response   = $this->rawg("/games/{$slug}");
        $responseSS = $this->rawg("/games/{$slug}/screenshots");

        abort_unless($response->successful(), 404, 'Game tidak ditemukan di RAWG API');

        $game        = $response->json();
        $screenshots = $responseSS->json('results', []);
        $recentPosts = Post::where('game_slug', $slug)->with('user')->latest()->take(3)->get();
        $totalPosts  = Post::where('game_slug', $slug)->count();

        return view('games.show', compact('game', 'screenshots', 'recentPosts', 'totalPosts'));
    }
}

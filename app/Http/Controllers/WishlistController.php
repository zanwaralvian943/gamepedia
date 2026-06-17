<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->latest()->get();
        return view('wishlist.index', compact('wishlists'));
    }
    public function toggle(Request $request)
    {
        $request->validate([
            'game_slug' => 'required|string',
            'game_name' => 'required|string',
            'image' => 'nullable|string'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('game_slug', $request->game_slug)->first();
        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Game removed from wishlist.');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'game_slug' => $request->game_slug,
                'game_name' => $request->game_name,
                'image' => $request->image
            ]);
            return redirect()->back()->with('success', 'Game added to wishlist!');
        }
    }
}

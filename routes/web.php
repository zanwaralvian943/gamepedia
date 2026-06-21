<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{id}', [GameController::class, 'show'])->name('games.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
Route::get('/community', [PostController::class, 'index'])->name('community.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/community/{slug}', [PostController::class, 'gameIndex'])->name('community.game');
    Route::post('/community/{slug}/post', [PostController::class, 'storePost'])->name('community.store');
    Route::post('/community/post/{post_id}/comment', [PostController::class, 'storeComment'])->name('community.comment');
    Route::delete('/community/post/{id}', [PostController::class, 'destroyPost'])->name('community.post.destroy');
    Route::delete('/community/comment/{id}', [PostController::class, 'destroyComment'])->name('community.comment.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{id?}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/stream', [ChatController::class, 'stream'])->name('chat.stream');
    Route::delete('/chat/session/{id}', [ChatController::class, 'deleteSession'])->name('chat.session.delete');
    Route::patch('/chat/session/{id}', [ChatController::class, 'renameSession'])->name('chat.session.rename');
});
Route::middleware('auth')->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

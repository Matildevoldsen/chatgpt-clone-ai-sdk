<?php

use App\Http\Controllers\Chats\ChatPageController;
use App\Http\Controllers\Chats\ChatStreamController;
use App\Http\Controllers\Chats\DeleteChatController;
use App\Http\Controllers\Chats\RenameChatController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn () => to_route('chat'))->name('dashboard');
    Route::get('chat', [ChatPageController::class, 'index'])->name('chat');
    Route::get('chat/{chat}', [ChatPageController::class, 'show'])->name('chat.show');
    Route::post('chat/stream', ChatStreamController::class)->name('chat.stream');
    Route::patch('chat/{chat}', RenameChatController::class)->name('chat.update');
    Route::delete('chat/{chat}', DeleteChatController::class)->name('chat.destroy');
});

require __DIR__.'/settings.php';

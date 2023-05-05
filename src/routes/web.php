<?php

use Ajyshrma69\LaravelMessenger\Http\Controllers\ChatController;
use Ajyshrma69\LaravelMessenger\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->as('laravel.messenger.')->group(function () {
    Route::get('messenger', [ChatController::class, 'index'])->name('chats');
    Route::post('load-message', [ChatController::class, 'loadMessages'])->name('load.messages');
    Route::post('create-new-chat', [ChatController::class, 'createNewChat'])->name('create.chat');

    Route::post('send-message', [MessageController::class, 'send'])->name('send.message');
    Route::post('fetch-new-messages', [MessageController::class, 'fetchNewMessages'])->name('fetch.new.message');
    Route::get('download/{id}/attachment', [MessageController::class, 'downloadAttachment'])->name('download.attachment');
});

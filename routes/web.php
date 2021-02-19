<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UploadVideoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VoteController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/channels', ChannelController::class);

Route::get('videos/{video}', [VideoController::class, 'show'])->name('videos.show');
Route::put('videos/{video}', [VideoController::class, 'updateViews']);

Route::get('videos/{video}/comments', [CommentController::class, 'index']);
Route::get('comments/{comment}/replies', [CommentController::class, 'show']);

Route::middleware(['auth'])->group(function () {

    Route::resource('/channels/{channel}/subscription', SubscriptionController::class)->only(['store', 'destroy']);

    Route::get('/channels/{channel}/videos', [UploadVideoController::class, 'index'])->name('channel.upload');
    Route::post('/channels/{channel}/videos', [UploadVideoController::class, 'store']);

    Route::put('videos/{video}/update', [VideoController::class, 'update'])->name('videos.update');

    Route::post('votes/{entityId}/{type}', [VoteController::class, 'vote']);


    Route::post('comments/{video}', [CommentController::class, 'store']);
});

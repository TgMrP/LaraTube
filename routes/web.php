<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UploadVideoController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/channels', ChannelController::class);

Route::middleware(['auth'])->group(function () {

    Route::resource('/channels/{channel}/subscription', SubscriptionController::class)->only(['store', 'destroy']);

    Route::get('/channels/{channel}/videos', [UploadVideoController::class, 'index'])->name('channel.upload');
    Route::post('/channels/{channel}/videos', [UploadVideoController::class, 'store']);
});

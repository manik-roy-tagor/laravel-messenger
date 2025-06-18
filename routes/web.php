<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\Messenger\MessageController;  // আগের রেসপন্সে তৈরি করা কন্ট্রোলার

Route::middleware(['auth'])->group(function () {  // শুধু অথেনটিকেটেড ইউজারদের জন্য
    Route::get('/messenger', [MessageController::class, 'index'])->name('messenger.index');  // চ্যাট পেজ
    Route::post('/messenger/send', [MessageController::class, 'sendMessage'])->name('messenger.send');  // মেসেজ সেন্ড
    Route::get('/messenger/{userId?}', [MessageController::class, 'index'])->name('messenger.index')->middleware('auth');
    Route::post('/messenger/send/{userId}', [MessageController::class, 'sendMessage'])->name('messenger.send')->middleware('auth');
});

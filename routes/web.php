<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\Messenger\MessageController;  // আগের রেসপন্সে তৈরি করা কন্ট্রোলার

Route::middleware(['auth'])->group(function () {  // শুধু অথেনটিকেটেড ইউজারদের জন্য
    Route::get('/messenger', [MessageController::class, 'index'])->name('messenger.index');  // চ্যাট পেজ
    Route::post('/messenger/send', [MessageController::class, 'sendMessage'])->name('messenger.send');  // মেসেজ সেন্ড
    Route::get('/messenger/{userId?}', [MessageController::class, 'index'])->name('messenger.index')->middleware('auth');
    Route::post('/messenger/send/{userId}', [MessageController::class, 'sendMessage'])->name('messenger.send')->middleware('auth');
});


Route::get('/', [BlogController::class, 'index'])->name('blogs.index');  // Welcome পেজ হিসেবে সেট
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');  // সিঙ্গল ব্লগ দেখা
Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');  // নতুন ব্লগ ফর্ম
Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');  // ব্লগ সেভ করা


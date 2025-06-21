<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Messenger\MessageController;
use App\Http\Controllers\UserController;


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth'])->group(function () {  // শুধু অথেনটিকেটেড ইউজারদের জন্য
    Route::get('/messenger', [MessageController::class, 'index'])->name('messenger.index');  // চ্যাট পেজ
    Route::post('/messenger/send', [MessageController::class, 'sendMessage'])->name('messenger.send');  // মেসেজ সেন্ড
    Route::get('/messenger/{userId?}', [MessageController::class, 'index'])->name('messenger.index')->middleware('auth');
    Route::post('/messenger/send/{userId}', [MessageController::class, 'sendMessage'])->name('messenger.send')->middleware('auth');
});


Route::get('/', [BlogController::class, 'index'])->name('blogs.index');  // Welcome পেজ হিসেবে সেট
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');  // সিঙ্গল ব্লগ দেখা
Route::get('post', [BlogController::class, 'createblog'])->name('blogs.create');  // নতুন ব্লগ ফর্ম
Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');  // ব্লগ সেভ করা

// ইউজার প্রোফাইল ও তার ব্লগ লিস্ট দেখার রাউট
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');
Route::get('/user/{id}/blogs', [UserController::class, 'blogs'])->name('user.blogs'); // ইউজারের ব্লগ লিস্ট
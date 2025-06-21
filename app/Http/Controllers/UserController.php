<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ইউজার প্রোফাইল দেখানো
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.profile', compact('user'));
    }

    // ইউজারের ব্লগ লিস্ট দেখানো
    public function blogs($id)
    {
        $user = User::findOrFail($id);
        $blogs = $user->blogs;  // ইউজারের ব্লগগুলো
        return view('users.blogs', compact('user', 'blogs'));
    }
}

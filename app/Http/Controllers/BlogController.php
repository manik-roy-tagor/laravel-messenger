<?php

namespace App\Http\Controllers;


use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use HeyMarco\LinkPreview;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['post', 'store']);  // শুধুমাত্র create এবং store মেথডের জন্য অথেনটিকেশন
    }

    // ব্লগ লিস্ট দেখানো (পাবলিক)
    public function index(Request $request)
    {
        $users = User::all();  // সব ইউজার
        $totalUsers = $users->count(); // মোট ইউজার সংখ্যা
        $blogs = Blog::with('user')
                ->latest()
                ->paginate(2);

    if ($request->ajax()) {
        return view('partials.blogs', compact('blogs'))->render();
    }  // সব ব্লগ, সর্বশেষ প্রথমে
        return view('welcome', compact('blogs', 'users', 'totalUsers'));  // welcome পেজে পাঠানো
    }

    // সিঙ্গল ব্লগ দেখানো (পাবলিক)
    public function show($id)
    {
        $blog = Blog::findOrFail($id);  // ব্লগ খুঁজুন
        return view('blogs.show', compact('blog'));  // নতুন ব্লেড ফাইলে দেখানো
    }

    // নতুন ব্লগ ফর্ম দেখানো (লগইনের পর)
    public function createblog()
    {
        return view('blogs.create');  // নতুন ব্লেড ফাইল
    }

    // নতুন ব্লগ সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),  // লগইন করা ইউজারের ID
        ]);

        return redirect()->route('blogs.index')->with('success', 'ব্লগ সফলভাবে পোস্ট করা হলো!');
    }
}


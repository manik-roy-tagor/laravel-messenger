<?php

namespace App\Http\Controllers;


use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store']);  // শুধুমাত্র create এবং store মেথডের জন্য অথেনটিকেশন
    }

    // ব্লগ লিস্ট দেখানো (পাবলিক)
    public function index()
    {
        $blogs = Blog::latest()->get();  // সব ব্লগ, সর্বশেষ প্রথমে
        return view('welcome', compact('blogs'));  // welcome পেজে পাঠানো
    }

    // সিঙ্গল ব্লগ দেখানো (পাবলিক)
    public function show($id)
    {
        $blog = Blog::findOrFail($id);  // ব্লগ খুঁজুন
        return view('blogs.show', compact('blog'));  // নতুন ব্লেড ফাইলে দেখানো
    }

    // নতুন ব্লগ ফর্ম দেখানো (লগইনের পর)
    public function create()
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


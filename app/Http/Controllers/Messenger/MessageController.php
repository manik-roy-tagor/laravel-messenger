<?php

namespace App\Http\Controllers\Messenger;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
// যদি কাস্টম ইভেন্ট থাকে, তাহলে ইমপোর্ট করুন: use App\Events\MessageSent;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // সব মেথডে অথেনটিকেশন চেক
    }

    public function index($userId = null)
    {
        $users = User::where('id', '!=', auth()->id())->get();  // অথেনটিকেটেড ইউজার বাদে সব ইউজার
        
        if ($userId) {
            $messages = Message::where(function ($query) use ($userId) {
                $query->where('user_id', auth()->id())->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('receiver_id', auth()->id());
            })->with(['user', 'receiver'])->get();
            
            // সিলেক্টেড ইউজারের নাম পেতে
            $selectedUser = User::find($userId);
            $selectedUserName = $selectedUser ? $selectedUser->name : 'Unknown User';
            
            return view('messenger.chat', compact('users', 'messages', 'userId', 'selectedUserName'));
        }
        
        return view('messenger.chat', compact('users'));  // যদি userId না থাকে, শুধু ইউজার লিস্ট দেখান
    }

    public function sendMessage(Request $request, $userId)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',  // রিসিভার আইডি ভ্যালিডেশন
            'type' => 'required|in:text,video,audio',
            'content' => 'required_if:type,text|string',
            'file' => 'required_if:type,video,audio|mimes:mp4,mp3|max:10240',  // ফাইল সাইজ 10MB লিমিট
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');  // ফাইল স্টোর
        }

        $message = Message::create([
            'user_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,  // এখানে $userId ব্যবহার করা যেতে পারে, কিন্তু ফর্ম থেকে নিচ্ছি
            'type' => $request->type,
            'content' => $request->type == 'text' ? $request->content : null,
            'file_path' => $path ?? null,
        ]);

        // রিয়েল-টাইম নোটিফিকেশনের জন্য (যদি সেট আপ করা থাকে)
        event(new MessageSent($message));  // কাস্টম ইভেন্ট ট্রিগার
        // যদি AJAX রিকোয়েস্ট হয়, তাহলে JSON রেসপন্স দিন
        // return response()->json(['message' => 'সফলভাবে সেন্ড করা হলো', 'data' => $message]);

        return redirect()->back()->with('success', 'মেসেজ সফলভাবে সেন্ড করা হলো');
    }
}

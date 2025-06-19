<?php

namespace App\Http\Controllers\Messenger;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;  // ফাইল স্টোরেজের জন্য, যদি প্রয়োজন হয়

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
            'receiver_id' => 'required|exists:users,id',
            'content' => 'nullable|string',  // টেক্সট ঐচ্ছিক
            'file' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif|max:10240',  // ফাইল ঐচ্ছিক
        ]);

        $type = 'text';  // ডিফল্ট টাইপ
        $path = null;
        $contentToSave = $request->content;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();

            // ফাইল টাইপ ডিটেক্ট
            if (strpos($mimeType, 'image/') === 0) {
                $type = 'image';
            } elseif (strpos($mimeType, 'video/') === 0) {
                $type = 'video';
            } elseif (strpos($mimeType, 'audio/') === 0) {
                $type = 'audio';
            }

            $path = $file->store('uploads', 'public');
        } elseif (empty($contentToSave)) {
            return back()->withErrors(['error' => 'At least content or file is required.']);
        }

        $message = Message::create([
            'user_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'type' => $type,
            'content' => $contentToSave,
            'file_path' => $path ?? null,
        ]);

        event(new MessageSent($message));
        return redirect()->back()->with('success', 'মেসেজ সফলভাবে সেন্ড করা হলো');
    }
}

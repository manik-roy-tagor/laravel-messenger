@extends('layouts.app')  {{-- আপনার মেইন লেয়াউট ফাইলের সাথে এক্সটেন্ড করুন --}}

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Left Sidebar: Chat List -->
        <div class="col-md-4 col-lg-3 bg-white shadow-sm p-3 overflow-auto" style="height: 100vh;">
            <h2 class="h4 mb-4 text-center text-primary">Chat List</h2>
            <ul class="list-group">
                @foreach($users as $user)
                <a href="{{ route('messenger.index', ['userId' => $user->id]) }}">
                    <li class="list-group-item list-group-item-action d-flex align-items-center p-3 bg-light">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4CAF50&color=fff" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                        <div>
                            <p class="mb-0 font-weight-bold">{{ $user->name }}</p>
                            <p class="mb-0 text-muted small">Last message...  {{-- এখানে আসল লাস্ট মেসেজ যোগ করুন যদি চান --}}</p>
                        </div>
                    </li>
                </a>
                @endforeach
                <!-- আরও চ্যাট আইটেম যোগ করুন -->
            </ul>
        </div>

        <!-- Right Main: Chat Window -->
        <div class="col-md-8 col-lg-9 d-flex flex-column">
            <div class="bg-white p-3 shadow-sm d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Chatting with {{ $selectedUserName ?? 'Unknown User' }}</h2>  {{-- ডায়নামিকভাবে সিলেক্টেড ইউজারের নাম --}}
            </div>

            <div class="flex-grow-1 p-3 overflow-auto bg-light" style="height: calc(100vh - 100px);">
                <!-- Message List -->
                @forelse($messages as $message)  {{-- forelse ব্যবহার করা হয়েছে যাতে খালি হলে এরর না আসে --}}
                <div class="d-flex align-items-start mb-2 {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="d-flex flex-column">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($message->user->name ?? 'User') }}&background=4CAF50&color=fff" alt="{{ $message->user->name ?? 'User' }}" class="rounded-circle {{ $message->user_id == auth()->id() ? 'order-last' : '' }} me-2" width="30" height="30">
                        <div class="bg-{{ $message->user_id == auth()->id() ? 'primary' : 'success' }} bg-opacity-10 p-2 rounded-pill">
                            <p class="mb-0 text-dark small">{{ $message->content ?? 'No content' }}</p>  {{-- মেসেজ কনটেন্ট --}}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">No messages yet.</p>
                @endforelse
                <!-- আরও মেসেজ যোগ করুন -->
            </div>

            <!-- Message Input -->
            <div class="p-3 bg-white shadow-sm">
                <form action="{{ route('messenger.send', ['userId' => $userId]) }}" method="POST">
                    @csrf  {{-- Laravel এর জন্য সিকিউরিটি --}}
                    <input type="hidden" name="receiver_id" value="{{ $userId }}">  {{-- রিসিভার আইডি অটোম্যাটিকলি পাঠানো --}}
                    <div class="input-group">
                        <input type="text" name="content" class="form-control" placeholder="Type a message..." aria-label="Message" required>
                        <input type="hidden" name="type" value="text">  {{-- ডিফল্ট টাইপ --}}
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

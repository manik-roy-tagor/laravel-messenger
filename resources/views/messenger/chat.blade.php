@extends('layouts.app')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Left Sidebar: Chat List -->
        <div class="col-md-4 col-lg-3 bg-white shadow-sm p-3 overflow-auto" style="height: 80vh;">
            <h2 class="h4 mb-4 text-center text-primary">Chat List</h2>
            <ul class="list-group">
                @foreach($users as $user)
                <a href="{{ route('messenger.index', ['userId' => $user->id]) }}">
                    <li class="list-group-item list-group-item-action d-flex align-items-center p-3 bg-light">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4CAF50&color=fff" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                        <div>
                            <p class="mb-0 font-weight-bold">{{ $user->name }}</p>
                            <p class="mb-0 text-muted small">Last message...</p>
                        </div>
                    </li>
                </a>
                @endforeach
            </ul>
        </div>

        <!-- Right Main: Chat Window -->
        <div class="col-md-8 col-lg-9 d-flex flex-column" style="height: 80vh;">
            <div class="bg-white p-3 shadow-sm d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Chatting with {{ $selectedUserName ?? 'Unknown User' }}</h2>
            </div>

            <div class="flex-grow-1 p-3 overflow-auto bg-light" style="height: 100vh;" id="messageContainer">
                @forelse($messages as $message)
                <div class="d-flex align-items-start mb-2 {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="d-flex flex-row align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($message->user->name ?? 'User') }}&background=4CAF50&color=fff" alt="{{ $message->user->name ?? 'User' }}" class="rounded-circle {{ $message->user_id == auth()->id() ? 'order-last' : '' }} me-2" width="30" height="30">
                        <div class=" ">
                            <!-- সবসময় টেক্সট চেক করা হবে, যদি থাকে -->
                            @if($message->content)
                            <p class="mb-0 text-dark small bg-{{ $message->user_id == auth()->id() ? 'primary' : 'success' }} bg-opacity-10 p-2 rounded-pill">{{ $message->content }}</p> <!-- rounded-pill যোগ করা হলো -->

                            @endif

                            @if($message->type == 'image')
                            <!-- ইমেজ ক্লিকযোগ্য করা হলো -->
                            <img src="{{ asset('storage/' . $message->file_path) }}" data-bs-toggle="modal" data-bs-target="#imageModal" data-src="{{ asset('storage/' . $message->file_path) }}" alt="Image" style="max-width: 200px; cursor: pointer;" class="img-fluid">
                            @elseif($message->type == 'video')
                            <video src="{{ asset('storage/' . $message->file_path) }}" controls style="max-width: 200px;">
                                Your browser does not support the video tag.
                            </video>
                            @elseif($message->type == 'audio')
                            <audio src="{{ asset('storage/' . $message->file_path) }}" controls></audio>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">No messages yet.</p>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-3 bg-white shadow-sm">
                <form action="{{ route('messenger.send', ['userId' => $userId]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $userId }}">
                    <div class="input-group">
                        <input type="text" name="content" class="form-control" placeholder="Type a message..." aria-label="Message">
                        <input type="file" name="file" class="form-control d-none" id="fileInput" accept="image/*,video/*,audio/*,.*">
                        <button class="btn btn-outline-secondary" type="button" id="fileButton">
                            <!-- <i class="bi bi-paperclip"></i> -->
                            <img src="{{ asset('storage/uploads/icon/OIP-C.jpg') }}" alt="file" width="30" height="30">
                        </button>
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Large Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            scrollToBottom();

            const observer = new MutationObserver(scrollToBottom);
            observer.observe(messageContainer, {
                childList: true,
                subtree: true
            });
        }

        document.getElementById('fileButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        // Image Modal Handler
        var imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button element that triggered the modal
                var src = button.getAttribute('data-src'); // Get the image source
                var modalImage = imageModal.querySelector('#modalImage');
                modalImage.src = src; // Set the image source in the modal
                modalImage.alt = 'Preview Image'; // Optional: Set alt text
            });
        }
    });

    function scrollToBottom() {
        const messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    }
</script>

@endsection
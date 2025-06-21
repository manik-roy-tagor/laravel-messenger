@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row">
        <!-- ক্যাটাগরি লিস্ট -->
        <div class="col-md-3 mb-4">
            <h4>Categories</h4>
            <ul class="list-group">

                <li class="list-group-item">
                    Blogs
                </li>

            </ul>
        </div>

        <!-- ব্লগ লিস্ট -->
        <div class="col-md-6 mb-4">

            <!-- লগইন করা ইউজারদের জন্য ইনলাইন ব্লগ পোস্ট ফর্ম -->
            @auth
            @if (auth()->id() === $user->id)
            <div class="card mb-4" style="border: 2px solid rgb(87, 28, 18);">
                <div class="card-header">Create New Blog</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('blogs.store') }}">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="title" class="form-control" placeholder="Blog Title" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="content" class="form-control" rows="3" placeholder="Write your blog..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Post</button>
                    </form>
                </div>
            </div>
            @endif
            @endauth

            <h4>Latest Blogs</h4>
            @foreach($user->blogs as $blog)
            <div class="card mb-4 shadow" style="border-left: 5px solid #933; border-radius: 10px;">
                <div class="card-header bg-white d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($blog->user->name) }}&background=0D8ABC&color=fff"
                        alt="Avatar" class="rounded-circle me-2" width="40" height="40">
                    <div>
                        <a href="{{ route('user.profile', $blog->user->id) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $blog->user->name }}
                        </a><br>
                        <small class="text-muted">{{ $blog->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $blog->title }}</h5>
                    <p class="card-text">{{ Str::limit($blog->content, 150) }}</p>
                    <button
                        class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#blogModal"
                        onclick="openBlogModal(this)"
                        data-title="{{ $blog->title }}"
                        data-content="{{ htmlspecialchars($blog->content) }}"
                        data-author="{{ $blog->user->name }}"
                        data-time="{{ $blog->created_at->diffForHumans() }}"
                        data-avatar="https://ui-avatars.com/api/?name={{ urlencode($blog->user->name) }}&background=0D8ABC&color=fff"
                        data-likes="{{ $blog->likes_count ?? 0 }}"
                        data-comments="{{ $blog->comments_count ?? 0 }}"
                        data-url="{{ route('blogs.show', $blog->id) }}">
                        Read More
                    </button>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <span class="me-3"><i class="bi bi-heart text-danger"></i> {{ $blog->likes_count ?? 0 }}</span>
                        <span class="me-3"><i class="bi bi-chat-left-dots text-secondary"></i> {{ $blog->comments_count ?? 0 }}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="shareBlog({{ $blog->id }})">
                        <i class="bi bi-share"></i> Share
                    </button>
                </div>
            </div>
            @endforeach

        </div>

        <!-- ইউজার লিস্ট -->
        <div class="col-md-3 mb-4">
            <h4>Users</h4>
            <ul class="list-group">

            </ul>
        </div>
    </div>
</div>


<!-- Global Blog Modal -->
<div class="modal fade" id="blogModal" tabindex="-1" aria-labelledby="blogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-left: 4px solid #933; border-radius: 10px;">

            <!-- Modal Header (User Info) -->
            <div class="modal-header bg-white d-flex align-items-center">
                <img id="modalUserImage" src="" alt="Avatar" class="rounded-circle me-2" width="45" height="45">
                <div>
                    <h6 id="modalUserName" class="mb-0 fw-bold text-dark">User Name</h6>
                    <small id="modalPostTime" class="text-muted">Just now</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Post Details) -->
            <div class="modal-body">
                <h4 id="modalPostTitle" class="fw-semibold mb-3">Post Title</h4>
                <p id="modalPostContent" class="text-dark" style="white-space: pre-line;">Full blog content here...</p>
            </div>

            <!-- Modal Footer (Like/Comment/Share) -->
            <div class="modal-footer bg-light px-4">
                <div class="row w-100 text-center">
                    <div class="col-4 border-end">
                        <i class="bi bi-heart text-danger"></i>
                        <span id="modalLikes">0</span>
                    </div>
                    <div class="col-4 border-end">
                        <i class="bi bi-chat-left-dots text-secondary"></i>
                        <span id="modalComments">0</span>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyModalShareLink()">
                            <i class="bi bi-share"></i> Share
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- শেয়ার ফাংশন -->
<script>
    function shareBlog(id) {
        const url = "{{ url('/blogs') }}/" + id;
        navigator.clipboard.writeText(url).then(() => {
            alert('Blog link copied to clipboard!');
        });
    }

    function openBlogModal(btn) {
        const title = btn.getAttribute('data-title');
        const content = btn.getAttribute('data-content');
        const author = btn.getAttribute('data-author');
        const time = btn.getAttribute('data-time');
        const avatar = btn.getAttribute('data-avatar');
        const likes = btn.getAttribute('data-likes') || 0;
        const comments = btn.getAttribute('data-comments') || 0;
        const url = btn.getAttribute('data-url');

        // Set modal data
        document.getElementById('modalPostTitle').innerText = title;
        document.getElementById('modalPostContent').innerText = content;
        document.getElementById('modalUserName').innerText = author;
        document.getElementById('modalPostTime').innerText = time;
        document.getElementById('modalUserImage').src = avatar;
        document.getElementById('modalLikes').innerText = likes;
        document.getElementById('modalComments').innerText = comments;

        // Store share URL for later use
        document.getElementById('modalShareURL')?.remove();
        const input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'modalShareURL';
        input.value = url;
        document.body.appendChild(input);
    }

    function copyModalShareLink() {
        const url = document.getElementById('modalShareURL')?.value;
        if (url) {
            navigator.clipboard.writeText(url);
            alert('✅ Link copied to clipboard!');
        }
    }
</script>

@endsection
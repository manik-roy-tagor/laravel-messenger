 @foreach($blogs as $blog)
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
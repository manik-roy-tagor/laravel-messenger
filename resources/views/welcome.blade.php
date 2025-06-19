@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Welcome to Our Chat App with Blogs</h1>
        <!-- লগইন করা ইউজারদের জন্য নতুন ব্লগ পোস্ট বাটন -->
    @auth
    <a href="{{ route('blogs.create') }}" class="btn btn-success">Create New Blog</a>
    @endauth
    <!-- ব্লগ লিস্ট -->
    <h2 class="mt-4">Latest Blogs</h2>
    <div class="row">
        @foreach($blogs as $blog)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $blog->title }}</h5>
                    <p class="card-text">{{ Str::limit($blog->content, 100) }}  <!-- 100 অক্ষর দেখানো --></p>
                    <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-primary">Read More</a>
                    <p class="text-muted">By: {{ $blog->user->name }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>


</div>
@endsection

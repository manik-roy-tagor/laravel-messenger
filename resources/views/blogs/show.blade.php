@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>{{ $blog->title }}</h1>
    <p>{{ $blog->content }}</p>
    <p class="text-muted">Posted by: {{ $blog->user->name }} on {{ $blog->created_at->format('d M Y') }}</p>
    <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Back to Blogs</a>
</div>
@endsection

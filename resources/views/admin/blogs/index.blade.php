@extends('layouts.main.master')
@section('style')
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Blog Posts</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">All Posts</h3>
            <div class="block-options">
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus opacity-50 me-1"></i> New Post
                </a>
            </div>
        </div>

        <div class="block-content">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search by title…" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-alt-secondary w-100">
                            <i class="fa fa-search opacity-50 me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Published</th>
                            <th style="width:220px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                            <tr>
                                <th scope="row">{{ $blog->id }}</th>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->author->name ?? '—' }}</td>
                                <td>{{ $blog->category ?? '—' }}</td>
                                <td>
                                    @if($blog->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ number_format($blog->views_count) }}</td>
                                <td>{{ $blog->published_at ? $blog->published_at->format('d M, Y') : '—' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-alt-primary">Edit</a>
                                        <a href="{{ route('admin.blogs.analytics', $blog->id) }}" class="btn btn-sm btn-alt-secondary">Analytics</a>
                                        <form action="{{ route('admin.blogs.toggle-publish', $blog->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $blog->status === 'published' ? 'btn-alt-warning' : 'btn-alt-success' }}">
                                                {{ $blog->status === 'published' ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No blog posts yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex">
                    {!! $blogs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

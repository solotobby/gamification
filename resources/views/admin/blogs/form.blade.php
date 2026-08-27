@extends('layouts.main.master')
@section('style')
<style>
    .cover-preview {
        width: 100%;
        max-width: 360px;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e9f0;
        margin-top: .75rem;
        display: none;
    }
    .cover-preview.show { display: block; }
</style>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $blog ? 'Edit Post' : 'New Post' }}</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $blog ? 'Edit' : 'New' }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $blog ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($blog) @method('PUT') @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="block block-rounded">
                    <div class="block-content">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Excerpt <span class="text-muted">(short summary shown in listings)</span></label>
                            <textarea name="excerpt" class="form-control" rows="2" maxlength="500">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" id="js-ckeditor5-classic" rows="14">{{ old('content', $blog->content ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">SEO</h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $blog->meta_title ?? '') }}" maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="2" maxlength="500">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Publish</h3>
                    </div>
                    <div class="block-content">
                        @if($blog)
                            <p class="fs-sm text-muted mb-3">
                                Status: <strong>{{ ucfirst($blog->status) }}</strong>
                                @if($blog->published_at)
                                    <br>Published {{ $blog->published_at->diffForHumans() }}
                                @endif
                            </p>
                            <button type="submit" name="action" value="save" class="btn btn-alt-secondary w-100 mb-2">Save Changes</button>
                            @if($blog->status === 'published')
                                <button type="submit" name="action" value="draft" class="btn btn-alt-warning w-100">Unpublish</button>
                            @else
                                <button type="submit" name="action" value="publish" class="btn btn-primary w-100">Publish Now</button>
                            @endif
                        @else
                            <button type="submit" name="action" value="draft" class="btn btn-alt-secondary w-100 mb-2">Save as Draft</button>
                            <button type="submit" name="action" value="publish" class="btn btn-primary w-100">Publish Now</button>
                        @endif
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Cover Image</h3>
                    </div>
                    <div class="block-content">
                        @if(!empty($blog->cover_image))
                            <img src="{{ $blog->cover_image }}" class="cover-preview show" id="current-cover" alt="Current cover">
                        @endif
                        <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewCover(this)">
                        <img id="cover-preview-new" class="cover-preview" alt="New cover preview">
                        <div class="fs-xs text-muted mt-2">JPG, PNG or WebP. Max 5MB.</div>
                    </div>
                </div>

                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Organize</h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $blog->category ?? '') }}" placeholder="e.g. Company News">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags <span class="text-muted">(comma-separated)</span></label>
                            <input type="text" name="tags" class="form-control"
                                value="{{ old('tags', $blog && $blog->tags ? implode(', ', $blog->tags) : '') }}"
                                placeholder="e.g. remote work, hiring, tips">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewCover(input) {
        if (!input.files.length) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById('cover-preview-new');
            img.src = e.target.result;
            img.classList.add('show');
            const current = document.getElementById('current-cover');
            if (current) current.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection

@section('script')
<script src="{{ asset('src/assets/js/plugins/ckeditor5-classic/build/ckeditor.js')}}"></script>
<script>Dashmix.helpersOnLoad(['js-ckeditor5']);</script>
@endsection

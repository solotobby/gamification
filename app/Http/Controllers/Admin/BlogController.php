<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->with('author:id,name')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.form', ['blog' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'required|string',
            'category'          => 'nullable|string|max:100',
            'tags'              => 'nullable|string', // comma-separated in the form
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'action'            => 'required|in:draft,publish',
        ]);

        $coverUrl = null;
        if ($request->hasFile('cover_image')) {
            // $path = $request->file('cover_image')->store('blogs/covers', 'public');
            $coverUrl = uploadImageToSpaces($request->file('cover_image'), 'blogs/covers');
            // $coverUrl = Storage::url($path);
        }

        $status = $validated['action'] === 'publish' ? 'published' : 'draft';

        Blog::create([
            'title'             => $validated['title'],
            'excerpt'           => $validated['excerpt'] ?? null,
            'content'           => $validated['content'],
            'category'          => $validated['category'] ?? null,
            'tags'              => !empty($validated['tags']) ? array_map('trim', explode(',', $validated['tags'])) : [],
            'meta_title'        => $validated['meta_title'] ?? null,
            'meta_description'  => $validated['meta_description'] ?? null,
            'cover_image'       => $coverUrl,
            'author_id'         => auth()->id(),
            'status'            => $status,
            'published_at'      => $status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog ' . ($status === 'published' ? 'published' : 'saved as draft') . ' successfully.');
    }

    public function edit($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        return view('admin.blogs.form', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'nullable|string|max:500',
            'content'           => 'required|string',
            'category'          => 'nullable|string|max:100',
            'tags'              => 'nullable|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'action'            => 'required|in:draft,publish,save',
        ]);

        $data = [
            'title'             => $validated['title'],
            'excerpt'           => $validated['excerpt'] ?? null,
            'content'           => $validated['content'],
            'category'          => $validated['category'] ?? null,
            'tags'              => !empty($validated['tags']) ? array_map('trim', explode(',', $validated['tags'])) : [],
            'meta_title'        => $validated['meta_title'] ?? null,
            'meta_description'  => $validated['meta_description'] ?? null,
        ];

        if ($request->hasFile('cover_image')) {
            // $path = $request->file('cover_image')->store('blogs/covers', 'public');
            // $data['cover_image'] = Storage::url($path);
            $coverUrl = uploadImageToSpaces($request->file('cover_image'), 'blogs/covers');
            $data['cover_image'] = $coverUrl;
        }

        if ($validated['action'] === 'publish') {
            $data['status'] = 'published';
            $data['published_at'] = $blog->published_at ?? now();
        } elseif ($validated['action'] === 'draft') {
            $data['status'] = 'draft';
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy($id)
    {
        Blog::findOrFail($id)->delete();
        return back()->with('success', 'Blog deleted.');
    }

    public function togglePublish($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->status === 'published') {
            $blog->unpublish();
            $msg = 'Blog unpublished.';
        } else {
            $blog->publish();
            $msg = 'Blog published.';
        }

        return back()->with('success', $msg);
    }

    public function analytics($id)
    {
        $blog = Blog::findOrFail($id);
        $viewsQuery = BlogView::where('blog_id', $id);

        $analytics = [
            'total_views'         => $blog->views_count,
            'unique_views'        => (clone $viewsQuery)->distinct('ip_address')->count('ip_address'),
            'views_last_7_days'   => (clone $viewsQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'views_last_30_days'  => (clone $viewsQuery)->where('created_at', '>=', now()->subDays(30))->count(),
            'views_by_day'        => (clone $viewsQuery)
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')->get(),
        ];

        return view('admin.blogs.analytics', compact('blog', 'analytics'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $query = Post::query();
        if ($fromDate) $query->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $query->whereDate('created_at', '<=', $toDate);
        $posts = $query->latest()->paginate(15)->withQueryString();
        $stats = Post::query();
        if ($fromDate) $stats->whereDate('created_at', '>=', $fromDate);
        if ($toDate) $stats->whereDate('created_at', '<=', $toDate);
        $allPosts = $stats->get();
        $totalPost = $allPosts->count();
        $activePosts = $allPosts->where('status', 'published')->count();
        $draftPosts = $allPosts->where('status', '!=', 'published')->count();
        return view('admin.posts.index', compact('posts', 'totalPost', 'activePosts', 'draftPosts', 'fromDate', 'toDate'));
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300|unique:posts',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|image|max:5120',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);
        $data['slug'] = Str::slug($data['title']);
        if ($request->hasFile('thumbnail_url')) {
            $data['thumbnail_url'] = $request->file('thumbnail_url')->store('posts', 'public');
        }
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:300|unique:posts,title,' . $post->id,
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|image|max:5120',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);
        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('thumbnail_url')) {
            $data['thumbnail_url'] = $request->file('thumbnail_url')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Đã xóa bài viết.');
    }

    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $path = $request->file('upload')->store('posts/editor', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }
}

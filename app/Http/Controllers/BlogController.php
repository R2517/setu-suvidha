<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_mr', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('excerpt_mr', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('publishedPosts')
            ->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->published()
            ->with(['category', 'tags', 'author'])
            ->firstOrFail();

        $post->incrementViewCount();
        $relatedPosts = $post->getRelatedPosts(4);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = BlogPost::published()
            ->where('blog_category_id', $category->id)
            ->with(['tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('publishedPosts')
            ->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::published()
            ->whereHas('tags', fn($q) => $q->where('slug', $slug))
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount('publishedPosts')
            ->get();

        return view('blog.tag', compact('tag', 'posts', 'categories'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $query = BlogPost::published()
            ->with(['category', 'tags', 'author'])
            ->orderByDesc('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_mr', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(12)->withQueryString();

        return view('blog.search', compact('posts', 'search'));
    }

    public function rssFeed()
    {
        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->orderByDesc('published_at')
            ->limit(50)
            ->get();

        return response()
            ->view('blog.feed', compact('posts'))
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}

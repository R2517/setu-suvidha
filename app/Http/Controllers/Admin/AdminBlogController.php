<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AdminBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'tags', 'author'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('blog_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(20)->withQueryString();
        $categories = BlogCategory::all();

        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::where('is_active', true)->orderBy('sort_order')->get();
        $tags = BlogTag::orderBy('name_en')->get();
        
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_json' => 'required|json',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled,archived',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'featured_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'featured_image_alt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'tags_text' => 'nullable|string',
        ]);

        $contentJson = json_decode($validated['content_json'], true);
        $slug = BlogPost::generateUniqueSlug($validated['title']);
        $readingTime = BlogPost::calculateReadingTime($contentJson);

        $featuredImage = $validated['featured_image'] ?? null;
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $filename = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/blog', $filename);
            $featuredImage = '/storage/blog/' . $filename;
        }

        $post = BlogPost::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'content_json' => $contentJson,
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'status' => $validated['status'],
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $featuredImage,
            'featured_image_alt' => $validated['featured_image_alt'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'reading_time_minutes' => $readingTime,
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'robots' => $validated['status'] === 'draft' ? 'noindex, nofollow' : 'index, follow',
        ]);

        if ($request->filled('tags_text')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags_text)));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = BlogTag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name_en' => $tagName, 'name_mr' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully!');
    }

    public function edit($id)
    {
        $post = BlogPost::with(['tags'])->findOrFail($id);
        $categories = BlogCategory::where('is_active', true)->orderBy('sort_order')->get();
        $tags = BlogTag::orderBy('name_en')->get();
        
        return view('admin.blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_json' => 'required|json',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled,archived',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $contentJson = json_decode($validated['content_json'], true);
        
        if ($post->title !== $validated['title']) {
            $post->old_slug = $post->slug;
            $post->slug = BlogPost::generateUniqueSlug($validated['title'], $post->id);
        }

        $post->previous_content_json = $post->content_json;
        $post->version++;

        $post->update([
            'title' => $validated['title'],
            'content_json' => $contentJson,
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'status' => $validated['status'],
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $validated['featured_image'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'reading_time_minutes' => BlogPost::calculateReadingTime($contentJson),
            'published_at' => $validated['status'] === 'published' && !$post->published_at ? now() : $post->published_at,
            'robots' => $validated['status'] === 'draft' ? 'noindex, nofollow' : 'index, follow',
        ]);

        if ($request->filled('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully');
    }

    public function destroy($id)
    {
        BlogPost::findOrFail($id)->delete();
        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted');
    }

    public function uploadJson(Request $request)
    {
        $request->validate(['json_file' => 'required|file|mimes:json|max:5120']);
        
        $content = file_get_contents($request->file('json_file')->getRealPath());
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['json_file' => 'Invalid JSON file']);
        }

        return back()->with('json_content', $content);
    }

    public function validateJson(Request $request)
    {
        $request->validate(['content_json' => 'required|json']);
        
        $decoded = json_decode($request->content_json, true);
        
        if (!isset($decoded['blocks']) || !is_array($decoded['blocks'])) {
            return response()->json(['valid' => false, 'error' => 'Missing blocks array']);
        }

        return response()->json(['valid' => true, 'blocks_count' => count($decoded['blocks'])]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('blog/images', $filename, 'public');

        $blogImage = BlogImage::create([
            'user_id' => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'file_path' => '/storage/' . $path,
            'alt_text' => $request->alt_text,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'image' => $blogImage,
            'url' => asset($blogImage->file_path),
        ]);
    }

    public function imageLibrary()
    {
        $images = BlogImage::latest()->paginate(24);
        return view('admin.blog.images', compact('images'));
    }

    public function deleteImage($id)
    {
        $image = BlogImage::findOrFail($id);
        
        if ($image->file_path && Storage::disk('public')->exists(str_replace('/storage/', '', $image->file_path))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $image->file_path));
        }
        
        $image->delete();
        
        return back()->with('success', 'Image deleted');
    }
}

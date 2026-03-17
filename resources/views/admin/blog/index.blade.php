@extends('layouts.app')
@section('title', 'Blog Posts — Admin')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')

    <div class="flex-1 p-6 lg:p-8 bg-gray-50 dark:bg-gray-950 overflow-x-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="newspaper" class="w-6 h-6 text-amber-500"></i> Blog Posts
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manage blog articles and content</p>
                </div>
                <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i> New Post
                </a>
            </div>

            <form method="GET" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
                <div class="flex flex-wrap items-center gap-3">
                    <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    <select name="category" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="flex-1 min-w-[200px] px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition">
                        <i data-lucide="search" class="w-4 h-4 inline pointer-events-none"></i> Filter
                    </button>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                @if($posts->isEmpty())
                <div class="text-center py-16">
                    <i data-lucide="file-text" class="w-12 h-12 text-gray-400 mx-auto mb-3 pointer-events-none"></i>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No posts found</h3>
                    <p class="text-sm text-gray-500">Create your first blog post to get started</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Views</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                            @foreach($posts as $post)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                                <td class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $post->title }}</p>
                                    <p class="text-xs text-gray-400">/blog/{{ $post->slug }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if($post->category)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700">
                                        {{ $post->category->name_en }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-700',
                                            'published' => 'bg-green-100 text-green-700',
                                            'scheduled' => 'bg-blue-100 text-blue-700',
                                            'archived' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$post->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ number_format($post->view_count) }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $post->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($post->status === 'published')
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" title="View" class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition">
                                            <i data-lucide="external-link" class="w-4 h-4 pointer-events-none"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.blog.edit', $post->id) }}" title="Edit" class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition">
                                            <i data-lucide="edit" class="w-4 h-4 pointer-events-none"></i>
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4 pointer-events-none"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

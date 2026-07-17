@extends('layouts.app')
@section('title', 'Ads Management — Admin')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')

    <div class="flex-1 p-6 lg:p-8 bg-gray-50 dark:bg-gray-950 overflow-x-hidden">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="megaphone" class="w-6 h-6 text-blue-500"></i> Ads Management
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manage vertical and horizontal ads across the platform.</p>
                </div>
            </div>

            <form action="{{ route('admin.ads.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    @php
                        $slots = [
                            'left_sidebar' => 'Left Vertical Sidebar',
                            'right_sidebar' => 'Right Vertical Sidebar',
                            'top_banner' => 'Top Horizontal Banner',
                            'bottom_banner' => 'Bottom Horizontal Banner'
                        ];
                    @endphp

                    @foreach($slots as $key => $label)
                        @php $ad = $ads->get($key); @endphp
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100 dark:border-gray-800">
                                <h3 class="font-bold text-gray-800 dark:text-white">{{ $label }}</h3>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="{{ $key }}_is_active" value="1" class="sr-only peer" {{ ($ad && $ad->is_active) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="space-y-4" x-data="{ type: '{{ $ad->type ?? 'image' }}' }">
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Ad Type</label>
                                    <select name="{{ $key }}_type" x-model="type" class="w-full mt-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                                        <option value="image">Custom Banner Image</option>
                                        <option value="script">Custom Script (AdSense, etc)</option>
                                    </select>
                                </div>

                                <div x-show="type === 'image'" class="space-y-4">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Banner Image</label>
                                        <input type="file" name="{{ $key }}_image_file" accept="image/*" class="w-full mt-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700">
                                        @if($ad && $ad->type === 'image' && $ad->content)
                                            <div class="mt-2">
                                                <img src="{{ asset($ad->content) }}" class="max-h-32 rounded border border-gray-200">
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Target URL (Redirect on click)</label>
                                        <input type="url" name="{{ $key }}_target_url" value="{{ $ad->target_url ?? '' }}" class="w-full mt-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm" placeholder="https://...">
                                    </div>
                                </div>

                                <div x-show="type === 'script'" class="space-y-4" style="display:none">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Script Code / HTML</label>
                                        <textarea name="{{ $key }}_script_content" rows="4" class="w-full mt-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 font-mono text-xs">{{ ($ad && $ad->type === 'script') ? $ad->content : '' }}</textarea>
                                        <p class="text-[10px] text-gray-500 mt-1">Paste your Google AdSense code or iframe snippet here.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="mt-6">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl flex items-center gap-2 shadow-lg hover:shadow-xl transition-all">
                        <i data-lucide="save" class="w-5 h-5"></i> Save All Ad Settings
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

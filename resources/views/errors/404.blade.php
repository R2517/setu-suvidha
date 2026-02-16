@extends('layouts.app')
@section('title', '404 — Page Not Found')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-8xl font-black text-amber-500 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Page Not Found</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">हे पृष्ठ सापडले नाही. कृपया URL तपासा किंवा मुख्यपृष्ठावर जा.</p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="btn-primary !px-6 !py-3">मुख्यपृष्ठ</a>
            <a href="javascript:history.back()" class="px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">मागे जा</a>
        </div>
    </div>
</div>
@endsection

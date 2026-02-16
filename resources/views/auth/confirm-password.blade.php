@extends('layouts.app')
@section('title', 'पासवर्ड पुष्टी — SETU Suvidha')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-50 dark:bg-gray-950">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800 p-8">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">पासवर्ड पुष्टी करा</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">हे एक सुरक्षित क्षेत्र आहे. कृपया पुढे जाण्यापूर्वी तुमचा पासवर्ड पुष्टी करा.</p>
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पासवर्ड</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full btn-primary !py-3">पुष्टी करा</button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'किंमत व्यवस्थापन — Admin')
@section('content')
<div class="flex min-h-screen" x-data="{ tab: '{{ $tab }}', showAdd: false }">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">किंमत व्यवस्थापन</h1>
            <button @click="showAdd = !showAdd" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> नवीन जोडा
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        {{-- Add New Pricing Form --}}
        <div x-show="showAdd" x-transition class="mb-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4">नवीन किंमत जोडा</h2>
            <form method="POST" action="{{ route('admin.pricing.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Form Type *</label>
                    <input type="text" name="form_type" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white" placeholder="e.g. hamipatra">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Form Name *</label>
                    <input type="text" name="form_name" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white" placeholder="e.g. हमीपत्र">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">किंमत ₹ *</label>
                    <input type="number" name="price" step="0.01" min="0" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Audience *</label>
                    <select name="audience" required class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                        <option value="vle">VLE</option>
                        <option value="public">Public</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">जोडा</button>
                </div>
            </form>
        </div>

        {{-- Tab Switch --}}
        <div class="flex items-center gap-1 mb-5 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl w-fit">
            <button @click="tab = 'vle'" :class="tab === 'vle' ? 'bg-white dark:bg-gray-900 shadow text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 text-sm font-bold rounded-lg transition">
                <i data-lucide="users" class="w-4 h-4 inline -mt-0.5"></i> VLE Pricing
            </button>
            <button @click="tab = 'public'" :class="tab === 'public' ? 'bg-white dark:bg-gray-900 shadow text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 text-sm font-bold rounded-lg transition">
                <i data-lucide="globe" class="w-4 h-4 inline -mt-0.5"></i> Public Pricing
            </button>
        </div>

        {{-- VLE Pricing Table --}}
        <div x-show="tab === 'vle'" x-transition>
            @include('admin.partials._pricing-table', ['pricing' => $vlePricing, 'label' => 'VLE'])
        </div>

        {{-- Public Pricing Table --}}
        <div x-show="tab === 'public'" x-transition>
            @include('admin.partials._pricing-table', ['pricing' => $publicPricing, 'label' => 'Public'])
        </div>
    </div>
</div>
@endsection

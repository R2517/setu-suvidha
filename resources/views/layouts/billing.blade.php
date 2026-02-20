@extends('layouts.app')

@section('content')
<div class="flex" style="min-height: calc(100vh - 64px);">
    @include('billing._sidebar')
    <div class="flex-1 overflow-y-auto bg-gray-50/50 dark:bg-gray-950">
        @yield('billing-content')
    </div>
</div>
@endsection

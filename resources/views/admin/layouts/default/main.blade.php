@extends('admin.layouts.default.baseof')

@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- @include('admin.layouts.partials.navbar-main') --}}

    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col">
        <div class="container mx-auto px-4 py-8">
            @yield('content')
            <x-notify::notify />
            @notifyJs
        </div>
    </main>
@endsection

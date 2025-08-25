@extends('admin.layouts.default.baseof')
@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col">
        <div class="container mx-auto px-4 py-8">
            {{-- @include('admin.components.autentikasi.login') --}}
            @yield('content')
            @if ($errors->any())
                <script>
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Maaf',
                        text: '{{ $errors->first() }}',
                        showConfirmButton: true,
                    });
                </script>
            @endif
        </div>
    </main>
@endsection

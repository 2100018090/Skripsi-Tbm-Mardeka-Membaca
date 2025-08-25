@extends('user.layouts.default.baseof')
@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Cek apakah user sudah login dan role-nya anggota --}}
    @if (auth()->check() && auth()->user()->role === 'anggota')
        @include('user.layouts.partials.navuser')
        {{-- @include('user.components.chat') --}}
    @else
        @include('user.layouts.partials.navbar-dashboard')
    @endif

    <main class="bg-gray-50 min-h-screen flex flex-col">
        @yield('content')
        @if (session('success'))
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ $errors->first() }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            </script>
        @endif
    </main>
@endsection

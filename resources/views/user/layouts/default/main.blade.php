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

    <main class="bg-white min-h-screen flex flex-col">

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @yield('content')
        {{-- SweetAlert untuk session flash & error --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: @json(session('success')),
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
                    title: @json(session('error')),
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
                    text: @json($errors->first()),
                    showConfirmButton: true,
                    timer: 3000
                });
            </script>
        @endif


        @include('user.layouts.partials.footer')
    </main>
@endsection

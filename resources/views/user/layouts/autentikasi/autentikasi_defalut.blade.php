@extends('user.layouts.default.baseof')
@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <main class="bg-white min-h-screen flex flex-col">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    title: 'Login Gagal!',
                    text: '{{ $errors->first() }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            </script>
        @endif
    </main>
@endsection

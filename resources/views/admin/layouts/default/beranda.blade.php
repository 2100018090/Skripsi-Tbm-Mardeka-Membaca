@extends('admin.layouts.default.baseof')
@section('main')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('admin.layouts.partials.navbar-beranda')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">

        @include('admin.layouts.partials.sidebar')

        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main>
                {{-- @include('admin.components.beranda')   --}}
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

            </main>
            {{-- @include('admin.layouts.partials.footer-beranda') --}}
            {{-- <x-notify::notify />
            @notifyJs --}}
        </div>
    </div>
@endsection

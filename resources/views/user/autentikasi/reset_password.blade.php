@extends('user.layouts.autentikasi.autentikasi_defalut')

@section('content')
    <!-- component -->
    <div class="h-screen w-full flex overflow-hidden">
        <!-- Kolom kiri: Form Login -->
        <div class="flex-1 relative bg-white flex items-center justify-center flex-col md:flex-row">
            <!-- Logo untuk mobile -->
            <div class="absolute top-[-60px] left-6 md:hidden">
                @php
                    $logoPage = \App\Models\Page::where('slug', 'logo_login')->first();
                @endphp

                @if ($logoPage && $logoPage->img)
                    <img class="w-32 h-32"
                        src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                        alt="Logo Login Mobile">
                @else
                    <p class="text-sm text-red-500">Logo belum tersedia</p>
                @endif
            </div>

            <!-- Logo untuk desktop -->
            <div class="hidden md:block absolute top-[-64px] left-6">
                @if ($logoPage && $logoPage->img)
                    <img class="w-48 h-48"
                        src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                        alt="Logo Login Desktop">
                @else
                    <p class="text-sm text-red-500">Logo belum tersedia</p>
                @endif
            </div>

            <!-- Gambar Buku untuk mobile -->
            <div class="absolute top-28 md:hidden">
                <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-[250px] h-[250px] object-contain">
            </div>

            <!-- Form Login -->
            <div class="max-w-md w-full px-8 mt-60 md:mt-0 z-10">
                <div class="shadow-md rounded-lg px-8 py-6 bg-white">
                    <h1 class="text-2xl font-bold font-poppins text-primary-952 dark:text-gray-800 text-center">Reset
                        Password</h1>
                    <form action="{{ route('password.reset') }}" method="POST">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <!-- Password Baru -->
                        <div class="mb-4">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-800 mb-2">Password
                                baru</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                class="shadow-sm rounded-md w-full px-3 py-2 border-[#64C0B7] focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan Password">
                        </div>

                        <div class="mb-4">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-800 mb-2">Konfirmasi
                                Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                autocomplete="new-password"
                                class="shadow-sm rounded-md w-full px-3 py-2 border-[#64C0B7] focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan Password">
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit"
                            class="w-full py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-[#64C0B7] hover:bg-[#57b1a8] focus:ring-2 focus:ring-offset-2 focus:ring-[#64C0B7]">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Ilustrasi Buku untuk desktop -->
        <div class="flex-1 bg-[#64C0B7] hidden md:flex items-center justify-center">
            <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-[500px] h-[500px] object-contain">
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Flowbite JS -->
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
@endpush

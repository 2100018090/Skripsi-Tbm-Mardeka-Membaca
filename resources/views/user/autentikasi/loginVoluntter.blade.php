@extends('user.layouts.autentikasi.autentikasi_defalut')

@section('content')
    <!-- component -->
    <div class="h-screen w-full flex overflow-hidden">
        <!-- Kolom kiri: Form Login -->
        <div class="flex-1 relative bg-white flex items-center justify-center flex-col md:flex-row">
            <!-- Logo -->
            <div class="absolute top-[-60px] left-6 md:top-[-64px] md:left-6">
                @php
                    $logoPage = \App\Models\Page::where('slug', 'logo_login')->first();
                @endphp

                @if ($logoPage && $logoPage->img)
                    <img class="w-48 h-48"
                        src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                        alt="Logo Login">
                @else
                    <p>Logo belum tersedia</p>
                @endif
            </div>

            <!-- Gambar Buku untuk mobile -->
            <div class="absolute top-28 md:hidden">
                <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-[250px] h-[250px] object-contain">
            </div>

            <!-- Form Login -->
            <div class="max-w-md w-full px-8 mt-60 md:mt-0 z-10">
                <div class="shadow-md rounded-lg px-8 py-6 bg-white">
                    <h1 class="text-2xl font-bold font-poppins text-primary-952 dark:text-gray-800 text-center">Masuk</h1>
                    <form action="{{ route('loginVoluntter') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-800 mb-2">Email</label>
                            <input type="text" id="email" name="email"
                                class="shadow-sm rounded-md w-full px-3 py-2 border-[#64C0B7] focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan Email">
                        </div>

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-800 mb-2">
                                Password
                            </label>

                            <input type="password" id="password" name="password" autocomplete="new-password"
                                class="shadow-sm rounded-md w-full px-3 py-2 border-[#64C0B7] focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 pr-10"
                                placeholder="Masukkan Password">

                            <!-- Ikon Mata -->
                            <button type="button" id="togglePassword"
                                class="absolute top-9 right-3 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        {{-- reCAPTCHA --}}
                        <div class="mb-4">
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            @error('g-recaptcha-response')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const toggleBtn = document.getElementById('togglePassword');
                                const passwordInput = document.getElementById('password');
                                const eyeIcon = document.getElementById('eyeIcon');

                                toggleBtn.addEventListener('click', function() {
                                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                    passwordInput.setAttribute('type', type);

                                    // Ganti ikon (jika ingin)
                                    if (type === 'text') {
                                        eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592m3.87-2.44A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.966 9.966 0 01-4.293 5.118M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3l18 18" />`;
                                    } else {
                                        eyeIcon.innerHTML =
                                            `
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                                    }
                                });
                            });
                        </script>



                        <!-- Lupa Password -->
                        <div class="flex justify-end mb-4">
                            <button type="button" data-modal-target="forgotPasswordModal"
                                data-modal-toggle="forgotPasswordModal" class="text-sm text-[#64C0B7] hover:underline">
                                Lupa Password?
                            </button>
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit"
                            class="w-full py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-[#64C0B7] hover:bg-[#57b1a8] focus:ring-2 focus:ring-offset-2 focus:ring-[#64C0B7]">
                            Masuk
                        </button>

                        <!-- Link Daftar -->
                        <div class="text-center mt-4">
                            <span class="text-sm text-gray-600">Belum punya akun?</span>
                            <a href="{{ route('daftar') }}"
                                class="text-sm text-[#64C0B7] font-medium hover:underline">Daftar di sini</a>
                        </div>
                    </form>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Ilustrasi Buku untuk desktop -->
        <div class="flex-1 bg-[#64C0B7] hidden md:flex items-center justify-center">
            <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-[500px] h-[500px] object-contain">
        </div>
    </div>

    <!-- Modal: Lupa Password -->
    <div id="forgotPasswordModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full h-full bg-black bg-opacity-50">
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg">
            <div class="flex justify-between items-center pb-2 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Lupa Password
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-900 text-2xl font-bold"
                    data-modal-hide="forgotPasswordModal">&times;</button>
            </div>
            <form action="{{ route('password.kirim') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="resetEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="resetEmail" name="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#64C0B7] focus:border-[#64C0B7]"
                        placeholder="Masukkan email terdaftar" required>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-white bg-[#64C0B7] hover:bg-[#57b1a9] rounded-md">
                    Kirim Link Reset
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Flowbite JS -->
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
@endpush

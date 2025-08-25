@extends('user.layouts.autentikasi.autentikasi_defalut')

@section('content')
<div class="min-h-screen w-full flex flex-col md:flex-row bg-white">

    {{-- Kolom kiri: konten form & logo --}}
    <div class="flex-1 flex flex-col items-center justify-center p-6">

        {{-- Logo --}}
        @php
            $logoPage = \App\Models\Page::where('slug', 'logo_login')->first();
        @endphp
        @if ($logoPage && $logoPage->img)
            <img 
                class="w-28 h-28 md:w-40 md:h-40 mb-4"
                src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                alt="Logo Login">
        @else
            <p class="text-sm text-red-500 mb-4">Logo belum tersedia</p>
        @endif

        {{-- Gambar buku (mobile saja) --}}
        <div class="md:hidden mb-6">
            <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-48 h-48 object-contain">
        </div>

        {{-- Form --}}
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold font-poppins text-black text-center mb-6">Daftar</h1>
            <form action="{{ route('akun.createAkun') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-black mb-2">Email</label>
                    <input type="text" id="email" name="email"
                        class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan Email">
                </div>

                {{-- Username --}}
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-black mb-2">Username</label>
                    <input type="text" id="username" name="username"
                        class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan Username">
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-black mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan Password">
                </div>

                {{-- Identitas --}}
                <div class="mb-4">
                    <label for="img_identitas" class="block text-sm font-medium text-black mb-2">Identitas</label>
                    <input type="file" id="img_identitas" name="img_identitas"
                        class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Role --}}
                <input type="hidden" name="role" value="anggota" />

                {{-- reCAPTCHA --}}
                <div class="mb-4">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @error('g-recaptcha-response')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Button --}}
                <button type="submit"
                    class="w-full py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-[#64C0B7] hover:bg-[#57b1a8] focus:ring-2 focus:ring-offset-2 focus:ring-[#64C0B7]">Daftar</button>

                {{-- Link Login --}}
                <div class="text-center mt-4">
                    <span class="text-sm text-gray-600">Sudah punya akun?</span>
                    <a href="{{ route('masuk') }}" class="text-sm text-[#64C0B7] font-medium hover:underline">
                        Login di sini
                    </a>
                </div>
            </form>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        </div>
    </div>

    {{-- Kolom kanan: Gambar Buku (desktop saja) --}}
    <div class="flex-1 bg-[#64C0B7] hidden md:flex items-center justify-center">
        <img src="{{ asset('storage/icon/Buku.svg') }}" alt="Buku" class="w-[500px] h-[500px] object-contain">
    </div>
</div>
@endsection

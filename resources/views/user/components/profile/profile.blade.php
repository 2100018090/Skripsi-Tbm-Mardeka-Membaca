@extends('user.layouts.default.beranda')
@section('content')
    <!-- component -->
    @php
        $akun = $akun ?? Auth::user();
        $anggota = $anggota ?? $akun->anggota;
    @endphp

    <div class="flex justify-center px-4 py-10">
        <div
            class="relative w-full max-w-md sm:max-w-xl bg-[#64C0B7]  border border-emerald-500 rounded-2xl shadow-lg dark:shadow-white px-8 pt-20 pb-10">

            {{-- Foto Profil --}}
            <div class="absolute -top-12 left-1/2 -translate-x-1/2">
                <div class="w-28 h-28 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-200">
                    @if ($anggota && $anggota->img)
                        <img src="{{ $anggota && $anggota->img
                            ? rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $anggota->img
                            : asset('storage/pengumuman/default.png') }}"
                            alt="Foto Profil" class="w-full h-full object-cover" />
                    @else
                        <img src="{{ asset('storage/icon/default-user.png') }}" alt="Default Profile"
                            class="w-full h-full object-cover" />
                    @endif
                </div>
            </div>

            {{-- Judul --}}
            <h1 class="text-center text-3xl font-bold text-gray-900 dark:text-white mb-10">Profil Anggota</h1>

            <form action="{{ $anggota ? route('akun.updateOnlyAnggota', $anggota->id) : route('akun.createOnlyAnggota') }}"
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if ($anggota)
                    @method('PUT')
                @endif

                {{-- Email (readonly) --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <label for="email" class="sm:w-1/4 font-semibold text-white">Email</label>
                    <input id="email" name="email" type="text" readonly
                        value="{{ old('email', $akun->email ?? '') }}"
                        class="flex-1 border border-emerald-500 rounded-md px-4 py-2 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white text-base" />
                </div>

                {{-- Nama --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <label for="nama" class="sm:w-1/4 font-semibold text-white">Nama</label>
                    <input id="nama" name="nama" type="text" value="{{ old('nama', $anggota->nama ?? '') }}"
                        class="flex-1 border border-emerald-500 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base" />
                </div>

                {{-- No Telepon --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <label for="notlp" class="sm:w-1/4 font-semibold text-white">No Telepon</label>
                    <input id="notlp" name="notlp" type="text" value="{{ old('notlp', $anggota->notlp ?? '') }}"
                        class="flex-1 border border-emerald-500 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base" />
                </div>

                {{-- Alamat --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <label for="alamat" class="sm:w-1/4 font-semibold text-white">Alamat</label>
                    <input id="alamat" name="alamat" type="text" value="{{ old('alamat', $anggota->alamat ?? '') }}"
                        class="flex-1 border border-emerald-500 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base" />
                </div>

                {{-- Upload Foto --}}
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <label for="img" class="sm:w-1/4 font-semibold text-white">
                        {{ $anggota ? 'Ubah Foto' : 'Foto Baru' }}
                    </label>
                    <input id="img" name="img" type="file"
                        class="flex-1 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-emerald-500 file:text-white file:font-semibold dark:file:bg-emerald-600" />
                </div>

                {{-- Tombol Simpan / Update --}}
                {{-- Tombol Simpan / Update --}}
                <div class="flex justify-end gap-3">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 bg-[#64C0B7] hover:bg-emerald-600 transition-colors text-white font-semibold rounded-md shadow-md">
                        <img src="{{ asset('storage/icon/pencil.svg') }}" alt="Edit Icon" class="w-4 h-4" />
                        {{ $anggota ? 'Perbarui' : 'Simpan' }}
                    </button>

                    {{-- Tombol Ajukan Volunteer (muncul hanya jika anggota dan akses bukan pending/plus) --}}
                    @if ($anggota && !in_array(strtolower($anggota->akses), ['pending', 'plus']))
                        <a href="{{ route('anggota.apply', $anggota->id) }}"
                            class="flex items-center gap-2 px-6 py-2 bg-indigo-500 hover:bg-indigo-600 transition-colors text-white font-semibold rounded-md shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>
                            Ajukan Volunteer
                        </a>
                    @endif
                </div>

            </form>

        </div>
    </div>
@endsection

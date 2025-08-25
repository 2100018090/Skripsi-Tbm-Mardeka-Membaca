@extends('admin.layouts.default.beranda')

@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="grid grid-cols-1 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $roleName }} Profile</h1>
        </div>

        <!-- Foto Profil -->
        <div class="col-span-full xl:col-auto">
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                    <img class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0"
                        src="@if ($roleName === 'Admin') {{ $profileData->img ? asset('storage/' . $profileData->img) : asset('storage/admin/default.png') }}
                             @elseif ($roleName === 'Voluntter')
                                {{ $profileData->img
                                    ? rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $profileData->img
                                    : asset('storage/pengumuman/default.png') }} @endif"
                        alt="Foto Profil {{ $roleName }}">

                    <div>
                        {{-- <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">Foto Profil</h3>
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            JPG, PNG, WEBP, Max 2MB
                        </div>
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('admin.uploadImg') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="imgUpload"
                                    class="inline-flex cursor-pointer items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z">
                                        </path>
                                        <path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path>
                                    </svg>
                                    Ubah Gambar
                                    <input type="file" id="imgUpload" name="img" accept="image/*" class="hidden"
                                        onchange="this.form.submit()">
                                </label>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Akun -->
        <div class="col-span-2">
            <div
                class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">Data Akun</h3>
                <form action="" method="POST">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">
                        @if ($roleName === 'Admin')
                            <!-- Nama -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nama"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $profileData?->nama) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>

                            <!-- Alamat -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="alamat"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                                <input type="text" name="alamat" id="alamat"
                                    value="{{ old('alamat', $profileData?->alamat) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>

                            <!-- No Telepon -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="notlp"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No Telepon</label>
                                <input type="text" name="notlp" id="notlp"
                                    value="{{ old('notlp', $profileData?->notlp) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>
                        @elseif ($roleName === 'Voluntter')
                            <!-- Nama -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nama"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama', $profileData?->nama) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>

                            <!-- Jabatan -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="jabatan"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan"
                                    value="{{ old('jabatan', $profileData?->jabatan) }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

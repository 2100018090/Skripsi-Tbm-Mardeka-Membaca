@extends('admin.layouts.default.beranda')
@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Buku</h1>
            </div>
            <div class="sm:flex">
                <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <form class="lg:pr-3" action="{{ route('admin.cariBuku') }}" method="GET">
                        <label for="users-search" class="sr-only">Search</label>
                        <div class="relative mt-1 lg:w-64 xl:w-96 flex items-center gap-2">
                            <input type="text" name="keyword" id="users-search" value="{{ request('keyword') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
            dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Masukkan Nama Buku">

                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-500 dark:hover:bg-primary-600">
                                Cari
                            </button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('users-search');
                            const form = searchInput.closest('form');

                            searchInput.addEventListener('input', function() {
                                if (this.value.trim() === '') {
                                    form.submit(); // auto-submit saat kosong
                                }
                            });
                        });
                    </script>

                </div>

                <div class="flex items-center ml-auto space-x-2 sm:space-x-3">

                    @php
                        $kategoris = \App\Models\Kategori::all();
                    @endphp

                    <div class="relative inline-block text-left w-1/2 sm:w-auto">
                        <button id="exportBtn" type="button"
                            class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                            aria-haspopup="true" aria-expanded="false">
                            Kategori Buku
                            <svg class="w-4 h-4 ml-2 -mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownMenu"
                            class="hidden origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 z-50">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="exportBtn">
                                @foreach ($kategoris as $kategori)
                                    <a href="{{ route('admin.bukuByKategori', $kategori->id) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                        role="menuitem">{{ $kategori->nama }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <script>
                        const exportBtn = document.getElementById('exportBtn');
                        const dropdownMenu = document.getElementById('dropdownMenu');

                        exportBtn.addEventListener('click', function() {
                            dropdownMenu.classList.toggle('hidden');
                        });

                        // Klik di luar dropdown untuk tutup dropdown
                        window.addEventListener('click', function(e) {
                            if (!exportBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                                dropdownMenu.classList.add('hidden');
                            }
                        });
                    </script>

                    <button type="button" data-modal-target="add-kategori-modal" data-modal-toggle="add-kategori-modal"
                        class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-[#64C0B7] hover:bg-[#57b0a8] focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-[#64C0B7] dark:hover:bg-[#57b0a8] dark:focus:ring-primary-800">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Tambah Kategori
                    </button>


                    <!-- Wrapper untuk dropdown -->
                    <div class="relative inline-block text-left">
                        <!-- Tombol utama -->
                        <button type="button" id="dropdownTambahBukuButton"
                            class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tambah Buku
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownTambahBukuMenu"
                            class="hidden absolute z-10 mt-2 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                                <li>
                                    <!-- Buku Digital (Online) -->
                                    <button type="button" data-modal-target="add-buku-digital"
                                        data-modal-toggle="add-buku-digital"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Buku Digital
                                    </button>
                                </li>
                                <li>
                                    <!-- Buku Fisik (Offline) -->
                                    <button type="button" data-modal-target="add-buku-fisik"
                                        data-modal-toggle="add-buku-fisik"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        Buku Fisik
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Script toggle dropdown -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const btn = document.getElementById("dropdownTambahBukuButton");
                            const menu = document.getElementById("dropdownTambahBukuMenu");

                            btn.addEventListener("click", () => {
                                menu.classList.toggle("hidden");
                            });

                            // Tutup dropdown saat klik di luar
                            document.addEventListener("click", (event) => {
                                if (!btn.contains(event.target) && !menu.contains(event.target)) {
                                    menu.classList.add("hidden");
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Judul
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Kategori
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Penulis
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Penerbit
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Tahun Terbit
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Tipe
                                </th>
                                @if (in_array(auth()->user()->role, ['admin', 'voluntter']))
                                    <th scope="col"
                                        class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Aksi
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @include('admin.components.crud.data_buku')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- update data anggota --}}
    @include('admin.components.crud.edit_buku')

    {{-- tambah data buku digital --}}
    @include('admin.components.crud.buku.tambah_buku_digital')

    {{-- tambah data buku fisik --}}
    @include('admin.components.crud.buku.tambah_buku_fisik')

    {{-- tambah data kategori --}}
    @include('admin.components.crud.tambah_kategori')
@endsection

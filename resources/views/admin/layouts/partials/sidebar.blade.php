@php
    $url = explode('/', request()->url());
    $page_slug = $url[count($url) - 2];
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 z-20 flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 transition-width lg:flex"
    aria-label="Sidebar">
    <div
        class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
            <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <ul class="pb-2 space-y-2">

                    {{-- Beranda --}}
                    <li>
                        <a href="{{ url('/admin') }}"
                            class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                            <img src="{{ asset('storage/icon/beranda.png') }}" alt="Beranda Icon" class="w-6 h-6">
                            <span class="ml-3" sidebar-toggle-item>Beranda</span>
                        </a>
                    </li>

                    {{-- Data Buku --}}
                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                            aria-controls="dropdown-crud" data-collapse-toggle="dropdown-crud">
                            <img src="{{ asset('storage/icon/data_peminjaman.png') }}" alt="Beranda Icon"
                                class="w-6 h-6">
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Data
                                Peminjaman</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-crud"
                            class="space-y-2 py-2 {{ Request::is('peminjaman*') || Request::is('konfirmasi*') || Request::is('denda*') ? 'block' : 'hidden' }}">
                            <li>
                                <a href="{{ route('admin.layoutsPeminjaman') }}"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
            {{ Request::is('peminjaman*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                    Data Peminjaman
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.layoutsPeminjamanDenda') }}"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
            {{ Request::is('denda*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                    Denda
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.layoutsbukufisik') }}"
                            class="flex items-center w-full p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                            <img src="{{ asset('storage/icon/data_buku.png') }}" alt="Beranda Icon" class="w-6 h-6">
                            <span class="ml-3">Data Buku</span>
                        </a>
                    </li>

                    {{-- Data Pengumuman --}}
                    <li>
                        <a href="{{ route('admin.pengumuman') }}"
                            class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                            <img src="{{ asset('storage/icon/data_pengumuman.png') }}" alt="Beranda Icon"
                                class="w-6 h-6">
                            <span class="ml-3" sidebar-toggle-item>Data Pengumuman</span>
                        </a>
                    </li>

                    {{-- Data Anggota --}}
                    @if (
                        !(Auth::check() &&
                            (Auth::user()->role === 'voluntter' || (isset($anggota) && strtolower($anggota->akses) === 'plus'))
                        ))
                        <li>
                            <button type="button"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                aria-controls="dropdown-user" data-collapse-toggle="dropdown-user">
                                <img src="{{ asset('storage/icon/data_peminjaman.png') }}" alt="Beranda Icon"
                                    class="w-6 h-6">
                                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>
                                    Data User
                                </span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <ul id="dropdown-user"
                                    class="space-y-2 py-2 {{ Request::is('volunteer*') || Request::is('logs*') || Request::is('settings*') ? 'block' : 'hidden' }}">

                                    <li>
                                        <a href="{{ route('admin.konfirmasiVoluntter') }}"
                                            class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
        {{ Request::is('settings*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                            Konfirmasi Voluntter
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('admin.data_anggota') }}"
                                            class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
        {{ Request::is('settings*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                            Data Anggota
                                        </a>
                                    </li>

                                    {{-- Data Volunteer --}}
                                    <li>
                                        <a href="{{ route('admin.data_voluntter') }}"
                                            class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
        {{ Request::is('volunteer*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                            Data Volunteer
                                        </a>
                                    </li>

                                    {{-- Log Aktivitas --}}
                                    <li>
                                        <a href="{{ route('admin.logs_activity') }}"
                                            class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700
        {{ Request::is('logs*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                                            Aktivitas Anggota
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif

                    @if (auth()->check() && auth()->user()->role === 'voluntter')
                        {{-- Chat Voluntter --}}
                        {{-- <li>
                            <a href="{{ route('admin.layoutsChat') }}"
                                class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <img src="{{ asset('storage/icon/data_anggota.png') }}" alt="Beranda Icon"
                                    class="w-6 h-6">
                                <span class="ml-3" sidebar-toggle-item>Chat</span>
                            </a>
                        </li> --}}
                    @endif

                    {{-- Pengaturan --}}
                    @if (!(Auth::user()->role === 'voluntter' || (isset($anggota) && strtolower($anggota->akses) === 'plus')))
                        <li>
                            <a href="{{ route('admin.settingAdmin') }}"
                                class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 {{ $page_slug == 'settings' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M8.34 1.804A1 1 0 019.32 1h1.36a1 1 0 01.98.804l.295 1.473c.497.144.971.342 1.416.587l1.25-.834a1 1 0 011.262.125l.962.962a1 1 0 01.125 1.262l-.834 1.25c.245.445.443.919.587 1.416l1.473.294a1 1 0 01.804.98v1.361a1 1 0 01-.804.98l-1.473.295a6.95 6.95 0 01-.587 1.416l.834 1.25a1 1 0 01-.125 1.262l-.962.962a1 1 0 01-1.262.125l-1.25-.834a6.953 6.953 0 01-1.416.587l-.294 1.473a1 1 0 01-.98.804H9.32a1 1 0 01-.98-.804l-.295-1.473a6.957 6.957 0 01-1.416-.587l-1.25.834a1 1 0 01-1.262-.125l-.962-.962a1 1 0 01-.125-1.262l.834-1.25a6.957 6.957 0 01-.587-1.416l-1.473-.294A1 1 0 011 10.68V9.32a1 1 0 01.804-.98l1.473-.295c.144-.497.342-.971.587-1.416l-.834-1.25a1 1 0 01.125-1.262l.962-.962A1 1 0 015.38 3.03l1.25.834a6.957 6.957 0 011.416-.587l.294-1.473zM13 10a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <span class="ml-3" sidebar-toggle-item>Setting</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Navbar Responsive</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer>
        // Tunggu DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenuButton.setAttribute('aria-expanded', 'false');

            mobileMenuButton.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('hidden');
            });

            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            if (userMenuButton && userMenu) {
                userMenuButton.setAttribute('aria-expanded', 'false');
                userMenuButton.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    userMenu.classList.toggle('hidden');
                });
            }

            // Logout link mobile dengan SweetAlert2
            const logoutLink = document.getElementById('logout-link');
            const logoutForm = document.getElementById('logout-form');
            if (logoutLink && logoutForm) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin Keluar?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#64C0B7',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }
        });

        // Fungsi konfirmasi logout desktop
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin Keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#64C0B7',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white">
    <nav class="sticky top-0 z-50 bg-[#64C0B7] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Kiri -->
                <div class="flex-shrink-0">
                    @php
                        $logoPage = \App\Models\Page::where('slug', 'logo_beranda')->first();
                    @endphp
                    @if ($logoPage && $logoPage->img)
                        <img class="h-8 w-auto"
                            src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                            alt="Logo">
                    @endif
                </div>

                <!-- Navigasi Tengah -->
                <div class="hidden md:flex flex-1 justify-center">
                    <div class="flex space-x-8">
                        <a href="{{ route('anggota.berandaAnggota') }}"
                            class="text-black font-bold border-b-2 border-black px-1 pt-1 inline-flex items-center text-lg">Beranda</a>
                        <a href="{{ route('anggota.layoutsRiwayat') }}"
                            class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Riwayat</a>
                        <a href="{{ route('anggota.layoutskoleksi') }}"
                            class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Koleksi</a>
                        <a href="{{ route('anggota.anggota_pengumuman') }}"
                            class="text-black hover:text-white border-b-2 border-transparent hover:border-white px-1 pt-1 inline-flex items-center text-lg font-bold">Pengumuman</a>
                    </div>
                </div>

                <!-- Profil Kanan -->
                <div class="flex items-center space-x-4">

                    @php
                        use App\Models\Notifikasi;

                        $user = auth()->user();
                        $notifs = [];
                        $jumlahBelumDibaca = 0;

                        if ($user && $user->anggota) {
                            $idAnggota = $user->anggota->id;

                            $notifs = Notifikasi::where('id_anggota', $idAnggota)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

                            $jumlahBelumDibaca = Notifikasi::where('id_anggota', $idAnggota)
                                ->where('dibaca', false)
                                ->count();
                        }
                    @endphp

                    <!-- Ikon Notifikasi -->
                    <!-- Container Notifikasi -->
                    <div class="relative block">
                        <!-- Tombol Notifikasi -->
                        <button id="notification-button"
                            class="text-white hover:text-gray-200 focus:outline-none relative transition duration-150 ease-in-out"
                            aria-label="Notifikasi" aria-expanded="false">
                            <!-- Ikon lonceng -->
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
             a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
             C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5
             m6 0v1a3 3 0 11-6 0v-1h6z" />
                            </svg>
                            <!-- Badge -->
                            @if ($jumlahBelumDibaca > 0)
                                <span
                                    class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                                    {{ $jumlahBelumDibaca }}
                                </span>
                            @endif

                        </button>

                        <!-- Dropdown Notifikasi (responsif) -->
                        <div id="notification-dropdown"
                            class="hidden absolute right-0 md:mt-3 mt-2 w-72 max-w-xs bg-white text-black rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 transition-all duration-300 ease-out transform origin-top scale-95">

                            <div
                                class="py-3 px-4 text-sm max-h-[60vh] overflow-y-auto divide-y divide-gray-200 scrollbar-thin scrollbar-thumb-[#64C0B7] scrollbar-track-gray-100">
                                <div class="pb-2 font-semibold text-gray-700">Notifikasi</div>
                                <div class="pt-2 space-y-2">
                                    @forelse ($notifs as $notif)
                                        <div
                                            class="flex items-start gap-2 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                                            <div class="text-blue-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3a2.121 2.121 0 113 3L7 18l-4 1 1-4L16.5 3z" />
                                                </svg>
                                            </div>
                                            <div class="text-sm">
                                                <p class="text-gray-800">{{ $notif->pesan }}</p>
                                                <span
                                                    class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-gray-400">Tidak ada notifikasi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const notifButton = document.getElementById('notification-button');
                            const notifDropdown = document.getElementById('notification-dropdown');

                            // Toggle dropdown saat tombol diklik
                            notifButton.addEventListener('click', function(event) {
                                event.stopPropagation(); // Cegah penutupan langsung saat klik ikon

                                const isExpanded = notifButton.getAttribute('aria-expanded') === 'true';
                                notifButton.setAttribute('aria-expanded', !isExpanded);
                                notifDropdown.classList.toggle('hidden');
                                notifDropdown.classList.toggle('scale-100');
                                notifDropdown.classList.toggle('scale-95');

                                // Tandai notifikasi sebagai sudah dibaca
                                fetch("{{ route('notifikasi.baca') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                }).then(response => {
                                    if (response.ok) {
                                        const badge = notifButton.querySelector('span');
                                        if (badge) badge.remove();
                                    }
                                });
                            });

                            // Klik di luar dropdown => tutup dropdown
                            document.addEventListener('click', function(event) {
                                const isClickInside = notifButton.contains(event.target) || notifDropdown.contains(event
                                    .target);

                                if (!isClickInside) {
                                    notifDropdown.classList.add('hidden');
                                    notifDropdown.classList.remove('scale-100');
                                    notifDropdown.classList.add('scale-95');
                                    notifButton.setAttribute('aria-expanded', false);
                                }
                            });
                        });
                    </script>


                    <!-- Tombol profil desktop -->
                    <!-- Tombol profil desktop -->
                    <div class="relative hidden md:block">
                        <button type="button" id="user-menu-button"
                            class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white"
                            aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            @php
                                $isLogin = Auth::check();
                                $user = $isLogin ? Auth::user() : null;
                                $anggota = $isLogin ? $user->anggota : null;

                                $img =
                                    $anggota && $anggota->img
                                        ? rtrim(env('SUPABASE_URL'), '/') .
                                            '/storage/v1/object/public/' .
                                            env('SUPABASE_BUCKET') .
                                            '/' .
                                            $anggota->img
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Guest');
                            @endphp

                            <img class="h-8 w-8 rounded-full object-cover" src="{{ $img }}"
                                alt="{{ $user->name ?? 'Guest' }}" />
                        </button>

                        <!-- Dropdown menu user -->
                        <div id="user-menu"
                            class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 text-gray-700 z-50"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                            @if ($isLogin)
                                <a href="{{ route('anggota.profileAnggota') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">Profil Anggota</a>
                                <button type="button" onclick="confirmLogout()"
                                    class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100"
                                    role="menuitem">Keluar</button>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm hover:bg-gray-100"
                                    role="menuitem">Masuk</a>
                            @endif
                        </div>
                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const menuButton = document.getElementById('user-menu-button');
                            const userMenu = document.getElementById('user-menu');

                            let isMenuOpen = false;

                            // Toggle menu saat klik avatar
                            menuButton.addEventListener('click', function(event) {
                                event.stopPropagation(); // agar tidak langsung tertutup
                                isMenuOpen = !isMenuOpen;

                                userMenu.classList.toggle('hidden', !isMenuOpen);
                                menuButton.setAttribute('aria-expanded', isMenuOpen ? 'true' : 'false');
                            });

                            // Jangan tutup saat klik di dalam menu
                            userMenu.addEventListener('click', function(event) {
                                event.stopPropagation();
                            });

                            // Klik di luar: tutup menu
                            document.addEventListener('click', function() {
                                if (isMenuOpen) {
                                    userMenu.classList.add('hidden');
                                    menuButton.setAttribute('aria-expanded', 'false');
                                    isMenuOpen = false;
                                }
                            });
                        });

                        // Logout dengan konfirmasi
                        function confirmLogout() {
                            Swal.fire({
                                title: 'Ingin Keluar ?',
                                text: "Terima Kasih Sudah berkunjung",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Keluar',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('logout-form').submit();
                                }
                            });
                        }
                    </script>




                    <!-- Tombol menu mobile -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" aria-controls="mobile-menu" aria-expanded="false"
                            class="text-white hover:text-gray-200 focus:outline-none">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="md:hidden hidden bg-[#64C0B7]" id="mobile-menu" role="menu" aria-orientation="vertical"
            aria-labelledby="mobile-menu-button">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('anggota.berandaAnggota') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                    role="menuitem">Beranda</a>
                <a href="{{ route('anggota.layoutsRiwayat') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                    role="menuitem">Riwayat</a>
                <a href="{{ route('anggota.layoutskoleksi') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                    role="menuitem">Koleksi</a>
                <a href="{{ route('anggota.anggota_pengumuman') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                    role="menuitem">Pengumuman</a>
            </div>

            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $img }}"
                                alt="{{ $user->name ?? 'Guest' }}" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm sm:text-base font-medium text-black truncate">
                                {{ $anggota->nama ?? ($user->name ?? 'Tamu') }}
                            </div>
                            <div class="text-xs sm:text-sm font-medium text-gray-600 truncate">
                                {{ $user->email ?? 'Tidak ada email' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 px-2 space-y-1">
                    @if ($isLogin)
                        <a href="{{ route('anggota.profileAnggota') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                            role="menuitem">Profil Anggota</a>
                        <a href="{{ route('admin.logout') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                            role="menuitem"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display:none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-white hover:bg-gray-700"
                            role="menuitem">Masuk</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

</body>

</html>

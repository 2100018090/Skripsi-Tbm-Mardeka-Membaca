<!-- Navbar -->
<nav id="header" class="fixed w-full z-30 top-0 py-1 bg-[#64C0B7] shadow-lg border-b border-blue-400">
    <div class="w-full flex items-center justify-between px-6 py-2 relative">

        <!-- Input harus di atas label agar berfungsi -->
        <input class="hidden" type="checkbox" id="menu-toggle">

        <!-- Logo (kiri) -->
        <div class="flex items-center space-x-2 ml-6">
            @php
                $logoPage = \App\Models\Page::where('slug', 'logo_beranda')->first();
            @endphp

            @if ($logoPage && $logoPage->img)
                <img class="h-8 w-auto"
                    src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                    alt="Logo">
            @else
                <p>Logo belum tersedia</p>
            @endif
        </div>

        <!-- Mobile: Hamburger Menu -->
        <div class="md:hidden flex items-center z-50">
            <label for="menu-toggle" class="cursor-pointer">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 20 20">
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </label>
        </div>

        <!-- Desktop: Center Navigation -->
        <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2" id="menu">
            <nav>
                <ul class="flex space-x-6 text-white text-base">
                    <li><a href="#beranda" class="text-black hover:text-gray-700 font-medium text-lg">Beranda</a></li>
                    <li><a href="#tentang" class="text-black hover:text-gray-700 font-medium text-lg">Tentang</a></li>
                    <li><a href="#buku-populer" class="text-black hover:text-gray-700 font-medium text-lg">Buku
                            Populer</a></li>
                    <li><a href="#pengurus" class="text-black hover:text-gray-700 font-medium text-lg">Pengurus</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Desktop: Auth Buttons -->
        <div class="hidden md:flex items-center space-x-2 ml-auto">
            <a href="{{ route('masuk') }}"
                class="bg-transparent text-white px-4 py-1 rounded-xl border border-white hover:bg-white hover:text-[#64C0B7] inline-block text-center transition">
                Masuk
            </a>

            <a href="{{ route('daftar') }}"
                class="bg-[#003350] text-white px-4 py-1 rounded-xl hover:bg-gray-200 inline-block text-center transition">
                Daftar
            </a>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2 bg-[#64C0B7] text-white">
        <a href="#beranda" class="block py-2 text-black hover:text-gray-700 font-bold">Beranda</a>
        <a href="#tentang" class="block py-2 text-black hover:text-gray-700 font-bold">Tentang</a>
        <a href="#buku-populer" class="block py-2 text-black hover:text-gray-700 font-bold">Buku Populer</a>
        <a href="#pengurus" class="block py-2 text-black hover:text-gray-700 font-bold">Pengurus</a>
        <div class="pt-2 space-x-2">
            <a href="{{ route('masuk') }}"
                class="bg-transparent text-white rounded-xl border border-white px-4 py-1 hover:bg-white hover:text-[#64C0B7] inline-block text-center">
                Masuk
            </a>
            <a href="{{ route('daftar') }}"
                class="bg-[#003350] text-white px-4 py-1 rounded-xl hover:bg-gray-200 inline-block text-center">
                Daftar
            </a>
        </div>
    </div>
</nav>

<!-- Script Toggle -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggle.addEventListener('change', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>

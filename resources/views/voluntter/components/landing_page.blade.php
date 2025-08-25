<!-- Component Bagian Pertama -->
<div id="beranda" class="min-h-screen -mt-16 w-full flex overflow-hidden dark:bg-gray-950">
    <!-- Kolom kiri: Form Login -->
    <div class="flex-1 relative bg-white dark:bg-gray-900 flex items-center justify-center flex-col text-center">
        <div class="relative min-h-screen flex flex-col items-center justify-center text-center px-4">
            <!-- Judul dan Subjudul -->
            <div>
                <h1 class="text-6xl font-bold text-[#003350]">Hallo,<br>Selamat Datang</h1>
                <h3 class="text-lg mt-2">
                    Dengan TBM Mardeka Membaca jelajahi buku dan <br>
                    temukan rekomendasi bacaan
                </h3>
            </div>

            <!-- Gambar versi MOBILE -->
            <div class="mt-6 md:hidden">
                <img src="{{ asset('storage/icon/landing.svg') }}" alt="Buku" class="w-[280px] h-[280px] object-contain">
            </div>

            <!-- Kontak & Sosial Media versi DESKTOP -->
            <div class="absolute bottom-14 -left-10 text-left hidden md:block">
                <h6 class="text-sm text-[#64C0B7]">Hubungi kami:</h6>
                <h6 class="text-sm text-[#737373]">Alamat: Kretek, Bantul, Yogyakarta</h6>
                <h6 class="text-sm text-[#737373]">Email: tbm@gmail.com</h6>
            </div>
            <div class="absolute bottom-4 -left-10 hidden md:flex space-x-4 text-[#003350]">
                <a href="https://www.facebook.com" target="_blank">
                    <img src="{{ asset('storage/icon/logo_facebook.svg') }}" alt="Facebook" class="w-8 h-8">
                </a>
                <a href="https://www.twitter.com" target="_blank">
                    <img src="{{ asset('storage/icon/logo_x.svg') }}" alt="Twitter" class="w-8 h-8">
                </a>
                <a href="mailto:tbm@gmail.com">
                    <img src="{{ asset('storage/icon/logo_email.svg') }}" alt="Email" class="w-8 h-8">
                </a>
                <a href="https://www.instagram.com" target="_blank">
                    <img src="{{ asset('storage/icon/logo_ig.svg') }}" alt="Instagram" class="w-8 h-8">
                </a>
            </div>

            <!-- Kontak & Sosial Media versi MOBILE -->
            <div class="mt-10 text-left md:hidden w-full px-6">
                <h6 class="text-sm text-[#64C0B7]">Hubungi kami:</h6>
                <h6 class="text-sm text-[#737373]">Alamat: Kretek, Bantul, Yogyakarta</h6>
                <h6 class="text-sm text-[#737373]">Email: tbm@gmail.com</h6>

                <div class="mt-2 flex justify-start space-x-4 text-[#003350]">
                    <a href="https://www.facebook.com" target="_blank">
                        <img src="{{ asset('storage/icon/logo_facebook.svg') }}" alt="Facebook" class="w-8 h-8">
                    </a>
                    <a href="https://www.twitter.com" target="_blank">
                        <img src="{{ asset('storage/icon/logo_x.svg') }}" alt="Twitter" class="w-8 h-8">
                    </a>
                    <a href="mailto:tbm@gmail.com">
                        <img src="{{ asset('storage/icon/logo_email.svg') }}" alt="Email" class="w-8 h-8">
                    </a>
                    <a href="https://www.instagram.com" target="_blank">
                        <img src="{{ asset('storage/icon/logo_ig.svg') }}" alt="Instagram" class="w-8 h-8">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom kanan: Gambar Buku versi DESKTOP -->
    <div class="flex-1 bg-white hidden md:flex items-center justify-center">
        <img src="{{ asset('storage/icon/landing.svg') }}" alt="Buku" class="w-[380px] h-[380px] object-contain">
    </div>
</div>

<!-- Component Bagian Kedua (Tentang) -->
<div id="tentang" class="min-h-screen w-full pt-4 overflow-hidden dark:bg-gray-950 bg-white">
    <!-- Bagian TENTANG -->
    <div class="flex flex-col items-center px-4 mt-4 md:mt-10">
        <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">TENTANG</h1>
        <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
        <h6 class="text-center mt-2 md:mt-2 text-sm md:text-base leading-snug">
            “Sistem informasi perpustakaan melalui web adalah sebuah sistem yang digunakan untuk mengelola<br class="hidden md:block">
            melalui interface berbasis web”
        </h6>
    </div>

    <!-- Gambar dan teks -->
    <div class="mt-12 md:mt-16 flex flex-col md:flex-row items-center justify-center md:space-x-10 space-y-8 md:space-y-0 px-4 md:px-20">
        <!-- Gambar kiri -->
        <div class="flex-shrink-0 md:-ml-20">
            <img src="{{ asset('storage/icon/tentang.svg') }}" alt="Tentang" class="h-[220px] md:h-[330px] w-auto">
        </div>

        <!-- Teks kanan -->
        <div class="max-w-xl md:ml-6 mt-4 md:mt-0">
            <h2 class="text-lg md:text-2xl font-bold text-black mb-3 md:mb-4 text-center md:text-left">
                Manfaat TBM Mardeka Membaca
            </h2>
            <ul class="list-disc list-inside text-gray-700 leading-relaxed space-y-2 text-sm md:text-base">
                <li>Mengelola klasifikasi buku berdasarkan kategori</li>
                <li>Memudahkan dalam melakukan pencarian buku</li>
                <li>Mengakses koleksi buku secara fleksibel</li>
                <li>Meningkatkan minat literasi digital</li>
            </ul>
        </div>
    </div>
</div>

<!-- Component Bagian Ketigga (buku populer) -->
<div id="buku populer" class="min-h-screen w-full pt-4 overflow-hidden dark:bg-gray-950 bg-white">
    <div class="flex flex-col items-center px-4 mt-6 md:mt-10">
        <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">BUKU POPULER</h1>
        <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
    </div>
    <!-- Daftar Buku -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mt-20 px-6 md:px-20">
        <!-- Buku 1 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 1" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 1</h3>
            <p class="text-gray-600 text-sm">Penulis Buku 1</p>
        </div>
    
        <!-- Buku 2 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 2" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 2</h3>
            <p class="text-gray-600 text-sm">Penulis Buku 2</p>
        </div>
    
        <!-- Buku 3 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 3" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 3</h3>
            <p class="text-gray-600 text-sm">Penulis Buku 3</p>
        </div>
    
        <!-- Buku 4 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 4" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 4</h3>
            <p class="text-gray-600 text-sm">Penulis Buku 4</p>
        </div>
    
        <!-- Buku 5 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 5" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 5</h3>
            <p class="text-gray-600 text-sm">Penulis Buku 5</p>
        </div>
    </div>
</div>

<!-- Component Bagian Keempat (penggurus) -->
<div id="pengurus" class="min-h-screen w-full pt-4 overflow-hidden dark:bg-gray-950 bg-white relative">
    <div class="flex flex-col items-center px-4 mt-6 md:mt-10">
        <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">PENGGURUS</h1>
        <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
    </div>

    {{-- DAFTAR PENGGURUS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mt-20 px-6 md:px-20">
        <!-- Buku 1 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 1" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Asruri Faisal Alam</h3>
            <p class="text-gray-600 text-sm">Ketua</p>
        </div>

        <!-- Buku 2 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 2" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 2</h3>
            <p class="text-gray-600 text-sm">Volunteer</p>
        </div>

        <!-- Buku 3 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 3" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 3</h3>
            <p class="text-gray-600 text-sm">Volunteer</p>
        </div>

        <!-- Buku 4 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 4" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 4</h3>
            <p class="text-gray-600 text-sm">Volunteer</p>
        </div>

        <!-- Buku 5 -->
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('storage/img/buku1.svg') }}" alt="Buku 5" class="h-48 w-auto rounded shadow-md">
            <h3 class="mt-4 text-lg font-semibold text-black">Judul Buku 5</h3>
            <p class="text-gray-600 text-sm">Volunteer</p>
        </div>
    </div>
</div>




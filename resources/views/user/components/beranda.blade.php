@extends('user.layouts.default.main')
@section('content')
    <!-- Component Bagian Pertama -->
    <div id="beranda" class="min-h-screen -mt-16 w-full flex overflow-hidden bg-white">

        {{-- Swiper CSS --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <div id="beranda"
            class="min-h-screen flex items-center justify-center px-4 md:px-10 lg:px-16 py-24 bg-gradient-to-br from-[#E0F7FA] via-white to-[#E0F7FA]">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-7xl w-full items-start">
                {{-- KIRI: Buku Terbaru --}}
                <!-- Swiper CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

                <div class="space-y-6">
                    <h1 class="text-3xl md:text-4xl font-bold text-[#003350] text-center lg:text-left">
                        📚 Buku Terbaru
                    </h1>

                    <div class="swiper mySwiper w-full">
                        <div class="swiper-wrapper">
                            @foreach ($bukuBaru as $buku)
                                <div class="swiper-slide">
                                    <div
                                        class="bg-white rounded-2xl shadow-md p-6 flex flex-col md:flex-row gap-6 hover:shadow-xl transition">
                                        <img src="{{ rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $buku->img }}"
                                            alt="{{ $buku->judul }}"
                                            class="w-40 h-60 object-cover rounded-xl border shadow-sm" />
                                        <div class="flex-1 text-left">
                                            <h2 class="text-xl font-bold text-[#003350] mb-2">{{ $buku->judul }}</h2>
                                            <p class="text-sm text-gray-700 mb-1">✍️ <strong>{{ $buku->penulis }}</strong>
                                            </p>
                                            <p class="text-sm text-gray-700 mb-3">📘 ISBN:
                                                <strong>{{ $buku->isbn }}</strong>
                                            </p>
                                            <p class="text-sm text-gray-700 leading-relaxed line-clamp-4">
                                                {{ $buku->deskripsi }}
                                            </p>
                                            <span
                                                class="inline-block mt-4 text-xs px-4 py-1 bg-[#64C0B7] text-white rounded-full font-semibold">
                                                Buku Terbaru
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Bulatan pagination -->
                        <div class="swiper-pagination mt-4"></div>
                    </div>

                </div>

                <!-- Swiper JS -->
                <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const swiper = new Swiper(".mySwiper", {
                            loop: true,
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                        });
                    });
                </script>



                {{-- KANAN: Peminjaman Terbaru --}}
                <div class="space-y-6">
                    <h2 class="text-3xl md:text-4xl font-bold text-[#003350] text-center lg:text-left">
                        📖 Peminjaman Buku
                    </h2>

                    <div
                        class="bg-white rounded-2xl shadow-md p-6 max-h-[75vh] overflow-y-auto scrollbar-thin scrollbar-thumb-[#64C0B7] scrollbar-track-gray-200 mt-24  ">
                        <div class="space-y-6">
                            @forelse ($peminjamanAnggota as $p)
                                <div
                                    class="bg-[#f8f9fa] border border-[#64C0B7] rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="text-sm font-semibold text-[#003350]">
                                            👤 {{ $p->anggota->nama ?? 'Nama tidak ditemukan' }}
                                        </h3>
                                        <span
                                            class="text-xs text-gray-400 italic">{{ $p->created_at->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        📚 <strong>{{ $p->buku->judul ?? 'Judul tidak ditemukan' }}</strong>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        🗓️ {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }} →
                                        {{ \Carbon\Carbon::parse($p->tanggal_pengembalian)->format('d M Y') }}
                                    </p>
                                    <span
                                        class="inline-block mt-3 text-xs px-3 py-1 rounded-full font-medium
                                {{ $p->status_pengembalian == 'Dipinjam' ? 'bg-yellow-200 text-yellow-800' : 'bg-orange-200 text-orange-800' }}">
                                        📦 Status: {{ $p->status_pengembalian }}
                                    </span>
                                    @if ($p->denda > 0 && $p->status_pengembalian !== 'Belum Diambil')
                                        <p class="mt-2 text-xs text-red-600 font-semibold">
                                            💸 Denda: Rp{{ number_format($p->denda, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-center text-gray-500">Belum ada data peminjaman.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Swiper JS --}}
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            new Swiper(".mySwiper", {
                loop: true,
                centeredSlides: true,
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });
        </script>

        {{-- <!-- Kolom kanan: Gambar Buku versi DESKTOP -->
        <div class="flex-1 bg-white hidden md:flex items-center justify-center">
            @php
                $logoPage = \App\Models\Page::where('slug', 'cover_landing')->first();
            @endphp

            @if ($logoPage && $logoPage->img)
                <img class="w-[380px] h-[380px] object-contain"
                    src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                    alt="Cover">
            @endif
        </div> --}}
    </div>

    <!-- Component Bagian Kedua (Tentang) -->
    <div id="tentang" class="min-h-screen w-full pt-4 overflow-hidden bg-white ">
        <!-- Bagian TENTANG -->
        <div class="flex flex-col items-center px-4 mt-4 md:mt-10">
            <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">TENTANG</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
            <h6 class="text-center mt-2 md:mt-2 text-sm md:text-base leading-snug">
                “Sistem informasi perpustakaan melalui web adalah sebuah sistem yang digunakan untuk mengelola<br
                    class="hidden md:block">
                melalui interface berbasis web”
            </h6>
        </div>

        <!-- Gambar dan teks -->
        <div class="mt-12 md:mt-16 flex flex-col md:flex-row items-center justify-center gap-10 px-4 md:px-20">
            <!-- Gambar kiri -->
            <div class="flex-shrink-0">
                @php
                    $logoPage = \App\Models\Page::where('slug', 'visi')->first();
                    $page = \App\Models\Page::where('slug', 'visi')->first();
                @endphp

                @if ($logoPage && $logoPage->img)
                    <img class="h-[220px] md:h-[330px] w-auto"
                        src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                        alt="tentang">
                @endif
            </div>

            <!-- Teks kanan -->
            <div class="max-w-xl md:ml-24">
                <h2 class="text-lg md:text-2xl font-bold text-[#64C0B7] mb-3 md:mb-4 text-center md:text-left">
                    Visi TBM Mardeka Membaca
                </h2>
                <p class="text-gray-700 text-sm md:text-base leading-relaxed mb-6 text-center md:text-justify">
                    @if ($page && $page->content)
                        {!! $page->content !!}
                    @endif
                </p>

                <h2 class="text-lg md:text-2xl font-bold text-[#64C0B7] mb-3 md:mb-4 text-center md:text-left">
                    Misi TBM Mardeka Membaca
                </h2>
                <ul
                    class="list-disc list-outside pl-5 text-gray-700 leading-relaxed space-y-2 text-sm md:text-base text-justify">
                    @php
                        $page = \App\Models\Page::where('slug', 'misi')->first();
                    @endphp
                    @if ($page && $page->content)
                        {!! $page->content !!}
                    @endif
                </ul>

            </div>
        </div>
    </div>

    <!-- Component Bagian Ketigga (buku populer) -->
    <div id="buku-populer" class="min-h-screen w-full pt-4 overflow-hidden bg-white relative">
        <div class="flex flex-col items-center px-4 mt-6 md:mt-10">
            <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">BUKU POPULER</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
        </div>

        @php
            $jumlahBuku = count($topBooks);
        @endphp

        <div
            class="{{ $jumlahBuku < 5 ? 'flex justify-center flex-wrap' : 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5' }} gap-8 mt-20 px-6 md:px-20">
            @foreach ($topBooks as $item)
                <div class="flex flex-col items-center text-center">
                    <img src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $item->buku->img ?? 'default.jpg' }}"
                        alt="Sampul Buku {{ $item->buku->judul ?? '-' }}" class="h-48 w-auto rounded shadow-md" />
                    <h3 class="mt-4 text-lg font-semibold text-black">{{ $item->buku->judul ?? '-' }}</h3>
                    <p class="text-gray-600 text-sm">{{ $item->buku->penulis ?? '-' }}</p>

                    @php
                        $rating = round($item->avg_rating * 2) / 2;
                        $fullStars = floor($rating);
                        $halfStar = $rating - $fullStars == 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp

                    <div class="flex items-center mt-2">
                        @for ($i = 0; $i < $fullStars; $i++)
                            <span class="text-yellow-400 text-xl">&#9733;</span>
                        @endfor
                        @if ($halfStar)
                            <span class="text-yellow-400 text-xl">&#9733;</span>
                        @endif
                        @for ($i = 0; $i < $emptyStars; $i++)
                            <span class="text-gray-300 text-xl">&#9733;</span>
                        @endfor
                        <span class="ml-2 text-yellow-500 font-semibold">({{ number_format($item->avg_rating, 2) }})</span>
                    </div>

                    <!-- Tombol Lihat Ulasan -->
                    <button data-modal-target="modal-ulasan-{{ $item->buku->id }}"
                        data-modal-toggle="modal-ulasan-{{ $item->buku->id }}"
                        class="mt-3 text-sm text-white bg-[#64C0B7] hover:bg-[#50a49c] px-4 py-1 rounded">
                        Lihat Ulasan
                    </button>

                    <!-- Modal Ulasan -->
                    <div id="modal-ulasan-{{ $item->buku->id }}" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-100">

                                <!-- Header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Ulasan Buku: {{ $item->buku->judul }}
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                                        data-modal-hide="modal-ulasan-{{ $item->buku->id }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="p-6 space-y-4 max-h-96 overflow-y-auto bg-gray-50 rounded-b-lg">
                                    @php
                                        $userId = auth()->user()->id ?? null;

                                        $ulasan = \App\Models\Peminjaman::with('anggota')
                                            ->where('id_buku', $item->buku->id)
                                            ->whereNotNull('ulasan')
                                            ->orderByDesc('created_at')
                                            ->get(); // Semua ulasan
                                    @endphp

                                    @forelse ($ulasan as $review)
                                        @php
                                            $isOwn = $review->anggota && $review->anggota->id === $userId;
                                            $img =
                                                $review->anggota && $review->anggota->img
                                                    ? rtrim(env('SUPABASE_URL'), '/') .
                                                        '/storage/v1/object/public/' .
                                                        env('SUPABASE_BUCKET') .
                                                        '/' .
                                                        $review->anggota->img
                                                    : asset('storage/pengumuman/default.png');
                                        @endphp

                                        <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                            <div
                                                class="flex items-end gap-3 {{ $isOwn ? 'flex-row-reverse' : 'flex-row' }}">
                                                <!-- Foto profil -->
                                                <img src="{{ $img }}"
                                                    alt="Foto {{ $review->anggota->nama ?? 'Anonim' }}"
                                                    class="w-9 h-9 rounded-full object-cover border shadow">

                                                <!-- Bubble -->
                                                <div
                                                    class="{{ $isOwn ? 'bg-blue-100' : 'bg-white' }} px-4 py-2 rounded-2xl shadow max-w-xs">
                                                    <p class="text-xs font-bold text-gray-700 mb-1">
                                                        {{ $isOwn ? 'Saya' : $review->anggota->nama ?? 'Anonim' }}
                                                    </p>
                                                    <p class="text-sm text-gray-800 leading-relaxed italic">
                                                        "{{ $review->ulasan }}"</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-center">Belum ada ulasan untuk buku ini.</p>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <!-- Component Bagian Keempat (penggurus) -->
    <div id="pengurus" class="min-h-screen w-full pt-4 overflow-hidden bg-white relative">
        <div class="flex flex-col items-center px-4 mt-6 md:mt-10">
            <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">PENGGURUS</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis"
                class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
        </div>

        @php
            $jumlah = count($volunteers);
        @endphp

        {{-- DAFTAR PENGGURUS --}}
        @if ($jumlah < 5)
            <div class="flex justify-center flex-wrap gap-8 mt-20 px-6 md:px-20">
                @foreach ($volunteers as $volunteer)
                    <div class="flex flex-col items-center text-center">
                        <img src="{{ env('SUPABASE_URL') . '/storage/v1/object/public/my-files/' . $volunteer->img }}"
                            alt="{{ $volunteer->nama }}" class="h-48 w-auto rounded shadow-md">
                        <h3 class="mt-4 text-lg font-semibold text-black">{{ $volunteer->nama }}</h3>
                        <p class="text-gray-600 text-sm">{{ $volunteer->jabatan }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mt-20 px-6 md:px-20">
                @foreach ($volunteers as $volunteer)
                    <div class="flex flex-col items-center text-center">
                        <img src="{{ asset('storage/' . $volunteer->img) }}" alt="{{ $volunteer->nama }}"
                            class="h-48 w-auto rounded shadow-md">
                        <h3 class="mt-4 text-lg font-semibold text-black">{{ $volunteer->nama }}</h3>
                        <p class="text-gray-600 text-sm">{{ $volunteer->jabatan }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

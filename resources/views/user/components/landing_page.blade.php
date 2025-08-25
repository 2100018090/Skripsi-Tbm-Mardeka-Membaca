@extends('user.layouts.default.main')
@section('content')
    <!-- Component Bagian Pertama -->
    <div id="beranda" class="min-h-screen -mt-22 w-full flex overflow-hidden bg-white">
        <!-- Kolom kiri: Form Login -->
        <div class="flex-1 relative bg-white  flex items-center justify-center flex-col text-center">
            <div class="relative min-h-screen flex flex-col items-center justify-center text-center px-4">
                <!-- Judul dan Subjudul -->
                <div>
                    @php
                        $page = \App\Models\Page::where('slug', 'landing_page')->first();
                    @endphp

                    @if ($page && $page->content)
                        <h1 class="text-6xl font-bold text-[#003350]">{!! $page->content !!}</h1>
                    @endif

                    @php
                        $page = \App\Models\Page::where('slug', 'landing_page2')->first();
                    @endphp
                    @if ($page && $page->content)
                        <h3 class="text-lg mt-2">
                            {!! $page->content !!}
                        </h3>
                    @endif
                </div>

                <!-- Gambar versi MOBILE -->
                <div class="mt-6 md:hidden">
                    @php
                        $logoPage = \App\Models\Page::where('slug', 'cover_landing')->first();
                    @endphp

                    @if ($logoPage && $logoPage->img)
                        <img class="w-[280px] h-[280px] object-contain"
                            src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                            alt="Cover">
                    @endif
                </div>

                <!-- Kontak & Sosial Media versi DESKTOP -->
                <div class="absolute bottom-14 left-4 text-left hidden md:block">
                    <h6 class="text-sm text-[#64C0B7]">Hubungi kami:</h6>
                    @php
                        $page = \App\Models\Page::where('slug', 'alamat')->first();
                    @endphp

                    @if ($page && $page->content)
                        <h6 class="text-sm text-[#737373]">Alamat: {!! $page->content !!}</h6>
                    @endif

                    @php
                        $page = \App\Models\Page::where('slug', 'email')->first();
                    @endphp

                    @if ($page && $page->content)
                        <h6 class="text-sm text-[#737373]">Email: {!! $page->content !!}</h6>
                    @endif
                </div>
                <div class="absolute bottom-4 left-4 hidden md:flex space-x-4 text-[#003350]">
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
                    @php
                        $page = \App\Models\Page::where('slug', 'alamat')->first();
                    @endphp

                    @if ($page && $page->content)
                        <h6 class="text-sm text-[#737373]">Alamat: {!! $page->content !!}</h6>
                    @endif

                    @php
                        $page = \App\Models\Page::where('slug', 'email')->first();
                    @endphp

                    @if ($page && $page->content)
                        <h6 class="text-sm text-[#737373]">Email: {!! $page->content !!}</h6>
                    @endif

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
            @php
                $logoPage = \App\Models\Page::where('slug', 'cover_landing')->first();
            @endphp

            @if ($logoPage && $logoPage->img)
                <img class="w-[380px] h-[380px] object-contain"
                    src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $logoPage->img }}"
                    alt="Cover">
            @endif
        </div>
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

    <!-- Component Bagian Ketiga (Buku Populer) -->
    <div id="buku-populer" class="min-h-screen w-full pt-4 overflow-hidden bg-white relative">
        <div class="flex flex-col items-center px-4 mt-6 md:mt-10">
            <h1 class="text-[28px] md:text-[39px] font-bold text-center text-[#003350]">BUKU POPULER</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
        </div>

        @php
            $jumlahBuku = count($topBooks);
        @endphp

        <div class="{{ $jumlahBuku < 5 ? 'flex justify-center flex-wrap' : 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5' }} gap-8 mt-20 px-6 md:px-20">
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
                    <button data-modal-target="modal-ulasan-{{ $item->buku->id }}" data-modal-toggle="modal-ulasan-{{ $item->buku->id }}"
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
                                <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                                    @php
                                        $ulasan = \App\Models\Peminjaman::with('anggota')
                                            ->where('id_buku', $item->buku->id)
                                            ->whereNotNull('ulasan')
                                            ->orderByDesc('created_at')
                                            ->take(10)
                                            ->get();
                                    @endphp

                                    @forelse ($ulasan as $review)
                                        <div class="border-b pb-3">
                                            <p class="text-sm text-gray-700"><strong>{{ $review->anggota->nama ?? 'Anonim' }}:</strong></p>
                                            <p class="text-gray-800 italic">"{{ $review->ulasan }}"</p>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">Belum ada ulasan untuk buku ini.</p>
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

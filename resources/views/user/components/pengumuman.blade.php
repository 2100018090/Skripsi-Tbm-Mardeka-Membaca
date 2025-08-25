@extends('user.layouts.default.beranda')

@section('content')
    <div class="bg-gray-50 py-8 px-4">

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-[#003350]">PENGUMUMAN</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] mx-auto mt-3">
            <p class="text-sm md:text-base mt-2">“Membaca adalah napas hidup dan jembatan emas ke masa depan"</p>
        </div>

        <!-- List View -->
        <div id="list-view" class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($pengumumans as $index => $item)
                <div class="bg-white rounded-xl border border-gray-200 shadow hover:shadow-lg transition p-4 flex flex-col">
                    <img src="{{ env('SUPABASE_URL') . '/storage/v1/object/public/my-files/' . $item->img }}"
                        alt="Gambar" class="w-full h-36 object-cover rounded-md mb-4">

                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $item->judul }}</h2>
                        <span class="text-sm text-gray-500 whitespace-nowrap ml-2">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                        </span>
                    </div>

                    <p class="text-gray-700 text-sm line-clamp-3 flex-grow">
                        {{ Str::limit(strip_tags($item->isi), 100, '...') }}
                    </p>

                    <button type="button"
                        onclick="showDetail({{ $index }})"
                        class="mt-4 px-4 py-2 bg-[#64C0B7] hover:bg-[#57b1a9] text-white rounded-md transition">
                        Lihat Detail
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Pagination (dipisah agar bisa diatur tampil/sembunyi) -->
        <div id="pagination" class="mt-6 text-center">
            {{ $pengumumans->links() }}
        </div>

        <!-- Detail View -->
        <div id="detail-view" class="max-w-3xl mx-auto hidden mt-12">
            <button onclick="showList()" class="mb-6 px-5 py-2 bg-[#64C0B7] hover:bg-[#57b1a9] text-white rounded-md">
                ← Kembali ke Daftar
            </button>

            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 text-gray-800">
                <img id="detail-image" class="w-full h-56 md:h-72 object-cover rounded-md mb-6" alt="Gambar Pengumuman">

                <h2 id="detail-title" class="text-2xl font-bold text-[#005A8D] mb-1"></h2>
                <p id="detail-date" class="text-sm text-gray-600 mb-3"></p>

                <div id="detail-content" class="text-[15px] leading-[1.5] space-y-1 font-sans text-gray-700"></div>

                <div class="mt-6 pt-3 text-xs text-gray-500 border-t text-center">
                    TBM Mardeka Membaca<br>
                    Halaman ini ditampilkan otomatis — mohon tidak dibalas.
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT -->
    <script>
        const SUPABASE_URL = '{{ rtrim(env('SUPABASE_URL'), '/') }}';
        const announcements = @json($pengumumans->items());

        function showDetail(index) {
            const ann = announcements[index];
            if (!ann) {
                alert("Data pengumuman tidak ditemukan.");
                return;
            }

            document.getElementById('detail-title').textContent = ann.judul;
            document.getElementById('detail-date').textContent = new Date(ann.tanggal).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            const formattedIsi = ann.isi.replace(/\n/g, "<br>");
            document.getElementById('detail-content').innerHTML = formattedIsi;

            const imgUrl = ann.img
                ? `${SUPABASE_URL}/storage/v1/object/public/my-files/${ann.img}`
                : '/storage/pengumuman/default.png';
            document.getElementById('detail-image').src = imgUrl;

            // Sembunyikan list dan pagination, tampilkan detail
            document.getElementById('list-view').classList.add('hidden');
            document.getElementById('pagination').classList.add('hidden');
            document.getElementById('detail-view').classList.remove('hidden');

            // Catat log
            fetch("{{ route('log.pengumuman') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": '{{ csrf_token() }}'
                },
                body: JSON.stringify({ judul: ann.judul })
            });
        }

        function showList() {
            document.getElementById('detail-view').classList.add('hidden');
            document.getElementById('list-view').classList.remove('hidden');
            document.getElementById('pagination').classList.remove('hidden');
        }

        // Real-time listener
        window.Echo.channel('pengumuman-channel')
            .listen('.pengumuman-baru', (e) => {
                alert("📢 Pengumuman baru: " + e.judul);
                location.reload(); // reload untuk ambil data baru
            });
    </script>
@endsection

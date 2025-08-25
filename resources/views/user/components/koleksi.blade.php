@extends('user.layouts.default.beranda')

@section('content')
    <div class="flex flex-col">
        <!-- Judul dan Deskripsi -->
        <div class="flex flex-col items-center px-4 mt-6 md:mt-10 mb-10 md:mb-10">
            <h6 class="text-lg md:text-2xl font-medium text-center text-[#003350]">MardekaLiterasi</h6>
            <h1 class="text-2xl md:text-3xl font-bold text-center text-[#003350]">Koleksi</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[4px] md:h-[8px] w-auto mt-2 md:mt-4">
            <h6 class="text-center mt-2 md:mt-2 text-xs md:text-base leading-snug italic text-gray-600">
                “Membaca adalah napas hidup dan jembatan emas ke masa depan"
            </h6>
        </div>

        <!-- Form Pencarian Buku dan Kategori -->
        <div class="px-4 md:px-10 mb-10">
            <div class="bg-white shadow-lg rounded-2xl p-5 md:p-6 w-full max-w-2xl mx-auto">

                <!-- Total Buku -->
                <div class="text-sm md:text-base font-medium text-gray-700 mb-2 text-center">
                    Buku Fisik: <span class="font-bold text-gray-900">{{ $totalFisik }}</span> &nbsp; | &nbsp;
                    Buku Digital: <span class="font-bold text-gray-900">{{ $totalDigital }}</span>
                </div>
                <h2 class="text-base md:text-lg font-semibold text-[#003350] mb-4">Cari Buku atau Kategori</h2>
                <form action="" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6" id="formCari">
                    <div>
                        <label for="judul" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Judul
                            Buku</label>
                        <input type="text" name="judul" id="judul" placeholder="Masukkan judul buku"
                            value="{{ request('judul') }}"
                            class="w-full border border-gray-300 rounded-xl p-2.5 md:p-3 focus:outline-none focus:ring-4 focus:ring-[#64C0B7] transition duration-300 text-sm md:text-base" />
                    </div>

                    <div>
                        <label for="kategori"
                            class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" id="kategori"
                            class="w-full border border-gray-300 rounded-xl p-2.5 md:p-3 focus:outline-none focus:ring-4 focus:ring-[#64C0B7] transition duration-300 text-sm md:text-base">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoriList as $namaKategori)
                                <option value="{{ $namaKategori }}"
                                    {{ request('kategori') == $namaKategori ? 'selected' : '' }}>
                                    {{ $namaKategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#34d399] to-[#059669] text-white font-semibold py-3 rounded-xl hover:from-[#059669] hover:to-[#065f46] shadow-lg transition duration-300 flex items-center justify-center gap-2 md:gap-3 text-sm md:text-lg">
                            <img src="{{ asset('storage/icon/pencarian.svg') }}" alt="Icon"
                                class="w-5 h-5 md:w-6 md:h-6 filter invert" />
                            <span>Cari</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Buku -->
        <div class="px-4 md:px-10 mb-10">
            <div class="bg-white shadow-lg rounded-2xl p-5 md:p-6 w-full max-w-4xl mx-auto">
                <div class="flex flex-col gap-6 md:gap-8">
                    @if ($bookGroups->isEmpty())
                        <p class="text-center text-gray-500 font-semibold py-10 text-sm md:text-base">
                            Silakan gunakan form pencarian di atas untuk mencari buku.
                        </p>
                    @else
                        @foreach ($bookGroups as $group)
                            @php
                                $buku = $group->first();
                                $fisik = $group->firstWhere('tipe', 'fisik');
                                $digital = $group->firstWhere('tipe', 'digital');
                            @endphp

                            <div
                                class="flex flex-col md:flex-row bg-gradient-to-r from-[#e0f2fe] to-[#bae6fd] rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition duration-300">
                                <img src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/{{ $buku->img }}"
                                    alt="Sampul Buku"
                                    class="w-full md:w-40 h-48 md:h-56 object-cover object-center rounded-t-2xl md:rounded-t-none md:rounded-l-2xl mx-auto md:mx-0 md:my-auto" />

                                <div class="p-4 md:p-6 flex flex-col justify-between w-full">
                                    <div>
                                        <h3
                                            class="text-lg md:text-2xl font-extrabold text-[#065f46] mb-3 md:mb-4 drop-shadow-md">
                                            {{ $buku->judul }}
                                        </h3>

                                        <div
                                            class="flex flex-col md:flex-row justify-between text-left mb-3 md:mb-4 bg-white bg-opacity-80 p-3 rounded-xl shadow-inner">
                                            <div class="flex-1 mb-3 md:mb-0">
                                                <p class="text-xs md:text-sm font-semibold text-[#065f46] tracking-wide">
                                                    ISBN</p>
                                                <p class="text-sm md:text-md font-medium text-[#064e3b]">
                                                    {{ $buku->isbn }}
                                                </p>
                                            </div>

                                            <div class="flex-1 mb-3 md:mb-0">
                                                <p class="text-xs md:text-sm font-semibold text-[#065f46] tracking-wide">
                                                    PENULIS</p>
                                                <p class="text-sm md:text-md font-medium text-[#064e3b]">
                                                    {{ $buku->penulis }}</p>
                                            </div>

                                            <div class="flex-1">
                                                <p class="text-xs md:text-sm font-semibold text-[#065f46] tracking-wide">
                                                    PENERBIT</p>
                                                <p class="text-sm md:text-md font-medium text-[#064e3b]">
                                                    {{ $buku->penerbit }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-1 md:mt-2">
                                            <p class="text-sm md:text-base font-semibold text-[#047857] mb-1">DESKRIPSI</p>
                                            <p class="text-sm md:text-base text-gray-700 leading-relaxed">
                                                {{ $buku->deskripsi }}</p>
                                        </div>

                                        <div
                                            class="mt-5 md:mt-6 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6 px-1">
                                            @if ($fisik)
                                                <div class="text-base md:text-lg font-semibold text-[#065f46] drop-shadow">
                                                    Stok:
                                                    <span
                                                        class="text-[#10b981]">{{ $fisik->fisik ? $fisik->fisik->stok : '0' }}</span>
                                                </div>

                                                @php
                                                    $stok = $fisik->fisik ? $fisik->fisik->stok : 0;
                                                @endphp

                                                <button data-id="{{ $fisik->id }}"
                                                    data-url="{{ route('Buku', $fisik->id) }}" type="button"
                                                    class="btnPinjamBuku flex items-center gap-2 md:gap-3
                                            bg-gradient-to-r from-[#10b981] to-[#059669]
                                            hover:from-[#059669] hover:to-[#047857]
                                            text-white px-4 md:px-5 py-2.5 md:py-3 rounded-3xl
                                            focus:outline-none shadow-lg transition duration-300 transform
                                            hover:-translate-y-1 text-sm md:text-lg
                                            {{ $stok == 0 ? 'cursor-not-allowed opacity-50 pointer-events-none' : '' }}"
                                                    {{ $stok == 0 ? 'disabled' : '' }}>
                                                    <img src="{{ asset('storage/icon/pinjam_buku3.png') }}" alt="Pinjam"
                                                        class="w-5 h-5 md:w-6 md:h-6 filter drop-shadow" />
                                                    <span>Pinjam Buku</span>
                                                </button>
                                            @endif

                                            @if ($digital)
                                                <a href="{{ route('buku.baca', $buku->digital->id) }}"
                                                    class="flex items-center gap-2 md:gap-3 bg-gradient-to-r from-[#3b82f6] to-[#2563eb] hover:from-[#2563eb] hover:to-[#1d4ed8] text-white px-4 md:px-5 py-2.5 md:py-3 rounded-3xl shadow-lg transition duration-300 transform hover:-translate-y-1 text-sm md:text-lg">
                                                    <img src="{{ asset('storage/icon/baca_buku.png') }}" alt="Baca"
                                                        class="w-5 h-5 md:w-6 md:h-6 filter drop-shadow" />
                                                    <span>Baca Buku</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- PAGINATION -->
                        <div class="mt-10 flex justify-center">
                            {{ $bookGroups->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Form Pinjam Buku -->
    <div id="modalPinjam"
        class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 backdrop-blur-sm transition-opacity duration-500">
        <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8 w-full max-w-xl mx-4 relative">
            <button id="closeModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-900 transition duration-300 text-2xl font-semibold">
                &times;
            </button>

            <form action="{{ route('anggota.pinjamLangsung') }}" method="POST" class="flex flex-col gap-5 md:gap-6">
                @csrf
                <input type="hidden" name="id_buku" id="id_buku" value="" />
                @if (Auth::user()->anggota)
                    <input type="hidden" name="id_anggota" value="{{ Auth::user()->anggota->id }}" />
                @else
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Maaf',
                                text: 'Silakan lengkapi profil anggota terlebih dahulu.',
                                confirmButtonText: 'Isi Sekarang'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/anggota/profileAnggota";
                                }
                            });
                        });
                    </script>
                @endif

                <!-- Flatpickr CSS & JS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                <div class="mt-4">
                    <label for="tanggal_pinjam" class="block font-semibold text-[#003350] mb-1">
                        Tanggal Pinjam
                    </label>
                    <input type="text" id="tanggal_pinjam" name="tanggal_pinjam"
                        class="w-full border border-gray-300 rounded-xl p-2 bg-white text-gray-700 focus:outline-none text-sm md:text-base"
                        placeholder="Pilih tanggal pinjam" />
                </div>

                <div class="mt-4">
                    <label for="tanggal_pengembalian" class="block font-semibold text-[#003350] mb-1">
                        Tanggal Pengembalian
                    </label>
                    <input type="text" id="tanggal_pengembalian" name="tanggal_pengembalian"
                        class="w-full border border-gray-300 rounded-xl p-2 bg-gray-100 cursor-not-allowed text-gray-500 focus:outline-none text-sm md:text-base"
                        readonly />
                </div>

                <script>
                    // Fungsi format tanggal ke Y-m-d
                    function formatDate(date) {
                        const y = date.getFullYear();
                        const m = String(date.getMonth() + 1).padStart(2, '0');
                        const d = String(date.getDate()).padStart(2, '0');
                        return `${y}-${m}-${d}`;
                    }

                    // Inisialisasi flatpickr untuk tanggal pinjam
                    flatpickr("#tanggal_pinjam", {
                        dateFormat: "Y-m-d",
                        minDate: new Date().fp_incr(1), // mulai dari besok
                        disable: [
                            function(date) {
                                return (date.getDay() === 0); // disable hari Minggu (0)
                            }
                        ],
                        onChange: function(selectedDates, dateStr, instance) {
                            if (selectedDates.length > 0) {
                                const tglPinjam = selectedDates[0];
                                const tglPengembalian = new Date(tglPinjam);
                                tglPengembalian.setDate(tglPengembalian.getDate() + 5);
                                document.getElementById('tanggal_pengembalian').value = formatDate(tglPengembalian);
                            } else {
                                document.getElementById('tanggal_pengembalian').value = '';
                            }
                        }
                    });
                </script>

                <button type="submit"
                    class="bg-gradient-to-r from-[#10b981] to-[#059669] text-white font-semibold py-3 rounded-xl hover:from-[#059669] hover:to-[#047857] shadow-lg transition duration-300">
                    Pinjam
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalPinjam');
            const closeModalBtn = document.getElementById('closeModal');
            const idBukuInput = document.getElementById('id_buku');
            const pinjamButtons = document.querySelectorAll('.btnPinjamBuku');

            pinjamButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const idBuku = button.getAttribute('data-id');
                    console.log('ID Buku dipilih:', idBuku); // Debug cek ID buku
                    idBukuInput.value = idBuku;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    </script>

    {{-- @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            showConfirmButton: true,
        });
    </script>
@endif --}}

    @if (session('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: {!! json_encode(session('error')) !!},
                showConfirmButton: true,
                timer: 3000
            });
        </script>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection

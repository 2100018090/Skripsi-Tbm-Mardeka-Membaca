@extends('admin.layouts.default.beranda')
@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800"
            style="max-width: 500px;">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">
                        Laporan Peminjaman Buku
                    </span>
                </div>
            </div>

            <!-- Form input Bulan Mulai dan Bulan Sampai -->
            <form action="" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai"
                            class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Bulan Mulai</label>
                        <input type="month" id="tanggal_mulai" name="tanggal_mulai"
                            class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white"
                            value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div>
                        <label for="tanggal_sampai"
                            class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Bulan Sampai
                        </label>
                        <input type="month" id="tanggal_sampai" name="tanggal_sampai"
                            class="w-full border border-gray-300 rounded px-3 py-2 dark:bg-gray-700 dark:text-white"
                            value="{{ request('tanggal_sampai') }}">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <!-- Tombol Submit & Export Excel berdampingan -->
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded shadow">
                        Tampilkan Laporan
                    </button>

                    @if (request()->filled('tanggal_mulai') && isset($peminjamans) && $peminjamans->count())
                        <a href="{{ route('admin.exportPeminjaman', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_sampai' => request('tanggal_sampai')]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow">
                            Export Excel
                        </a>
                    @endif
                </div>
            </form>

            @if (request()->filled('tanggal_mulai'))
                @if (isset($peminjamans) && $peminjamans->count())
                    <!-- Tabel hasil pencarian -->
                    <div class="mt-8">
                        <div class="mt-6 text-sm text-gray-700 dark:text-gray-300">
                            <p><strong>Filter:</strong></p>
                            <ul class="list-disc list-inside">
                                <li>Dari Bulan:
                                    <span class="font-medium">
                                        {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->translatedFormat('F Y') }}
                                    </span>
                                </li>
                                @if (request()->filled('tanggal_sampai'))
                                    <li>Sampai Bulan:
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse(request('tanggal_sampai'))->translatedFormat('F Y') }}
                                        </span>
                                    </li>
                                @endif
                            </ul>

                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 mt-4">Hasil Laporan</h2>
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                        <tr>
                                            <th class="px-4 py-2 border">No</th>
                                            <th class="px-4 py-2 border">Nama Peminjam</th>
                                            <th class="px-4 py-2 border">Judul Buku</th>
                                            <th class="px-4 py-2 border">ISBN</th>
                                            <th class="px-4 py-2 border">Kode Buku</th>
                                            <th class="px-4 py-2 border">Pemberi Buku</th>
                                            <th class="px-4 py-2 border">Penerima Buku</th>
                                            <th class="px-4 py-2 border">Tanggal Pinjam</th>
                                            <th class="px-4 py-2 border">Tanggal Pengembalian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peminjamans as $index => $peminjaman)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-4 py-2 border">{{ $peminjamans->firstItem() + $index }}</td>

                                                <td class="px-4 py-2 border">{{ $peminjaman->anggota->nama ?? '-' }}</td>
                                                <td class="px-4 py-2 border">{{ $peminjaman->buku->judul ?? '-' }}</td>
                                                <td class="px-4 py-2 border">{{ $peminjaman->buku->isbn ?? '-' }}</td>
                                                <td class="px-4 py-2 border">{{ $peminjaman->detailBuku->kode ?? '-' }}</td>
                                                <td class="px-4 py-2 border">{{ $peminjaman->voluntterPinjam->nama ?? '-' }}</td>
                                                <td class="px-4 py-2 border">{{ $peminjaman->voluntterKembali->nama ?? '-' }}</td>
                                                <td class="px-4 py-2 border">
                                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-4 py-2 border">
                                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->translatedFormat('d F Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- PAGINATION -->
                                <div class="mt-4">
                                    {{ $peminjamans->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="mt-8 text-sm text-red-600 dark:text-red-400">Tidak ada data peminjaman pada rentang bulan
                        tersebut.</p>
                @endif
            @endif
        </div>

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">
                        Informasi Buku
                    </span>
                </div>
            </div>

            {{-- Form Pencarian Buku --}}
            <form method="GET" action="" class="space-y-4">
                {{-- Pilih Jenis Buku --}}
                <div>
                    <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                        Buku</label>
                    <select id="tipe" name="tipe"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">-- Pilih Jenis Buku --</option>
                        <option value="fisik" {{ request('tipe') == 'fisik' ? 'selected' : '' }}>Buku Fisik</option>
                        <option value="digital" {{ request('tipe') == 'digital' ? 'selected' : '' }}>Buku Digital</option>
                    </select>
                </div>

                {{-- Input Nama Buku --}}
                <div>
                    <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Buku</label>
                    <input type="text" id="judul" name="judul" value="{{ request('judul') }}"
                        placeholder="Masukkan nama buku"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
                </div>

                {{-- Input Isbn --}}
                <div>
                    <label for="isbn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="{{ request('isbn') }}"
                        placeholder="Masukkan ISBN"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
                </div>

                {{-- Tombol Cari --}}
                <div>
                    <button type="submit"
                        class="text-white px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                        Cari Buku
                    </button>
                </div>
            </form>

            {{-- Tabel Hasil Pencarian dan Pesan Jika Tidak Ada Data --}}
            @if ((request()->has('tipe') && request('tipe') != '') || (request()->has('judul') && request('judul') != ''))
                @if (isset($bukus) && $bukus->count())
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-2">Judul</th>
                                    <th class="px-4 py-2">Penulis</th>
                                    <th class="px-4 py-2">Penerbit</th>
                                    <th class="px-4 py-2">Tahun</th>
                                    <th class="px-4 py-2">Tipe</th>
                                    <th class="px-4 py-2">Stok/jumlahHalaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bukus as $b)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-4 py-2">{{ $b['judul'] }}</td>
                                        <td class="px-4 py-2">{{ $b['penulis'] }}</td>
                                        <td class="px-4 py-2">{{ $b['penerbit'] }}</td>
                                        <td class="px-4 py-2">{{ $b['tahun_terbit'] }}</td>
                                        <td class="px-4 py-2">{{ $b['tipe'] }}</td>
                                        <td class="px-4 py-2">
                                            @if ($b['tipe'] == 'fisik')
                                                {{ $b->fisik->stok ?? 0 }}
                                            @elseif($b['tipe'] == 'digital')
                                                {{ $b->digital->jumlahHalaman ?? 0 }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $bukus->links() }}
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-gray-600 dark:text-gray-300">Tidak ada data yang ditemukan.</p>
                @endif
            @endif

        </div>

    </div>

    <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800"
            style="max-width: 1000px;">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800"
                style="max-width: 1000px; margin: auto;">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Pembayaran Denda</h2>

                @isset($dataDenda)
                    @if ($dataDenda->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 border">No</th>
                                        <th class="px-4 py-2 border">Nama Peminjam</th>
                                        <th class="px-4 py-2 border">Judul Buku</th>
                                        <th class="px-4 py-2 border">ISBN</th>
                                        <th class="px-4 py-2 border">Tanggal Pinjam</th>
                                        <th class="px-4 py-2 border">Tanggal Pengembalian</th>
                                        <th class="px-4 py-2 border">Jumlah Denda</th>
                                        <th class="px-4 py-2 border">Tanggal Pembayaran</th>
                                        <th class="px-4 py-2 border">Metode Pembayaran</th>
                                        <th class="px-4 py-2 border">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataDenda as $index => $denda)
                                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                            <td class="px-4 py-2 border">{{ $dataDenda->firstItem() + $index }}</td>
                                            <td class="px-4 py-2 border">{{ $denda->peminjaman->anggota->nama ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ $denda->peminjaman->buku->judul ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ $denda->peminjaman->buku->isbn ?? '-' }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ optional($denda->peminjaman)->tanggal_pinjam ? \Carbon\Carbon::parse($denda->peminjaman->tanggal_pinjam)->translatedFormat('d F Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ optional($denda->peminjaman)->tanggal_pengembalian ? \Carbon\Carbon::parse($denda->peminjaman->tanggal_pengembalian)->translatedFormat('d F Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                Rp{{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ $denda->tanggal_pembayaran ? \Carbon\Carbon::parse($denda->tanggal_pembayaran)->translatedFormat('d F Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border">{{ $denda->metode_pembayaran ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ ucfirst($denda->status_pembayaran) ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $dataDenda->links() }}
                            </div>
                        </div>
                    @else
                        <p class="mt-4 text-red-500 dark:text-red-400">Belum ada data pembayaran denda.</p>
                    @endif
                @endisset
            </div>
        </div>

        <div class="w-full mt-4">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white">Statistik Data</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Data Buku Fisik
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalFisik }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Data Buku Digital
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalDigital }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Data Jumlah Anggota
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalAnggota }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Data Jumlah Voluntter
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalVoluntter }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Data Jumlah Peminjaman
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $totalPeminjaman }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Pendapatan Denda
                        </h3>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            Rp{{ number_format($totalPendapatanDenda, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

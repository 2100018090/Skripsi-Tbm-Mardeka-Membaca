@if ($peminjamans->count())
    @foreach ($peminjamans as $peminjaman)
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full"
                    src="{{ $peminjaman->anggota->img ? asset('storage/' . $peminjaman->anggota->img) : asset('storage/peminjaman/default.png') }}"
                    alt="user photo">
                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $peminjaman->anggota->nama }}
                    </div>
                </div>
            </td>
            <td class="p-4 text-gray-500 dark:text-gray-400">
                {{ $peminjaman->buku->judul }}
            </td>
            <td class="p-4 text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d-m-Y') }}
            </td>
            <td class="p-4 text-gray-500 dark:text-gray-400">
                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d-m-Y') }}
            </td>
            <td class="p-4 text-gray-500 dark:text-gray-400">
                {{ $peminjaman->status_pengembalian }}
            </td>
            <td class="p-4 text-gray-500 dark:text-gray-400">
                {{ $peminjaman->denda == 0 ? 'Tidak ada denda' : 'Rp ' . number_format($peminjaman->denda, 0, ',', '.') }}
            </td>

            <td class="p-4 space-x-2 whitespace-nowrap" x-data="{ openQris: false, openCash: false }">
                <!-- Tombol Pembayaran Cash -->
                <button @click="openCash = true"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4h12v12H4z" />
                    </svg>
                    Bayar Cash
                </button>

                <!-- Tombol Pembayaran QRIS jika ada bukti -->
                @if ($peminjaman->pembayaranDenda?->bukti_pembayaran)
                    <button @click="openQris = true"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h16v16H4z" />
                        </svg>
                        Lihat Bukti QRIS
                    </button>
                @endif

                <!-- Modal Cash -->
                <div x-show="openCash" x-cloak x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="openCash = false" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md"
                        @keydown.escape.window="openCash = false">
                        <h2 class="text-lg font-semibold mb-4">Pembayaran Cash</h2>
                        <form action="{{ route('admin.bayarCash', $peminjaman->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="jumlah_cash" class="block text-sm font-medium text-gray-700">Jumlah
                                    Pembayaran</label>
                                <input type="number" name="jumlah" id="jumlah_cash" value="{{ $peminjaman->denda }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="openCash = false"
                                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Bayar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal QRIS -->
                <div x-show="openQris" x-cloak x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="openQris = false" @keydown.escape.window="openQris = false"
                        class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">

                        <h2 class="text-lg font-semibold mb-4">Detail Pembayaran QRIS</h2>

                        {{-- Jumlah Denda --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Jumlah Denda</label>
                            <p class="text-gray-800 font-semibold">
                                Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Bukti Pembayaran --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                            <img src="{{ env('SUPABASE_URL') }}/storage/v1/object/public/my-files/{{ $peminjaman->pembayaranDenda?->bukti_pembayaran }}"
                                alt="Bukti QRIS" class="w-full max-h-96 object-contain mt-2 border rounded-md">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex flex-wrap justify-between gap-2 mt-6">
                            {{-- Tombol Tutup --}}
                            <button type="button" @click="openQris = false"
                                class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                                Tutup
                            </button>

                            {{-- Konfirmasi Pembayaran --}}
                            <form action="{{ route('admin.konfirmasiDenda', $peminjaman->id) }}" method="POST"
                                class="flex-1">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="konfirmasi"
                                    class="w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                    Konfirmasi Pembayaran
                                </button>
                            </form>

                            {{-- Transaksi Gagal --}}
                            <form action="{{ route('admin.transaksiGagal', $peminjaman->id) }}" method="POST"
                                class="flex-1"
                                onsubmit="return confirm('Yakin ingin menandai transaksi ini sebagai gagal?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="gagal"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                    Transaksi Gagal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
    </tr>
@endif

<!-- Tambahkan ini di bawah template jika belum ada -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

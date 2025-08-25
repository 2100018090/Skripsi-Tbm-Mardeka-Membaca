@extends('user.layouts.default.beranda')

@section('content')
    <div class="flex flex-col">
        <div class="flex flex-col items-center px-4 mt-6 md:mt-10 mb-10 md:mb-10">
            <h6 class="text-xl md:text-2xl font-medium text-center text-[#003350]">MardekaLiterasi</h6>
            <h1 class="text-2xl md:text-3xl font-bold text-center text-[#003350]">Riwayat Peminjaman</h1>
            <img src="{{ asset('storage/icon/garis.svg') }}" alt="Garis" class="h-[6px] md:h-[8px] w-auto mt-2 md:mt-4">
        </div>

        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            @php
                                $adaDenda = $peminjaman->contains(function ($item) {
                                    return $item->denda > 0;
                                });
                            @endphp

                            <tr>
                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">No</th>
                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Judul</th>
                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Tgl Pinjam</th>
                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Tgl Kembali</th>
                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Status</th>

                                @if ($tampilkanKolomDenda)
                                    <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Jumlah Denda</th>
                                @endif

                                <th class="text-sm font-medium text-gray-900 px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $index => $pinjam)
                                <tr
                                    class="border-b hover:bg-gray-100 transition duration-300 {{ $pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil' ? 'bg-yellow-100' : 'bg-white' }}">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $pinjam->buku->judul }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_pengembalian)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2">
                                        {{ $pinjam->status_pengembalian === 'Kembalikan'
                                            ? 'Sudah Dikembalikan'
                                            : ($pinjam->status_pengembalian === 'Tolak'
                                                ? 'Ditolak'
                                                : $pinjam->status_pengembalian) }}

                                    </td>
                                    @if ($tampilkanKolomDenda)
                                        <td
                                            class="px-4 py-2 text-sm font-semibold {{ $pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil' ? 'text-red-600' : 'text-gray-500 italic' }}">
                                            @if ($pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil')
                                                Rp{{ number_format($pinjam->denda, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif

                                    <td class="px-4 py-2">
                                        <button onclick="openModal('modal-{{ $pinjam->id }}')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">Lihat
                                            Detail</button>
                                        @if (is_null($pinjam->rating) && $pinjam->status_pengembalian === 'Kembalikan' && $pinjam->denda == 0)
                                            <button onclick="openModal('modal-rating-{{ $pinjam->id }}')"
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm mt-2 sm:mt-0 sm:ml-2">
                                                Tambahkan Rating
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Modal Detail --}}
                                <div id="modal-{{ $pinjam->id }}"
                                    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
                                    <div
                                        class="bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-6 relative border-t-8 border-blue-500 mx-4">
                                        <button onclick="closeModal('modal-{{ $pinjam->id }}')"
                                            class="absolute top-3 right-4 text-2xl text-gray-600">&times;</button>
                                        <h3 class="text-xl font-bold text-blue-600 mb-4">Detail Peminjaman</h3>
                                        <div class="space-y-2 text-sm text-gray-700">
                                            <div><strong>📘 Judul:</strong> {{ $pinjam->buku->judul }}</div>
                                            <div><strong>📅 Tanggal Pinjam:</strong>
                                                {{ $pinjam->tanggal_pinjam->format('d M Y') }}</div>
                                            <div><strong>📆 Tanggal Kembali:</strong>
                                                {{ $pinjam->tanggal_pengembalian->format('d M Y') }}</div>
                                            <div><strong>🔁 Status:</strong> {{ $pinjam->status_pengembalian }}</div>
                                            @if ($pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil')
                                                <div><strong>💰 Denda:</strong>
                                                    Rp{{ number_format($pinjam->denda ?? 0, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2">
                                            @if ($pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil')
                                                <button onclick="openModal('modalPembayaran-{{ $pinjam->id }}')"
                                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 w-full sm:w-auto">Bayar
                                                    Denda</button>
                                            @endif
                                            <button onclick="closeModal('modal-{{ $pinjam->id }}')"
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full sm:w-auto">Tutup</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Pembayaran --}}
                                <div id="modalPembayaran-{{ $pinjam->id }}"
                                    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
                                    <div class="bg-white p-6 rounded-2xl w-[90%] max-w-md shadow-lg mx-4">
                                        <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Metode Pembayaran QRIS
                                        </h2>
                                        <form action="{{ route('anggota.denda') }}" method="POST"
                                            enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="pinjam_id" value="{{ $pinjam->id }}">
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600 mb-2">Scan QR berikut:</p>
                                                <img src="https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/pages/qris.jpeg"
                                                    alt="QRIS" class="w-40 h-40 mx-auto border rounded shadow">
                                            </div>
                                            <div class="text-center">
                                                <label for="bukti-{{ $pinjam->id }}"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Unggah Bukti Pembayaran
                                                </label>
                                                <input type="file" name="bukti_pembayaran"
                                                    id="bukti-{{ $pinjam->id }}" accept="image/*,.pdf"
                                                    class="w-full max-w-xs mx-auto block file:cursor-pointer file:rounded-md file:border-0 file:bg-blue-500 file:px-4 file:py-2 file:text-sm file:text-white file:font-semibold file:hover:bg-blue-600 transition border border-gray-300 rounded-md px-3 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, PDF (maks. 2MB)</p>
                                            </div>
                                            <div class="flex flex-col sm:flex-row justify-end gap-2">
                                                <button type="button"
                                                    onclick="closeModal('modalPembayaran-{{ $pinjam->id }}')"
                                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg w-full sm:w-auto">Tutup</button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium w-full sm:w-auto">Bayar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Modal Rating --}}
                                @if (is_null($pinjam->rating))
                                    <div id="modal-rating-{{ $pinjam->id }}"
                                        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
                                        <div
                                            class="bg-white rounded-2xl shadow-xl w-[90%] max-w-md mx-4 p-6 border-t-8 border-yellow-400 relative">
                                            <button onclick="closeModal('modal-rating-{{ $pinjam->id }}')"
                                                class="absolute top-3 right-3 text-2xl text-gray-600 hover:text-red-500">&times;</button>
                                            <h3 class="text-xl font-bold text-yellow-500 mb-4 text-center">Beri Rating</h3>
                                            <form action="{{ route('anggota.storeRating', ['id' => $pinjam->id]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="peminjaman_id" value="{{ $pinjam->id }}">
                                                <input type="hidden" name="rating"
                                                    id="svg-rating-input-{{ $pinjam->id }}" required>

                                                <div class="flex justify-center gap-2 mb-4"
                                                    id="svg-stars-{{ $pinjam->id }}">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg data-value="{{ $i }}"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="currentColor"
                                                            class="w-6 h-6 sm:w-8 sm:h-8 text-gray-300 hover:text-yellow-400 cursor-pointer star-svg transition-colors duration-200">
                                                            <path
                                                                d="M11.998 2.25c.36 0 .692.205.857.53l2.308 4.678 5.164.75c.375.055.692.287.854.631.162.345.133.744-.077 1.06l-3.736 5.462.883 5.145c.063.37-.087.746-.39.982-.302.236-.705.294-1.058.153L12 18.347l-4.63 2.394c-.354.142-.757.084-1.059-.153a1.05 1.05 0 01-.389-.982l.883-5.145-3.737-5.462a1.052 1.052 0 01-.077-1.06c.162-.344.48-.576.855-.63l5.164-.75 2.308-4.678a.956.956 0 01.857-.53z" />
                                                        </svg>
                                                    @endfor
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block font-semibold text-gray-700 mb-1">Ulasan</label>
                                                    <textarea name="ulasan" rows="3"
                                                        class="w-full border rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                                        placeholder="Tulis ulasan..."></textarea>
                                                </div>

                                                <div class="flex justify-end">
                                                    <button type="submit"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-semibold shadow-md transition">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                {{-- Script Rating --}}
                                <script>
                                    document.querySelectorAll('[id^="svg-stars-"]').forEach(container => {
                                        const pinjamId = container.id.split('-')[2];
                                        const stars = container.querySelectorAll('.star-svg');
                                        const ratingInput = document.getElementById(`svg-rating-input-${pinjamId}`);
                                        stars.forEach((star, index) => {
                                            star.addEventListener('click', () => {
                                                const rating = index + 1;
                                                ratingInput.value = rating;
                                                stars.forEach((s, i) => {
                                                    s.classList.toggle('text-yellow-400', i < rating);
                                                    s.classList.toggle('text-gray-300', i >= rating);
                                                });
                                            });
                                        });
                                    });
                                </script>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-gray-500 py-4">
                                        Anda belum melakukan peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4 px-4 flex justify-center">
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endsection

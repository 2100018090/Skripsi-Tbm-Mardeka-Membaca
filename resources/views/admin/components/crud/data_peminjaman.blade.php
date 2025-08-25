@if ($peminjamans->count())
    @foreach ($peminjamans as $peminjaman)
            @php
                $role = auth()->user()->role;
            @endphp
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                    <img class="w-10 h-10 rounded-full"
                        src="{{ optional($peminjaman->anggota)->img
                            ? rtrim(env('SUPABASE_URL'), '/') .
                                '/storage/v1/object/public/' .
                                env('SUPABASE_BUCKET') .
                                '/' .
                                $peminjaman->anggota->img
                            : asset('storage/admin/default.png') }}"
                        alt="Foto Anggota">


                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        <div class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $peminjaman->anggota->nama }}</div>
                </td>
                <td
                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                    {{ $peminjaman->buku->judul }}</td>
                <td
                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td
                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('d-m-Y') }}
                </td>
                <td
                    class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                    {{ $peminjaman->status_pengembalian }}</td>

                <td class="p-4 space-x-2 whitespace-nowrap">
                    {{-- Tombol Kembalikan --}}
                    @if (in_array(strtolower($peminjaman->status_pengembalian), ['dipinjam', 'kembalikan', 'tolak']))
                        <!-- Tombol untuk membuka modal -->
                        @if ($peminjaman->status_pengembalian === 'Dipinjam' && auth()->user()->role === 'voluntter')
                            <button type="button" onclick="openModal({{ $peminjaman->id }})"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 10a1 1 0 011-1h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 11-1.414-1.414L13.586 11H4a1 1 0 01-1-1z" />
                                </svg>
                                Kembalikan
                            </button>
                        @endif

                        <!-- Modal -->
                        <div id="modal-{{ $peminjaman->id }}"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Konfirmasi Pengembalian Buku</h2>

                                <form action="{{ route('admin.kembalikanBuku', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label class="block text-sm mb-1 text-gray-700">Masukkan ISBN Buku:</label>
                                    <input type="text" name="isbn" class="border p-2 rounded w-full" required
                                        value="{{ $peminjaman->buku->isbn }}" readonly>
                                    <label class="block text-sm mb-1 text-gray-700">Kode Buku Fisik:</label>
                                    <input type="text" name="kode" class="border p-2 rounded w-full bg-gray-100"
                                        readonly value="{{ optional($peminjaman->detailbuku)->kode ?? 'Belum tersedia' }}">

                                    {{-- Pilih Status Kondisi Buku --}}
                                    <label class="block text-sm mt-4 mb-1 text-gray-700">Status Kondisi Buku:</label>
                                    <select name="status_kondisi" class="border p-2 rounded w-full" required>
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                        <option value="Hilang">Hilang</option>
                                    </select>

                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" onclick="closeModal({{ $peminjaman->id }})"
                                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            onclick="return confirm('Yakin ISBN sudah benar dan ingin mengembalikan buku?')"
                                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-800 text-sm">
                                            Konfirmasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script>
                            function openModal(id) {
                                document.getElementById('modal-' + id).classList.remove('hidden');
                            }

                            function closeModal(id) {
                                document.getElementById('modal-' + id).classList.add('hidden');
                            }
                        </script>

                        @if ($role === 'admin')
                            <!-- Tombol Edit (Trigger Modal) -->
                            <button type="button" data-modal-target="modal-edit-{{ $peminjaman->id }}"
                                data-modal-toggle="modal-edit-{{ $peminjaman->id }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd"
                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Edit
                            </button>

                            <button type="button" data-modal-target="delete-user-modal-{{ $peminjaman->id }}"
                                data-modal-toggle="delete-user-modal-{{ $peminjaman->id }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Hapus
                            </button>
                        @endif
                        <!-- Modal -->
                        <div id="modal-edit-{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

                                    <!-- Header -->
                                    <div
                                        class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Edit Peminjaman
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                            data-modal-hide="modal-edit-{{ $peminjaman->id }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Form -->
                                    <form action="{{ route('admin.updatePeminjaman', $peminjaman->id) }}" method="POST"
                                        class="p-6 space-y-6">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="id" value="{{ $peminjaman->id }}">

                                        <div class="grid grid-cols-6 gap-6">
                                            <!-- Nama Anggota -->
                                            <!-- Nama Anggota -->
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="id_anggota"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Nama Anggota
                                                </label>
                                                <select id="id_anggota" name="id_anggota" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="">-- Pilih Anggota --</option>
                                                    @foreach ($anggotaList as $anggota)
                                                        <option value="{{ $anggota->id }}"
                                                            {{ $anggota->id == $peminjaman->id_anggota ? 'selected' : '' }}>
                                                            {{ $anggota->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Judul Buku -->
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="id_buku"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Judul Buku
                                                </label>
                                                <select id="id_buku" name="id_buku" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="">-- Pilih Buku --</option>
                                                    @foreach ($bukuList as $buku)
                                                        <option value="{{ $buku->id }}"
                                                            {{ $buku->id == $peminjaman->id_buku ? 'selected' : '' }}>
                                                            {{ $buku->judul }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- Tanggal -->
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="tanggal_pinjam"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                    Pinjam</label>
                                                <input type="date" name="tanggal_pinjam"
                                                    value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d') }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>
                                            </div>
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="tanggal_ambil"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                    Ambil</label>
                                                <input type="date" name="tanggal_ambil"
                                                    value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_ambil)->format('Y-m-d') }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>

                                            </div>
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="tanggal_pengembalian"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                    Pengembalian</label>
                                                <input type="date" name="tanggal_pengembalian"
                                                    value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->format('Y-m-d') }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    required>

                                            </div>

                                            <!-- Status -->
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="status_pengembalian"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                                <select name="status_pengembalian"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    @foreach (['Belum Diambil', 'Dipinjam', 'Kembalikan', 'Terlambat', 'Tolak'] as $status)
                                                        <option value="{{ $status }}"
                                                            {{ $peminjaman->status_pengembalian === $status ? 'selected' : '' }}>
                                                            {{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Denda -->
                                            <div class="col-span-6 sm:col-span-3">
                                                <label for="denda"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Denda</label>
                                                <input type="number" name="denda" value="{{ $peminjaman->denda }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="flex justify-end border-t pt-6 dark:border-gray-700">
                                            <button type="submit"
                                                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- Modal Konfirmasi -->
                        <div id="delete-user-modal-{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true"
                            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                            <div class="relative w-full h-full max-w-md md:h-auto">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="p-6 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                            Yakin ingin menghapus peminjaman <b>{{ $peminjaman->nama }}</b>?
                                        </h3>
                                        <form method="POST"
                                            action="{{ route('admin.deletePeminjaman', $peminjaman->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 mr-2">
                                                Ya, Hapus
                                            </button>
                                            <button type="button"
                                                data-modal-hide="delete-user-modal-{{ $peminjaman->id }}"
                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($peminjaman->status_pengembalian === 'Belum Diambil' && auth()->user()->role === 'voluntter')
                        <!-- Button Trigger Modal -->
                        <div class="flex gap-2">
                            {{-- Tombol Konfirmasi --}}
                            <button type="button" data-modal-target="konfirmasi-modal-{{ $peminjaman->id }}"
                                data-modal-toggle="konfirmasi-modal-{{ $peminjaman->id }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414L9 13.414l4.707-4.707z"
                                        clip-rule="evenodd" />
                                </svg>
                                Konfirmasi
                            </button>

                            {{-- Tombol Tolak --}}
                            <form action="{{ route('peminjaman.tolak', $peminjaman->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menolak pengembalian ini?')">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M6 6a1 1 0 011.707-.707L10 7.586l2.293-2.293A1 1 0 0114.707 6L12.414 8.293l2.293 2.293a1 1 0 01-1.414 1.414L11 9.414l-2.293 2.293a1 1 0 01-1.414-1.414L9.586 8.293 7.293 6A1 1 0 016 6z" />
                                    </svg>
                                    Tolak
                                </button>
                            </form>
                        </div>
            </tr>
            <!-- Modal Konfirmasi -->
            <div id="konfirmasi-modal-{{ $peminjaman->id }}" tabindex="-1" aria-hidden="true"
                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                <div class="relative w-full max-w-md h-full md:h-auto">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <form action="{{ route('admin.prosesKonfirmasi', $peminjaman->id) }}" method="POST">
                            @csrf
                            <div class="p-6 text-left">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Konfirmasi Peminjaman
                                </h3>

                                <p class="text-sm text-gray-700 mb-2"><strong>Judul:</strong>
                                    {{ $peminjaman->buku->judul }}</p>

                                <label for="id_detail_buku"
                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                    Pilih Kode Buku Fisik
                                </label>

                                <select name="id_detail_buku" id="id_detail_buku"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                    <option disabled selected value="">-- Pilih Kode Buku --</option>
                                    @php
                                        $detailBukuTersedia = \App\Models\Detailbuku::where('id_buku', $peminjaman->id_buku)
                                            ->where('dipinjam', false)
                                            ->get();
                                    @endphp

                                    @foreach ($detailBukuTersedia as $detail)
                                        <option value="{{ $detail->id }}">{{ $detail->kode }}</option>
                                    @endforeach
                                </select>

                                <div class="mt-6 flex justify-end space-x-2">
                                    <button data-modal-hide="konfirmasi-modal-{{ $peminjaman->id }}" type="button"
                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-gray-800">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Konfirmasi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        </td>
        </tr>
    @endforeach
@else
<tr>
    <td colspan="4" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
</tr>
@endif

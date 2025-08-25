@extends('admin.layouts.default.beranda')
@section('content')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div
        class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Data Peminjaman</h1>
            </div>
            <div class="sm:flex">
                <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <form class="lg:pr-3" action="{{ route('admin.cariPeminjaman') }}" method="GET">
                        <label for="users-search" class="sr-only">Search</label>
                        <div class="relative mt-1 lg:w-64 xl:w-96 flex items-center gap-2">
                            <input type="text" name="keyword" id="users-search" value="{{ request('keyword') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
            dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Masukkkan Nama Peminjam atau Nama Buku">

                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-500 dark:hover:bg-primary-600">
                                Cari
                            </button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('users-search');
                            const form = searchInput.closest('form');

                            searchInput.addEventListener('input', function() {
                                if (this.value.trim() === '') {
                                    form.submit(); // auto-submit saat kosong
                                }
                            });
                        });
                    </script>
                </div>
                <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                    <!-- Tombol Flowbite untuk membuka modal -->
                    <button type="button" data-modal-target="add-peminjaman-modal" data-modal-toggle="add-peminjaman-modal"
                        class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Tambah Peminjaman
                    </button>
                    <!-- Modal dengan ID: add-peminjaman-modal -->
                    <form action="{{ route('peminjaman.baru') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div id="add-peminjaman-modal" tabindex="-1" aria-hidden="true"
                            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto inset-0 max-h-full bg-black/50">
                            <div class="relative w-full max-w-2xl max-h-full mx-auto">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex items-center justify-between p-4 border-b">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Peminjaman
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                            data-modal-hide="add-peminjaman-modal">
                                            ✕
                                        </button>
                                    </div>

                                    <div class="p-6 space-y-4">
                                        <!-- STEP 1: Akun & Anggota -->
                                        <div id="step-1" class="step">
                                            <h4 class="text-lg font-bold text-gray-700 mb-4">Langkah 1: Data Akun & Anggota
                                            </h4>

                                            <!-- Email -->
                                            <input type="email" name="email" placeholder="Email" required
                                                class="w-full mb-3 px-3 py-2 border rounded">

                                            <!-- Username -->
                                            <input type="text" name="username" placeholder="Username" required
                                                class="w-full mb-3 px-3 py-2 border rounded">

                                            <!-- Password -->
                                            <input type="password" name="password" placeholder="Password" required
                                                class="w-full mb-3 px-3 py-2 border rounded">

                                            <!-- Nama -->
                                            <input type="text" name="nama" placeholder="Nama Anggota" required
                                                class="w-full mb-3 px-3 py-2 border rounded">

                                            <!-- Alamat -->
                                            <textarea name="alamat" placeholder="Alamat" rows="2" required class="w-full mb-3 px-3 py-2 border rounded"></textarea>

                                            <!-- No Telepon -->
                                            <input type="text" name="notlp" placeholder="No Telepon" required
                                                class="w-full mb-3 px-3 py-2 border rounded">

                                            <!-- Foto -->
                                            <input type="file" name="img"
                                                class="block w-full text-sm text-gray-500 file:py-1 file:px-2 file:rounded file:bg-blue-100 file:text-blue-700">

                                            <div class="flex justify-end mt-4">
                                                <button type="button" id="nextStep"
                                                    class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Selanjutnya</button>
                                            </div>
                                        </div>
                                        <!-- STEP 2: Buku -->
                                        <div id="step-2" class="step hidden">
                                            <h4 class="text-lg font-bold text-gray-700 mb-4">Langkah 2: Data Peminjaman</h4>

                                            <!-- Pilih Buku -->
                                            <select name="id_buku" required class="w-full px-3 py-2 border rounded"
                                                id="select-buku">
                                                <option value="">-- Pilih Buku --</option>
                                                @foreach ($bukuFisik as $buku)
                                                    <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                                                @endforeach
                                            </select>

                                            <!-- Pilih Detail Buku -->
                                            <select name="id_detail_buku" class="w-full px-3 py-2 border rounded mt-4"
                                                id="select-detail-buku">
                                                <option value="">-- Pilih Kode Buku --</option>
                                            </select>

                                            <script>
                                                document.getElementById('select-buku').addEventListener('change', function() {
                                                    const bukuId = this.value;
                                                    const detailSelect = document.getElementById('select-detail-buku');

                                                    // Kosongkan dulu pilihan detail
                                                    detailSelect.innerHTML = '<option value="">-- Pilih Kode Buku --</option>';

                                                    if (!bukuId) return;

                                                    fetch(`/api/detail-buku/${bukuId}`)
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            if (data.length === 0) {
                                                                detailSelect.innerHTML += '<option value="">(Tidak ada kode tersedia)</option>';
                                                            } else {
                                                                data.forEach(detail => {
                                                                    const option = document.createElement('option');
                                                                    option.value = detail.id;
                                                                    option.text = detail.kode;
                                                                    detailSelect.appendChild(option);
                                                                });
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error('Gagal memuat detail buku:', error);
                                                            alert('Gagal memuat data kode buku.');
                                                        });
                                                });
                                            </script>




                                            <!-- Tanggal Pinjam -->
                                            <label class="block mt-4 mb-1 text-sm">Tanggal Pinjam</label>
                                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                                class="w-full px-3 py-2 border rounded" min="{{ now()->toDateString() }}"
                                                required>


                                            <!-- Tanggal Pengembalian -->
                                            <label class="block mt-4 mb-1 text-sm">Tanggal Pengembalian</label>
                                            <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian"
                                                class="w-full px-3 py-2 border rounded" readonly>
                                            <script>
                                                document.getElementById('tanggal_pinjam').addEventListener('change', function() {
                                                    const tanggalPinjam = new Date(this.value);
                                                    if (!isNaN(tanggalPinjam.getTime())) {
                                                        tanggalPinjam.setDate(tanggalPinjam.getDate() + 5);
                                                        const yyyy = tanggalPinjam.getFullYear();
                                                        const mm = String(tanggalPinjam.getMonth() + 1).padStart(2, '0');
                                                        const dd = String(tanggalPinjam.getDate()).padStart(2, '0');
                                                        const tanggalPengembalian = `${yyyy}-${mm}-${dd}`;
                                                        document.getElementById('tanggal_pengembalian').value = tanggalPengembalian;
                                                    }
                                                });
                                            </script>



                                            <div class="flex justify-between mt-6">
                                                <button type="button" id="prevStep"
                                                    class="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600">Kembali</button>
                                                <button type="submit"
                                                    class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Simpan</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.getElementById('nextStep').addEventListener('click', function() {
                            document.getElementById('step-1').classList.add('hidden');
                            document.getElementById('step-2').classList.remove('hidden');
                        });

                        document.getElementById('prevStep').addEventListener('click', function() {
                            document.getElementById('step-2').classList.add('hidden');
                            document.getElementById('step-1').classList.remove('hidden');
                        });
                    </script>



                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Peminjam
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Nama Buku
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Tgl Peminjaman
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Tgl Kembali
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Status
                                </th>
                                <th scope="col"
                                    class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @include('admin.components.crud.data_peminjaman')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.components.crud.lihat_peminjaman')

    {{-- @include('admin.components.crud.edit_peminjaman') --}}


    {{-- tambah data anggota --}}
    {{-- @include('admin.components.crud.tambah_voluntter') --}}

    <!-- Delete User Modal -->
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full"
        id="delete-user-modal">
        <div class="relative w-full h-full max-w-md px-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex justify-end p-2">
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-hide="delete-user-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 pt-0 text-center">
                    <svg class="w-16 h-16 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-5 mb-6 text-lg text-gray-500 dark:text-gray-400">Are you sure you want to delete this
                        user?</h3>
                    <a href="#"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2 dark:focus:ring-red-800">
                        Yes, I'm sure
                    </a>
                    <a href="#"
                        class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                        data-modal-hide="delete-user-modal">
                        No, cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

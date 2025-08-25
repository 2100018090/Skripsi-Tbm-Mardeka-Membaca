{{-- =====================  Buku  ===================== --}}
<div id="add-buku-digital" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden h-modal sm:h-full">
    <div class="relative w-full h-full max-w-2xl px-4 md:h-auto mx-auto mt-4">
        {{-- Modal content --}}
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            {{-- Modal header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">
                    Tambah Buku Digital
                </h3>
                <button type="button" data-modal-hide="add-buku-digital"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="p-6 space-y-6">
                <form action="{{ route('admin.createBuku') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">

                        {{-- Judul --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="judul"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                            <input id="judul" name="judul" type="text" required placeholder="Masukkan Judul"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Kategori --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="id_kategori"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <select id="id_kategori" name="id_kategori" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                           rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                           dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                           dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Penulis --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="penulis"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penulis</label>
                            <input id="penulis" name="penulis" type="text" required placeholder="Masukkan Penulis"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- isbn --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="isbn"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISBN</label>
                            <input id="isbn" name="isbn" type="text" required placeholder="Masukkan Isbn"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Penerbit --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="penerbit"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penerbit</label>
                            <input id="penerbit" name="penerbit" type="text"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Tahun Terbit --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="tahun_terbit"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun
                                Terbit</label>
                            <input id="tahun_terbit" name="tahun_terbit" type="date"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        <!-- Input hidden langsung set ke 'digital' -->
                        <input type="hidden" id="tipe" name="tipe" value="digital">

                        {{-- file --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="file_url"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File</label>
                            <input id="file_url" name="file_url" type="file"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>


                        {{-- jumlahHalaman --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="jumlahHalaman"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah
                                Halaman</label>
                            <input id="jumlahHalaman" name="jumlahHalaman" type="number"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Harga --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="harga"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                            <input id="harga" name="harga" type="text"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="deskripsi"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                            <input id="deskripsi" name="deskripsi" type="text"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Img --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="img"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover</label>
                            <input type="file" id="img" name="img"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer
                                          bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                                          dark:placeholder-gray-400">
                        </div>

                    </div>

                    {{-- Modal footer + tombol submit --}}
                    <div
                        class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg
                                       hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
                                       dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Tambah Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

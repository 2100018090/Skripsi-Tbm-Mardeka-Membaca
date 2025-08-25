{{-- =====================  MODAL  ===================== --}}
<div id="add-pengumuman-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden h-modal sm:h-full">
    <div class="relative w-full h-full max-w-2xl px-4 md:h-auto mx-auto mt-4">
        {{-- Modal content --}}
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            {{-- Modal header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">
                    Tambah Pengumuman
                </h3>
                <button type="button" data-modal-hide="add-pengumuman-modal"
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
                <form action="{{ route('admin.createPengumuman') }}" method="POST" enctype="multipart/form-data">
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

                        {{-- tanggal --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="tanggal"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                            <input id="tanggal" name="tanggal" type="date" required placeholder="Masukkan Tanggal"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Image --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="img"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                            <input id="img" name="img" type="file"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        <div class="col-span-6">
                            <label for="isi"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi</label>
                            <textarea id="isi" name="isi" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="👨‍💻masukkan isi pengumuman"></textarea>
                        </div>


                    </div>

                    {{-- Modal footer + tombol submit --}}
                    <div
                        class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg
                                       hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
                                       dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Tambah Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

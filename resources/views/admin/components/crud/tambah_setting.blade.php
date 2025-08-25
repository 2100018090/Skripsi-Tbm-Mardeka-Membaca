{{-- =====================  MODAL  ===================== --}}
<div id="add-setting-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden h-modal sm:h-full">
    <div class="relative w-full h-full max-w-2xl px-4 md:h-auto mx-auto mt-4">
        {{-- Modal content --}}
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            {{-- Modal header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">
                    Tambah Setting
                </h3>
                <button type="button" data-modal-hide="add-setting-modal"
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
                <form action="{{ route('admin.createSetting') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-6 gap-6">

                        {{-- slug --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="slug"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                            <input id="slug" name="slug" type="text" required placeholder="Masukkan Slug"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                        {{-- Img --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="img"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Img</label>
                            <input type="file" id="img" name="img"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer
                                          bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                                          dark:placeholder-gray-400">
                        </div>

                        {{-- content --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label for="content"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                            <input id="content" name="content" type="text" placeholder="Masukkan Content"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm
                                          rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                          dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                          dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>

                    </div>

                    {{-- Modal footer + tombol submit --}}
                    <div
                        class="flex justify-end items-center pt-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg
                                       hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
                                       dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Tambah Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

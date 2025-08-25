<!-- component -->
<div class="my-5 mt-20">
    <!-- Main container for the form, responsive to screen sizes -->
    <div
        class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-[#F0F9F8] dark:bg-gray-800 border-emerald-500 rounded-md">

        <div class="my-3">
            <!-- Form title -->
            <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Kategori</h1>
            <form action="" method="POST">

                <!-- Input 'Nama' -->
                <div class="mb-4 flex items-center">
                    <label for="nama"
                        class="w-1/4 text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" name="nama" id="nama"
                        class="w-3/4 border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white" />
                </div>

                <!-- Input 'Deskripsi' -->
                <div class="mb-4 flex items-start">
                    <label for="deskripsi"
                        class="w-1/4 text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300 mt-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-3/4 border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-y"
                     ></textarea>
                </div>

                <!-- Input field for 'Gambar' -->
                <div class="mb-4 flex items-center">
                    <label for="gambar"
                        class="w-1/4 text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Gambar</label>
                    <input type="file" name="gambar" id="gambar"
                        class="w-3/4 text-sm sm:text-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:border-0 file:py-2 file:px-4 file:rounded-md file:bg-emerald-500 file:text-black file:cursor-pointer" />
                </div>

                <!-- Save button, aligned to the right -->
                <div class="flex justify-end mt-4">
                    <button
                        class="flex items-center gap-2 px-4 py-1 bg-[#64C0B7] rounded-md text-white text-sm sm:text-lg shadow-md">
                        <img src="{{ asset('storage/icon/pencil.svg') }}" alt="Edit Icon" class="w-4 h-4 sm:w-5 sm:h-5" />
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

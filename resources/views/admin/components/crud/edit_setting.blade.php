{{-- =========================  MODAL EDIT PENGUMUMAN  ========================= --}}
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
    <div class="relative w-full max-w-2xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

            {{-- Modal Header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">Edit Pengumuman</h3>
                <button type="button" id="close-edit-modal"
                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form id="form-edit" action="#" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">

                <div class="grid grid-cols-6 gap-6">
                    {{-- Slug --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-slug"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug</label>
                        <input type="text" id="edit-slug" name="slug" placeholder="Masukkan Judul" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Img --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-img"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Img</label>
                        <input type="file" id="edit-img" name="img"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer
                                          bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600
                                          dark:placeholder-gray-400">
                    </div>


                    {{-- content --}}
                    <div class="col-span-6">
                        <label for="edit-content"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                        <textarea id="edit-content" name="content" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="👨‍💻masukkan isi content"></textarea>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                    <button type="submit"
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5
                        dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".edit-setting-button").forEach(btn => {
            btn.addEventListener("click", async () => {
                const url = btn.dataset.url;

                try {
                    const res = await fetch(url);
                    const page = await res.json();
                    if (!page || !page.id) throw new Error(
                        "Data tidak ditemukan.");

                    // Isi field form
                    document.getElementById("edit-id").value = page.id ?? '';
                    document.getElementById("edit-slug").value = page.slug ?? '';
                    // document.getElementById("edit-img").value = page.img ??
                    //     '';
                    document.getElementById("edit-content").value = page.content ?? '';

                    // Set action form
                    document.getElementById("form-edit").action =
                        `/admin/updateSetting/${page.id}`;

                    // Tampilkan modal
                    document.getElementById("edit-user-modal").classList.remove("hidden");

                } catch (error) {
                    console.error(error);
                    alert("Gagal memuat data setting.");
                }
            });
        });

        // Tombol close
        document.getElementById("close-edit-modal").addEventListener("click", () => {
            document.getElementById("edit-user-modal").classList.add("hidden");
        });

        // Klik overlay untuk tutup modal
        document.getElementById("edit-user-modal").addEventListener("click", (e) => {
            if (e.target === e.currentTarget) {
                e.currentTarget.classList.add("hidden");
            }
        });
    });
</script>

{{-- =========================  MODAL EDIT PENGUMUMAN  ========================= --}}
<div id="edit-user-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
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
                    {{-- Judul --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-judul"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                        <input type="text" id="edit-judul" name="judul" placeholder="Masukkan Judul" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-tanggal"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal</label>
                        <input type="date" id="edit-tanggal" name="tanggal" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                                        {{-- Gambar --}}
                    <div class="col-span-6">
                        <label for="img"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar</label>
                        <input type="file" id="img" name="img"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>


                    {{-- Isi --}}
                        <div class="col-span-6">
                            <label for="edit-isi"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Isi</label>
                            <textarea id="edit-isi" name="isi" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="👨‍💻masukkan isi pengumuman"></textarea>
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
        document.querySelectorAll(".edit-button").forEach(btn => {
            btn.addEventListener("click", async () => {
                const url = btn.dataset.url;

                try {
                    const res = await fetch(url);
                    const pengumuman = await res.json();
                    if (!pengumuman || !pengumuman.id) throw new Error(
                        "Data tidak ditemukan.");

                    // Isi field form
                    document.getElementById("edit-id").value = pengumuman.id ?? '';
                    document.getElementById("edit-judul").value = pengumuman.judul ?? '';
                    document.getElementById("edit-tanggal").value = pengumuman.tanggal ??
                    '';
                    document.getElementById("edit-isi").value = pengumuman.isi ?? '';

                    // Set action form
                    document.getElementById("form-edit").action =
                        `/admin/updatePengumuman/${pengumuman.id}`;

                    // Tampilkan modal
                    document.getElementById("edit-user-modal").classList.remove("hidden");

                } catch (error) {
                    console.error(error);
                    alert("Gagal memuat data pengumuman.");
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

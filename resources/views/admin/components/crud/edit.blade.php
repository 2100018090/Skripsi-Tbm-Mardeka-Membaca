{{-- =========================  MODAL EDIT ANGGOTA  ========================= --}}
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
    <div class="relative w-full max-w-2xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

            {{-- ---------- Modal Header ---------- --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">Edit Anggota</h3>
                <button type="button" id="close-edit-modal"
                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            {{-- ---------- Modal Body ---------- --}}
            <form id="form-edit" action="#" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">

                <div class="grid grid-cols-6 gap-6">
                    {{-- EMAIL --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="edit-email" name="email" placeholder="Masukkan Email" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- USERNAME --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" id="edit-username" name="username" placeholder="Masukkan Username" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- NAMA --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" id="edit-nama" name="nama" placeholder="Masukkan Nama" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Masukkan Alamat" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- NO TELP --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="notlp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No Telepon</label>
                        <input type="text" id="notlp" name="notlp" placeholder="Masukkan No Telepon" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- STATUS --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <input type="text" id="status" name="status" placeholder="Masukkan Status" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- IMAGE --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="img" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                        <input type="file" id="img" name="img"
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

                {{-- ---------- Modal Footer ---------- --}}
                <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                    <button type="submit"
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5
                        dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =========================  SCRIPT ========================= --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {

        /*=========== 1. TOMBOL EDIT (ambil data & buka modal) ===========*/
        document.querySelectorAll(".edit-button").forEach(btn => {
            btn.addEventListener("click", async () => {
                const url = btn.dataset.url;

                try {
                    const res = await fetch(url);
                    const user = await res.json();
                    if (!user || !user.id) throw new Error("Data anggota tidak ditemukan.");

                    // Isi field form
                    document.getElementById("edit-id").value = user.id ?? '';
                    document.getElementById("edit-nama").value = user.nama ?? '';
                    document.getElementById("alamat").value = user.alamat ?? '';
                    document.getElementById("notlp").value = user.notlp ?? '';
                    document.getElementById("status").value = user.status ?? '';
                    document.getElementById("edit-email").value = user.akun?.email ?? '';
                    document.getElementById("edit-username").value = user.akun?.username ?? '';

                    // Set action form sesuai ID
                    document.getElementById("form-edit").action = `/admin/updateAnggota/${user.id}`;

                    // Tampilkan modal
                    document.getElementById("edit-user-modal").classList.remove("hidden");

                } catch (error) {
                    console.error(error);
                    alert("Gagal memuat data anggota.");
                }
            });
        });

        /*=========== 2. TOMBOL CLOSE ===========*/
        document.getElementById("close-edit-modal").addEventListener("click", () => {
            document.getElementById("edit-user-modal").classList.add("hidden");
        });

        /*=========== 3. KLIK OVERLAY UNTUK MENUTUP ===========*/
        document.getElementById("edit-user-modal").addEventListener("click", (e) => {
            if (e.target === e.currentTarget) {
                e.currentTarget.classList.add("hidden");
            }
        });
    });
</script>

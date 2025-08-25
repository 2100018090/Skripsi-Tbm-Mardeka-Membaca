{{-- =========================  MODAL EDIT ANGGOTA  ========================= --}}
<div id="lihat-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
    <div class="relative w-full max-w-2xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

            {{-- ---------- Modal Header ---------- --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">Lihat Peminjaman</h3>
                <button type="button" id="close-lihat-modal"
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
                <input type="hidden" id="lihat-id" name="id">

                <div class="grid grid-cols-6 gap-6">
                    {{-- Anggota --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="anggota"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anggota</label>
                        <input type="text" id="anggota" name="anggota" placeholder="Masukkan Anggota" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Buku --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="buku"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Buku</label>
                        <input type="text" id="buku" name="judul" placeholder="Masukkan Buku" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- tanggal_pinjam --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_pinjam"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal_pinjam</label>
                        <input type="text" id="tanggal_pinjam" name="tanggal_pinjam"
                            placeholder="Masukkan Tanggal_pinjam" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Tanggal_ambil --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_ambil"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal_ambil</label>
                        <input type="text" id="tanggal_ambil" name="tanggal_ambil"
                            placeholder="Masukkan Tanggal_ambil" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
        focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>


                    {{-- Tanggal_pengembalian --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_pengembalian"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal_pengembalian</label>
                        <input type="text" id="tanggal_pengembalian" name="tanggal_pengembalian"
                            placeholder="Masukkan Tanggal_pengembalian" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Status_pengembalian --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="status_pengembalian"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status_pengembalian</label>
                        <input type="text" id="status_pengembalian" name="status_pengembalian"
                            placeholder="Masukkan Status_pengembalian" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Denda --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="denda"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Denda</label>
                        <input type="text" id="denda" name="denda" placeholder="Masukkan Denda" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                            focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- =========================  SCRIPT ========================= --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {

        /*=========== 1. TOMBOL EDIT (ambil data & buka modal) ===========*/
        document.querySelectorAll(".lihat-button").forEach(btn => {
            btn.addEventListener("click", async () => {
                const url = btn.dataset.url;
                console.log("URL tujuan:", url);

                try {
                    const res = await fetch(url);
                    const user = await res.json();
                    if (!user || !user.id) throw new Error("Data buku tidak ditemukan.");

                    // Isi field form
                    document.getElementById("lihat-id").value = user.id ?? '';
                    const tanggal_pinjam = user.tanggal_pinjam?.split("T")[0] ?? '';
                    document.getElementById("tanggal_pinjam").value = tanggal_pinjam;
                    const tanggal_ambil = user.tanggal_ambil?.split("T")[0] ?? '';
                    document.getElementById("tanggal_ambil").value = tanggal_ambil;
                    const tanggal = user.tanggal_pengembalian?.split("T")[0] ?? '';
                    document.getElementById("tanggal_pengembalian").value = tanggal;
                    document.getElementById("anggota").value = user.anggota?.nama ?? '';
                    document.getElementById("buku").value = user.buku?.judul ?? '';
                    document.getElementById("status_pengembalian").value = user
                        .status_pengembalian ?? '';
                    document.getElementById("denda").value = user.denda ?? '';
                    // document.getElementById("deskripsi").value = user.deskripsi ?? '';
                    // document.getElementById("img").value = user.img ?? '';

                    // Set action form sesuai ID
                    document.getElementById("form-edit").action =
                        `/admin/updateVoluntter/${user.id}`;

                    // Tampilkan modal
                    document.getElementById("lihat-user-modal").classList.remove("hidden");

                } catch (error) {
                    console.error(error);
                    alert("Gagal memuat Data Buku.");
                }
            });
        });

        /*=========== 2. TOMBOL CLOSE ===========*/
        document.getElementById("close-lihat-modal").addEventListener("click", () => {
            document.getElementById("lihat-user-modal").classList.add("hidden");
        });

        /*=========== 3. KLIK OVERLAY UNTUK MENUTUP ===========*/
        document.getElementById("lihat-user-modal").addEventListener("click", (e) => {
            if (e.target === e.currentTarget) {
                e.currentTarget.classList.add("hidden");
            }
        });
    });
</script>

{{-- =========================  MODAL EDIT BUKU  ========================= --}}
<div id="edit-user-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
    <div class="relative w-full max-w-2xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

            {{-- ---------- Header ---------- --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">Edit Buku</h3>
                <button type="button" id="close-edit-modal"
                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto
                               dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            {{-- ---------- Body ---------- --}}
            <form id="form-edit" action="#" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">

                <div class="grid grid-cols-6 gap-6">
                    {{-- Kategori --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="id_kategori"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                        <select id="id_kategori" name="id_kategori" required
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                       focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Judul --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-judul"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                        <input type="text" id="edit-judul" name="judul" required
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Penulis --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="edit-penulis"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penulis</label>
                        <input type="text" id="edit-penulis" name="penulis" required
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Penerbit --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="penerbit"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" required
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Tahun terbit --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tahun_terbit"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun terbit</label>
                        <input type="date" id="tahun_terbit" name="tahun_terbit" required
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Tipe --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tipe"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                        <input type="text" id="tipe" name="tipe" readonly
                               class="bg-gray-100 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Stok (fisik) --}}
                    <div id="stok_section" class="col-span-6 sm:col-span-3 hidden">
                        <label for="stok"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                        <input type="number" id="stok" name="stok" 
                               class="bg-gray-100 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- File URL (digital) --}}
                    <div id="file_url_section" class="col-span-6 sm:col-span-3 hidden">
                        <label for="file_url"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File URL</label>
                        <input type="file" id="file_url" name="file_url" readonly
                               class="bg-gray-100 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Harga --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="harga"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                        <input type="text" id="harga" name="harga" required
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-span-6">
                        <label for="deskripsi"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" required
                                  class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                         focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                         dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    {{-- Gambar --}}
                    <div class="col-span-6">
                        <label for="img"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar (opsional)</label>
                        <input type="file" id="img" name="img"
                               class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                      block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                {{-- ---------- Footer ---------- --}}
                <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                    <button type="submit"
                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300
                                   font-medium rounded-lg text-sm px-5 py-2.5
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

    /*=========== 1. EDIT BUTTON ===========*/
    document.querySelectorAll(".edit-button").forEach(btn => {
        btn.addEventListener("click", async () => {
            const url = btn.dataset.url;

            try {
                const res  = await fetch(url);
                const user = await res.json();
                if (!user || !user.id) throw new Error("Data buku tidak ditemukan.");

                /*—— isi field dasar ——*/
                document.getElementById("edit-id").value       = user.id ?? '';
                document.getElementById("id_kategori").value   = user.id_kategori ?? '';
                document.getElementById("edit-judul").value    = user.judul ?? '';
                document.getElementById("edit-penulis").value  = user.penulis ?? '';
                document.getElementById("penerbit").value      = user.penerbit ?? '';
                document.getElementById("tahun_terbit").value  = user.tahun_terbit ?? '';
                document.getElementById("tipe").value          = user.tipe ?? '';
                document.getElementById("harga").value         = user.harga ?? '';
                document.getElementById("deskripsi").value     = user.deskripsi ?? '';

                /*—— tampilkan stok / file_url sesuai tipe ——*/
                const stokSec  = document.getElementById("stok_section");
                const fileSec  = document.getElementById("file_url_section");

                if (user.tipe === 'fisik') {
                    stokSec.classList.remove('hidden');
                    fileSec.classList.add('hidden');
                    document.getElementById("stok").value      = user.fisik?.stok ?? '';
                    // document.getElementById("file_url").value  = '';
                } else if (user.tipe === 'digital') {
                    fileSec.classList.remove('hidden');
                    stokSec.classList.add('hidden');
                    // document.getElementById("file_url").value  = user.digital?.file_url ?? '';
                    document.getElementById("stok").value      = '';
                } else {
                    stokSec.classList.add('hidden');
                    fileSec.classList.add('hidden');
                    document.getElementById("stok").value      = '';
                    // document.getElementById("file_url").value  = '';
                }

                /*—— set action form ——*/
                document.getElementById("form-edit").action = `/admin/updateBuku/${user.id}`;

                /*—— tampilkan modal ——*/
                document.getElementById("edit-user-modal").classList.remove("hidden");

            } catch (err) {
                console.error(err);
                alert("Gagal memuat data buku.");
            }
        });
    });

    /*=========== 2. CLOSE BUTTON ===========*/
    document.getElementById("close-edit-modal").addEventListener("click", () => {
        document.getElementById("edit-user-modal").classList.add("hidden");
    });

    /*=========== 3. CLICK OVERLAY UNTUK MENUTUP ===========*/
    document.getElementById("edit-user-modal").addEventListener("click", (e) => {
        if (e.target === e.currentTarget) {
            e.currentTarget.classList.add("hidden");
        }
    });
});
</script>

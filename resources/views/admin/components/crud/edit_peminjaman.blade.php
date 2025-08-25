{{-- =========================  MODAL EDIT PENGUMUMAN  ========================= --}}
<div id="edit-user-modal"
    class="{{ isset($peminjaman) ? 'fixed' : 'hidden' }} inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
    <div class="relative w-full max-w-2xl p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

            {{-- Modal Header --}}
            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold dark:text-white">Edit Peminjaman</h3>
                <a href=""
                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            {{-- Modal Body --}}
            <form action="  " method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $peminjaman->id }}">

                <div class="grid grid-cols-6 gap-6">
                    {{-- Anggota --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="id_anggota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Anggota</label>
                        <input type="text" id="id_anggota" name="id_anggota"
                            value="{{ $peminjaman->anggota->nama ?? '' }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Buku --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="id_buku" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Buku</label>
                        <input type="text" id="id_buku" name="id_buku"
                            value="{{ $peminjaman->buku->judul ?? '' }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_pinjam" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pinjam</label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam"
                            value="{{ $peminjaman->tanggal_pinjam }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Tanggal Ambil --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_ambil" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Ambil</label>
                        <input type="date" id="tanggal_ambil" name="tanggal_ambil"
                            value="{{ $peminjaman->tanggal_ambil }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Tanggal Pengembalian --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="tanggal_pengembalian" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pengembalian</label>
                        <input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian"
                            value="{{ $peminjaman->tanggal_pengembalian }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>

                    {{-- Status Pengembalian --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="status_pengembalian" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status Pengembalian</label>
                        <select id="status_pengembalian" name="status_pengembalian" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">-- Pilih Status --</option>
                            @foreach (['Belum Diambil', 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Rusak', 'Hilang'] as $status)
                                <option value="{{ $status }}" {{ $peminjaman->status_pengembalian === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Denda --}}
                    <div class="col-span-6 sm:col-span-3">
                        <label for="denda" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Denda</label>
                        <input type="text" id="denda" name="denda"
                            value="{{ $peminjaman->denda }}" required
                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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
                    const peminjaman = await res.json();
                    if (!peminjaman || !peminjaman.id) throw new Error(
                        "Data tidak ditemukan.");

                    // Isi field form
                    document.getElementById("edit-id").value = peminjaman.id ?? '';
                    // Asumsikan 'peminjaman' adalah objek JSON dari server:
                    document.getElementById("edit-id_anggota").value = peminjaman
                        .id_anggota ?? '';
                    document.getElementById("edit-id_buku").value = peminjaman.id_buku ??
                    '';
                    const tanggal_pinjam = peminjaman.tanggal_pinjam?.split("T")[0] ?? '';
                    document.getElementById("edit-tanggal_pinjam").value = tanggal_pinjam;
                    const tanggal_ambil = peminjaman.tanggal_ambil?.split("T")[0] ?? '';
                    document.getElementById("edit-tanggal_ambil").value = tanggal_ambil;
                    const tanggal_pengembalian = peminjaman.tanggal_pengembalian?.split(
                        "T")[0] ?? '';
                    document.getElementById("edit-tanggal_pengembalian").value =
                        tanggal_pengembalian;
                    document.getElementById("edit-status_pengembalian").value = peminjaman
                        .status_pengembalian ?? '';
                    document.getElementById("edit-denda").value = peminjaman.denda ?? '';
                    // document.getElementById("edit-isi").value = peminjaman.isi ?? '';

                    // Set action form
                    document.getElementById("form-edit-peminjaman").action =
                        `/admin/updatePeminjaman/${peminjaman.id}`;

                    // Tampilkan modal
                    document.getElementById("edit-user-modal").classList.remove("hidden");

                } catch (error) {
                    console.error(error);
                    alert("Gagal memuat data peminjaman.");
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

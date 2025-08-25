@if ($bukus->count())
    @foreach ($bukus as $buku)
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full"
                    src="{{ $buku->img
                        ? 'https://bubjbpluqoznbyjmsefs.supabase.co/storage/v1/object/public/my-files/' . $buku->img
                        : asset('storage/buku/default.png') }}"
                    alt="user photo">

                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $buku->judul }}</div>
            </td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $buku->kategori->nama }}</td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $buku->penulis }}</td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $buku->penerbit }}</td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $buku->tahun_terbit }}</td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $buku->tipe }}</td>

            <td class="p-4 space-x-2 whitespace-nowrap">
                @if (auth()->user()->role === 'admin')
                    <button type="button" data-id="{{ $buku->id }}"
                        data-url="{{ route('admin.findBukuId', $buku->id) }}"
                        class="edit-button inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                            </path>
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Edit
                    </button>

                    <button type="button" data-modal-target="delete-user-modal-{{ $buku->id }}"
                        data-modal-toggle="delete-user-modal-{{ $buku->id }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Hapus
                    </button>
                    <!-- Modal Konfirmasi -->
                    <div id="delete-user-modal-{{ $buku->id }}" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                        <div class="relative w-full h-full max-w-md md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-6 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Yakin ingin menghapus buku <b>{{ $buku->nama }}</b>?
                                    </h3>
                                    <form method="POST" action="{{ route('admin.deleteBuku', $buku->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 mr-2">
                                            Ya, Hapus
                                        </button>
                                        <button type="button" data-modal-hide="delete-user-modal-{{ $buku->id }}"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                                            Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif (auth()->user()->role === 'voluntter' && $buku->tipe === 'fisik' && $buku->fisik)
                    <button type="button" data-modal-target="tambah-stok-modal-{{ $buku->id }}"
                        data-modal-toggle="tambah-stok-modal-{{ $buku->id }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5h6M9 3h6a2 2 0 012 2v0a2 2 0 01-2 2H9a2 2 0 01-2-2v0a2 2 0 012-2zM4 7h16M4 11h16M4 15h10" />
                        </svg>
                        Stok Opname
                    </button>

                    <div id="tambah-stok-modal-{{ $buku->id }}" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-md md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-6 text-center">
                                    <svg class="mx-auto mb-4 text-green-500 w-12 h-12" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m2-4h-4.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 009.586 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2z" />
                                    </svg>

                                    <h3 class="mb-5 text-lg font-normal text-gray-700 dark:text-white">
                                        Tambah stok untuk buku <b>{{ $buku->judul }}</b>
                                    </h3>

                                    <form method="POST" action="{{ route('updateStokBuku', $buku->id) }}">
                                        @csrf

                                        <div class="mb-4">
                                            <label for="fisik" class="block text-sm font-medium text-gray-700 mb-1">
                                                Jumlah Stok Buku Fisik
                                            </label>

                                            <div class="flex items-center gap-2">
                                                <input type="number" name="fisik" id="fisik"
                                                    value="{{ $buku->fisik->stok }}" min="0"
                                                    class="w-full border border-gray-300 rounded-md p-2 text-center shadow-sm focus:ring focus:ring-primary-200 focus:border-primary-400 transition"
                                                    required>
                                                <span class="text-sm text-gray-500 whitespace-nowrap">
                                                    (Sebelumnya: {{ $buku->fisik->stok }})
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex justify-center gap-3">
                                            <button type="submit"
                                                class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                                Simpan
                                            </button>
                                            <button type="button"
                                                data-modal-hide="tambah-stok-modal-{{ $buku->id }}"
                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm px-5 py-2.5">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="100%" class="py-4">
            <div class="flex justify-center">
                {{ $bukus->links() }}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="4" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
    </tr>
@endif

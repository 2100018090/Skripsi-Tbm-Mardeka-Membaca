@if ($anggotas->count())
    @foreach ($anggotas as $anggota)
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full"
                    src="{{ $anggota->img
                        ? rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $anggota->img
                        : asset('storage/pengumuman/default.png') }}"
                    alt="user photo">

                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $anggota->nama }}</div>
                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        {{ $anggota->akun?->email ?? '-' }}</div>
                </div>
            </td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $anggota['alamat'] }}</td>
            <td class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                {{ $anggota['notlp'] }}</td>

            <td class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center">
                    @if ($anggota['status'] === 'active')
                        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
                    @elseif ($anggota['status'] === 'offline')
                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                    @endif
                    {{ $anggota['status'] }}
                </div>
            </td>
            @if (auth()->user()->role === 'admin')
                <td class="p-4 space-x-2 whitespace-nowrap">
                    <!-- Tombol Buka Modal -->
                    <button type="button" data-modal-target="tolak-user-modal-{{ $anggota->id }}"
                        data-modal-toggle="tolak-user-modal-{{ $anggota->id }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <!-- Ikon silang -->
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0
            111.414 1.414L11.414 10l4.293 4.293a1 1 0
            01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0
            01-1.414-1.414L8.586 10 4.293 5.707a1 1 0
            010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Tolak
                    </button>

                    <div id="tolak-user-modal-{{ $anggota->id }}" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                        <div class="relative w-full h-full max-w-md md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-6 text-center">
                                    <!-- Ikon silang -->
                                    <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Yakin ingin menolak menjadi volunteer ?
                                    </h3>
                                    <form method="POST"
                                        action="{{ route('admin.tolakAnggota', $anggota->akun->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 mr-2">
                                            Ya, Tolak
                                        </button>
                                        <button type="button" data-modal-hide="tolak-user-modal-{{ $anggota->id }}"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                                            Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" data-modal-target="terima-user-modal-{{ $anggota->id }}"
                        data-modal-toggle="terima-user-modal-{{ $anggota->id }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <!-- Ikon centang untuk Terima -->
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414L8.5 14.914l-4.207-4.207a1 1 0 011.414-1.414L8.5 12.086l7.793-7.793a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Terima
                    </button>
                    <!-- Modal Konfirmasi -->
                    <div id="terima-user-modal-{{ $anggota->id }}" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                        <div class="relative w-full h-full max-w-md md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="p-6 text-center">
                                    <!-- Ikon centang -->
                                    <svg class="mx-auto mb-4 text-green-600 w-12 h-12 dark:text-green-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Yakin ingin menerima menjadi volunteer ?
                                    </h3>
                                    <form method="POST" action="{{ route('admin.setujuAnggota', $anggota->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 mr-2">
                                            Ya, Terima
                                        </button>
                                        <button type="button" data-modal-hide="terima-user-modal-{{ $anggota->id }}"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                                            Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            @endif
        </tr>
    @endforeach

    <div class="mt-4 flex justify-center">
        {{ $anggotas->links() }}
    </div>
@else
    <tr>
        <td colspan="4" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
    </tr>
@endif

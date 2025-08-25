@if ($voluntters->count())
    @foreach ($voluntters as $voluntter)
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                <img class="w-10 h-10 rounded-full"
                    src="{{ $voluntter->img
                        ? rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/' . $voluntter->img
                        : asset('storage/pengumuman/default.png') }}"
                    alt="user photo">

                <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $voluntter->nama }}</div>
                    <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        {{ $voluntter->akun?->email ?? '-' }}</div>
                </div>
            </td>
            <td
                class="max-w-sm p-4 overflow-hidden text-base font-normal text-gray-500 truncate xl:max-w-xs dark:text-gray-400">
                {{ $voluntter['jabatan'] }}</td>
            <td class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center">
                    @if ($voluntter['status'] === 'active')
                        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
                    @elseif ($voluntter['status'] === 'offline')
                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                    @endif
                    {{ $voluntter['status'] }}
                </div>
            </td>
            <td class="p-4 space-x-2 whitespace-nowrap">
                <button type="button" data-modal-target="edit-voluntter-modal-{{ $voluntter->id }}"
                    data-modal-toggle="edit-voluntter-modal-{{ $voluntter->id }}"
                    class="edit-button inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd"
                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                            clip-rule="evenodd" />
                    </svg>
                    Edit
                </button>
                <!-- Modal -->
                <div id="edit-voluntter-modal-{{ $voluntter->id }}" tabindex="-1" aria-hidden="true"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto">
                    <div class="relative w-full max-w-2xl p-4">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">

                            <!-- Modal Header -->
                            <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                                <h3 class="text-xl font-semibold dark:text-white">Edit Voluntter</h3>
                                <button type="button" data-modal-hide="edit-voluntter-modal-{{ $voluntter->id }}"
                                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <form action="{{ route('admin.updateVoluntter', $voluntter->id) }}" method="POST"
                                enctype="multipart/form-data" class="p-6 space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-6 gap-6">
                                    <!-- Email -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="email-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                        <input type="email" name="email" id="email-{{ $voluntter->id }}"
                                            value="{{ $voluntter->akun->email }}" required
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Username -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="username-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                        <input type="text" name="username" id="username-{{ $voluntter->id }}"
                                            value="{{ $voluntter->akun->username }}" required
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Nama -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="nama-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                        <input type="text" name="nama" id="nama-{{ $voluntter->id }}"
                                            value="{{ $voluntter->nama }}" required
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Jabatan -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="jabatan-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                                        <input type="text" name="jabatan" id="jabatan-{{ $voluntter->id }}"
                                            value="{{ $voluntter->jabatan }}" required
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Status -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="status-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                        <input type="text" name="status" id="status-{{ $voluntter->id }}"
                                            value="{{ $voluntter->status }}" required
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>

                                    <!-- Gambar -->
                                    <div class="col-span-6 sm:col-span-3">
                                        <label for="img-{{ $voluntter->id }}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gambar</label>
                                        <input type="file" name="img" id="img-{{ $voluntter->id }}"
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                                    <button type="submit"
                                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        Ubah
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <button type="button" data-modal-target="delete-user-modal-{{ $voluntter->id }}"
                    data-modal-toggle="delete-user-modal-{{ $voluntter->id }}"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Hapus
                </button>
                <!-- Modal Konfirmasi -->
                <div id="delete-user-modal-{{ $voluntter->id }}" tabindex="-1" aria-hidden="true"
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
                                    Yakin ingin menghapus voluntter <b>{{ $voluntter->nama }}</b>?
                                </h3>
                                <form method="POST"
                                    action="{{ route('admin.deleteVoluntter', $voluntter->akun->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 mr-2">
                                        Ya, Hapus
                                    </button>
                                    <button type="button" data-modal-hide="delete-user-modal-{{ $voluntter->id }}"
                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5">
                                        Batal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
    </tr>
@endif

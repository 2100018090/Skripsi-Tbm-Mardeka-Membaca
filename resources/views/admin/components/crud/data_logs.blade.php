@if ($penggunas->count())
    @foreach ($penggunas as $anggota)
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
                {{ $anggota->akun?->role ?? '-' }}</td>
            <td class="p-4 text-base font-normal text-gray-900 whitespace-nowrap dark:text-white">
                <button data-modal-target="log-modal-{{ $anggota->akun->id }}"
                    data-modal-toggle="log-modal-{{ $anggota->akun->id }}"
                    class="bg-blue-100 text-blue-700 hover:bg-blue-200 font-medium rounded px-3 py-1 text-sm">
                    Lihat Log
                </button>

                <div id="log-modal-{{ $anggota->akun->id }}" tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Header -->
                            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Log Aktivitas - {{ $anggota->nama }}
                                </h3>
                                <button type="button" data-modal-hide="log-modal-{{ $anggota->akun->id }}"
                                    class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="p-6 max-h-96 overflow-y-auto">
                                @php
                                    $logs = \App\Models\ActivityLog::where('user_id', $anggota->akun->id)
                                        ->latest()
                                        ->take(20)
                                        ->get();
                                @endphp

                                @if ($logs->count())
                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th class="px-4 py-2">Tanggal</th>
                                                    <th class="px-4 py-2">Aksi</th>
                                                    <th class="px-4 py-2">IP Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($logs as $log)
                                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                        <td class="px-4 py-2">
                                                            {{ $log->created_at->format('d-m-Y H:i') }}</td>
                                                        <td class="px-4 py-2">{{ $log->aksi }}</td>
                                                        <td class="px-4 py-2">{{ $log->ip_address ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-gray-500">Tidak ada log aktivitas.</p>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="log-modal-{{ $anggota->akun->id }}"
                                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 text-center">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach

    <div class="mt-4 flex justify-center">
        {{ $penggunas->links() }}
    </div>
@else
    <tr>
        <td colspan="4" class="text-center text-gray-500">Tidak ada hasil ditemukan.</td>
    </tr>
@endif

@foreach ($peminjaman as $pinjam)
    <!-- Informasi peminjaman seperti nama buku, tanggal, dll -->

    @if ($pinjam->status_pengembalian == 'Kembalikan' && $pinjam->rating == null && auth()->user()->id == $pinjam->user_id)
        <!-- Form rating tampil hanya jika status Sudah Kembali dan belum ada rating -->
        <div class="mt-4 p-4 border rounded bg-gray-50">
            <p class="font-semibold mb-2">Beri rating dan ulasan untuk peminjaman ini:</p>

            <form action="{{ route('anggota.storeRating', ['id' => $pinjam->id]) }}" method="POST" x-data="{ rating: 0 }">
                @csrf

                <div class="flex items-center mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-8 h-8 cursor-pointer text-gray-300 hover:text-yellow-400 transition"
                            :class="{ 'text-yellow-400': rating >= {{ $i }} }"
                            @click="rating = {{ $i }}"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.135 3.487a1 1 0 00.95.69h3.672c.969 0 1.371 1.24.588 1.81l-2.974 2.162a1 1 0 00-.364 1.118l1.135 3.486c.3.922-.755 1.688-1.54 1.118l-2.974-2.161a1 1 0 00-1.175 0L6.207 17.6c-.784.57-1.838-.196-1.539-1.118l1.135-3.486a1 1 0 00-.364-1.118L2.465 9.914c-.783-.57-.38-1.81.588-1.81h3.672a1 1 0 00.95-.69l1.135-3.487z" />
                        </svg>
                    @endfor
                    <input type="hidden" name="rating" x-model="rating">
                </div>

                <label for="ulasan-{{ $pinjam->id }}" class="block text-sm font-medium text-gray-700 mb-1">Ulasan:</label>
                <textarea name="ulasan" id="ulasan-{{ $pinjam->id }}" rows="3"
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tulis ulasan Anda di sini..."></textarea>

                <button type="submit"
                    class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm">
                    Kirim Rating
                </button>
            </form>
        </div>
    @endif
@endforeach

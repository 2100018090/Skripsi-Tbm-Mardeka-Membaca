<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\PengumumanBaru;
use App\Exports\AnggotaExport;
use App\Exports\PeminjamanExport;
use App\Exports\VolunteerExport;
use App\Helpers\LogActivity;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Detailbuku;
use App\Models\Digital;
use App\Models\Fisik;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\Page;
use App\Models\Pembayaran_denda;
use App\Models\Peminjaman;
use App\Models\Pengumuman;
use App\Models\Pesan;
use App\Models\Voluntter;
use App\Services\Akun\AkunService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{

    public function createOnlyAnggota(Request $request)
    {
        // Ambil akun yang sedang login
        $akun = auth()->user();
        if (!$akun) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        // Cek jika sudah ada data anggota untuk akun ini
        if ($akun->anggota) {
            return redirect()->back()->withErrors('Profil anggota sudah pernah dibuat.');
        }

        // Validasi input
        $request->validate([
            'nama'   => 'required|string|max:50',
            'notlp'  => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
            'alamat' => 'required|string|max:50',
            'status' => 'nullable|in:active,offline',
            'img'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'notlp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'img.max'     => 'Ukuran gambar maksimal 2MB.',
            'img.mimes'   => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        // Upload gambar ke Supabase jika ada
        $imgPath = null;
        if ($request->hasFile('img')) {
            $file      = $request->file('img');
            $ext       = $file->getClientOriginalExtension();
            $filename  = 'anggota-' . Str::uuid() . '.' . $ext;
            $filePath  = "anggota/{$filename}";
            $fileBytes = file_get_contents($file->getPathname());

            $url = rtrim(env('SUPABASE_URL'), '/') .
                "/storage/v1/object/" .
                env('SUPABASE_BUCKET') .
                "/{$filePath}";

            $response = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type'  => $file->getMimeType(),
            ])
            ->withBody($fileBytes, $file->getMimeType())
            ->put($url);

            if (!$response->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $response->body()
                ])->withInput();
            }

            $imgPath = $filePath;
        }

        $anggota = Anggota::create([
            'id_akun' => $akun->id,
            'nama'    => $request->nama,
            'alamat'  => $request->alamat,
            'notlp'   => $request->notlp,
            'status'  => 'active',
            'img'     => $imgPath,
        ]);

        ActivityLog::create([
            'user_id'   => $akun->id,
            'aksi'      => 'Buat Profil Anggota',
            'deskripsi' => 'Anggota dengan email ' . $akun->email . ' membuat profil anggota baru dengan nama ' . $anggota->nama,
            'ip_address'=> $request->ip(),
        ]);



        return redirect()->route('anggota.profileAnggota')->with('success', 'Profil anggota berhasil disimpan.');
    }

    public function uploadImg(Request $request)
    {
        $user = $request->user() ?? session('user');

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User tidak ditemukan']);
        }

        $admin = Admin::where('id_akun', $user->id)->first();

        if (!$admin) {
            return redirect()->back()->withErrors(['error' => 'Admin tidak ditemukan']);
        }

        if ($request->hasFile('img')) {
            // Log path lama
            Log::info('Path gambar lama:', ['path' => $admin->img]);

            if ($admin->img && Storage::disk('public')->exists($admin->img)) {
                Storage::disk('public')->delete($admin->img);
                Log::info('Gambar lama berhasil dihapus');
            } else {
                Log::warning('Gambar lama tidak ditemukan di storage');
            }

            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admin', $filename, 'public');

            $admin->img = $path;
            $admin->save();

            return redirect()->route('admin.setting')->with('success', 'Gambar berhasil diupload');
        }

        return redirect()->back()->withErrors(['error' => 'Tidak ada file gambar yang diupload']);
    }
  
    public function createPengumuman(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'judul'   => 'required|string|max:50',
            'tanggal' => 'required|date',
            'isi'     => 'required|string',
            'img'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $filePath = null;

        // 2. Upload ke Supabase jika ada gambar
        if ($request->hasFile('img')) {
            $file      = $request->file('img');
            $ext       = $file->getClientOriginalExtension();
            $filename  = 'pengumuman-' . Str::uuid() . '.' . $ext;
            $filePath  = "pengumuman/{$filename}";
            $fileBytes = file_get_contents($file->getPathname());

            $url = rtrim(env('SUPABASE_URL'), '/') .
                "/storage/v1/object/" .
                env('SUPABASE_BUCKET') .
                "/{$filePath}";

            $resp = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type'  => $file->getMimeType(),
            ])->withBody($fileBytes, $file->getMimeType())
            ->put($url);

            if (!$resp->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $resp->body()
                ])->withInput();
            }
        }

        // 3. Simpan pengumuman ke database
        $pengumuman = Pengumuman::create([
            'judul'   => $validated['judul'],
            'tanggal' => $validated['tanggal'],
            'isi'     => $validated['isi'],
            'img'     => $filePath,
        ]);

        // 4. Ambil semua anggota
        $anggotaList = Anggota::with('akun')->get();

        foreach ($anggotaList as $anggota) {
            // 5. Simpan notifikasi ke database
            Notifikasi::create([
                'id_anggota'    => $anggota->id,
                'judul'         => 'Pengumuman Baru',
                'pesan'         => 'Pengumuman "' . $pengumuman->judul . '" telah diterbitkan. Silakan cek informasinya.',
                'status_dibaca' => false,
            ]);

            // 6. Kirim email jika anggota punya akun dan email
            if ($anggota->akun && $anggota->akun->email) {
                Mail::send('emails.pengumuman', [
                    'judul'   => $pengumuman->judul,
                    'isi'     => $pengumuman->isi,
                    'tanggal' => \Carbon\Carbon::parse($pengumuman->tanggal)->format('d M Y'),
                ], function ($message) use ($anggota, $pengumuman) {
                    $message->to($anggota->akun->email)
                            ->subject("📢 Pengumuman Baru: {$pengumuman->judul}");
                });
            }
        }

        // 8. Catat log aktivitas admin
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Tambah Pengumuman',
            'deskripsi' => 'Admin menambahkan pengumuman: ' . $pengumuman->judul,
            'ip_address'=> $request->ip(),
        ]);

            // 7. Redirect sukses
            return redirect()->route('admin.pengumuman')
                            ->with('success', 'Pengumuman berhasil ditambahkan & email terkirim!');
    }

    public function findPengumumanId($id)
    {
        $pengumuman = Pengumuman::find($id);   // ← tanpa with('akun')

        if (!$pengumuman) {
            return response()->json(['message' => 'Pengumuman tidak ditemukan'], 404);
        }
        return response()->json($pengumuman);
    }

    public function updatePengumuman(Request $request, $id)
    {
        $validated = $request->validate([
            'judul'   => 'required|string|max:50',
            'tanggal' => 'required|date',
            'isi'     => 'required|string',
            'img'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $oldImagePath = $pengumuman->img;
        $newImagePath = $oldImagePath;

        if ($request->hasFile('img')) {
            if ($oldImagePath) {
                $deleteUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/' .
                            env('SUPABASE_BUCKET') . '/' . $oldImagePath;

                Http::withHeaders([
                    'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                ])->delete($deleteUrl);
            }

            $file = $request->file('img');
            $filename = 'pengumuman-' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $newImagePath = "pengumuman/{$filename}";
            $uploadUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/' .
                        env('SUPABASE_BUCKET') . '/' . $newImagePath;

            $uploadResp = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type'  => $file->getMimeType(),
            ])->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
            ->put($uploadUrl);

            if (!$uploadResp->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $uploadResp->body()
                ])->withInput();
            }
        }

        $pengumuman->update([
            'judul'   => $validated['judul'],
            'tanggal' => $validated['tanggal'],
            'isi'     => $validated['isi'],
            'img'     => $newImagePath,
        ]);

        // ✅ Catat log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Update Pengumuman',
            'deskripsi' => 'Admin memperbarui pengumuman: ' . $validated['judul'],
            'ip_address'=> $request->ip(),
        ]);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function deletePengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        // Simpan dulu judulnya untuk log
        $judul = $pengumuman->judul;

        // Hapus gambar dari storage jika ada
        if ($pengumuman->img && Storage::disk('public')->exists($pengumuman->img)) {
            Storage::disk('public')->delete($pengumuman->img);
        }

        // Hapus data pengumuman dari database
        $pengumuman->delete();

        // ✅ Catat log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Hapus Pengumuman',
            'deskripsi' => 'Admin menghapus pengumuman: ' . $judul,
            'ip_address'=> request()->ip(),
        ]);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function cariPengumuman(Request $request)
    {
        // 1. Ambil keyword pencarian
        $keyword = trim($request->input('keyword', ''));

        // 2. Validasi login dan role admin
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        $user = Auth::user();
        abort_if($user->role !== 'admin', 403, 'Hanya admin yang dapat mengakses.');

        // 3. Query dasar pengumuman
        $query = Pengumuman::query();

        // 4. Tambahkan filter pencarian jika ada keyword
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'LIKE', "%{$keyword}%")
                ->orWhere('isi', 'LIKE', "%{$keyword}%");
            });
        }

        // 5. Gunakan paginate agar bisa pakai ->links()
        $pengumumans = $query->paginate(10)->appends(['keyword' => $keyword]);

        // 6. Response untuk AJAX (misalnya live search)
        if ($request->ajax()) {
            return view('admin.components.pengumuman', compact('pengumumans'))->render();
        }

        // 7. Response untuk full page
        return view('admin.components.pengumuman', [
            'title'       => 'Data Pengumuman',
            'pengumumans' => $pengumumans,
            'keyword'     => $keyword,
        ]);
    }

    public function createKategori(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:20|unique:kategoris,nama',
        ]);

        // Simpan ke database
        $kategori = Kategori::create([
            'nama' => $request->nama,
        ]);

        // ✅ Catat log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Tambah Kategori',
            'deskripsi' => 'Admin menambahkan kategori baru: ' . $kategori->nama,
            'ip_address'=> $request->ip(),
        ]);

        // Redirect atau response
        return redirect()->route('admin.layoutsbukufisik')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function pinjamLangsung(Request $request)
    {
        $sessionUser = Auth::user();

        // Cek login dan role
        if (!$sessionUser || $sessionUser->role !== 'anggota') {
            return redirect()->back()->with('error','Silakan login sebagai anggota.');
        }

        $anggota = Anggota::where('id_akun', $sessionUser->id)->first();
        if (!$anggota) {
            return redirect()->back()->with('error','Data anggota tidak ditemukan.');
        }

        // Cek kelengkapan profil
        if (empty($anggota->alamat) || empty($anggota->notlp)) {
            return redirect()->back()->with('error','Profil belum lengkap. Lengkapi alamat & nomor telepon.');
        }

        // ✅ Cek denda belum dibayar
        $adaDendaBelumBayar = Peminjaman::where('id_anggota', $anggota->id)
            ->where('denda', '>', 0)
            ->where(function ($query) {
                $query->whereDoesntHave('pembayaranDenda')
                    ->orWhereHas('pembayaranDenda', function ($q) {
                        $q->where('status_pembayaran', '!=', 'Sudah Dibayar');
                    });
            })
            ->exists();

        if ($adaDendaBelumBayar) {
            return redirect()->back()->with('error','Anda masih memiliki denda yang belum dibayar.');
        }

        // ✅ Cek belum diberi rating
        $adaBelumRating = Peminjaman::where('id_anggota', $anggota->id)
            ->where('status_pengembalian', 'Kembalikan')
            ->whereNull('rating')
            ->exists();

        if ($adaBelumRating) {
            return redirect()->back()->with('error', 'Anda masih memiliki peminjaman yang belum diberi rating.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_buku' => ['required', 'exists:bukus,id'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_pengembalian' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
        ], [
            'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian harus setelah atau sama dengan tanggal pinjam.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
            $tanggalPengembalian = Carbon::parse($request->tanggal_pengembalian);

            if ($tanggalPengembalian->gt($tanggalPinjam->copy()->addDays(5))) {
                $validator->errors()->add('tanggal_pengembalian', 'Maksimal peminjaman adalah 5 hari.');
            }
        });

        if ($validator->fails()) {
            $errorMessage = implode('<br>', $validator->errors()->all());
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        $id_buku = $request->id_buku;
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalKembali = Carbon::parse($request->tanggal_pengembalian);

        // Cek peminjaman aktif
        $jumlahAktif = Peminjaman::where('id_anggota', $anggota->id)
            ->whereIn('status_pengembalian', ['Dipinjam', 'Belum Diambil'])
            ->count();

        if ($jumlahAktif >= 2) {
            return redirect()->back()->with('error', 'Maksimal 2 buku dapat dipinjam sekaligus.');
        }

        // Cek buku sudah dipinjam atau belum
        $sudahPinjamBukuIni = Peminjaman::where('id_anggota', $anggota->id)
            ->where('id_buku', $id_buku)
            ->whereIn('status_pengembalian', ['Dipinjam', 'Belum Diambil'])
            ->exists();

        if ($sudahPinjamBukuIni) {
            return redirect()->back()->with('error', 'Buku ini masih dalam peminjaman oleh Anda.');
        }

        // ✅ Transaksi aman
        DB::beginTransaction();

        try {
            $stok = Fisik::lockForUpdate()->where('id_buku', $id_buku)->first();

            if (!$stok || $stok->stok < 1) {
                DB::rollBack();
                return redirect()->back()->with('error','Stok buku tidak tersedia.');
            }

            $stok->decrement('stok');

            $peminjaman = Peminjaman::create([
                'id_anggota' => $anggota->id,
                'id_buku' => $id_buku,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_ambil' => null,
                'tanggal_pengembalian' => $tanggalKembali,
                'status_pengembalian' => 'Belum Diambil',
                'denda' => 0,
            ]);

            LogActivity::add('Peminjaman Buku', 'Anggota dengan email ' . $sessionUser->email . ' meminjam buku dengan ID ' . $id_buku . ' pada ' . now()->format('d-m-Y H:i'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error','Terjadi kesalahan saat memproses peminjaman.');
        }

        return redirect()->route('anggota.layoutsRiwayat')->with('success', 'Berhasil meminjam buku.');
    }

    public function Buku($idBuku)
    {
        $book = Peminjaman::where('id_buku', $idBuku)
            ->with('buku')
            ->first();

        if (!$book) {
            return response()->json(['message' => 'Peminjaman dengan id_buku ini tidak ditemukan'], 404);
        }

        if (!$book->buku || strtolower($book->buku->tipe) !== 'fisik') {
            return response()->json(['message' => 'Buku tidak ditemukan atau bukan tipe fisik'], 404);
        }

        return response()->json($book);
    }

    public function createBuku(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'judul' => 'required|string|max:70',
                'id_kategori' => 'required|exists:kategoris,id',
                'penulis' => 'required|string|max:30',
                'penerbit' => 'required|string|max:30',
                'tahun_terbit' => 'required|date',
                'isbn' => 'required|string|max:20',
                'tipe' => 'required|in:digital,fisik',
                'harga' => 'required|string|max:10',
                'deskripsi' => 'required|string',
                'img' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
                'file_url' => 'required_if:tipe,digital|mimes:pdf|max:10000',
                'jumlahHalaman' => 'required_if:tipe,digital|integer|min:1',
                'stok' => 'required_if:tipe,fisik|integer|min:0|nullable',
            ]);

            // Upload gambar ke Supabase - folder cover/
            $fileImg = $request->file('img');
            $extImg = $fileImg->getClientOriginalExtension();
            $filenameImg = Str::slug($validated['judul']) . '-' . Str::uuid() . '.' . $extImg;
            $filePathImg = "cover/{$filenameImg}";
            $fileBytesImg = file_get_contents($fileImg->getPathname());

            $urlImg = rtrim(env('SUPABASE_URL'), '/') .
                "/storage/v1/object/" .
                env('SUPABASE_BUCKET') .
                "/{$filePathImg}";

            $respImg = Http::withHeaders([
                'apikey' => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type' => $fileImg->getMimeType(),
            ])->withBody($fileBytesImg, $fileImg->getMimeType())
            ->put($urlImg);

            if (!$respImg->successful()) {
                return back()->withErrors(['img' => 'Upload gambar ke Supabase gagal: ' . $respImg->body()])
                            ->withInput();
            }

            // Simpan data buku
            $buku = Buku::create([
                'judul' => $validated['judul'],
                'id_kategori' => $validated['id_kategori'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahun_terbit' => $validated['tahun_terbit'],
                'isbn' => $validated['isbn'],
                'tipe' => $validated['tipe'],
                'harga' => $validated['harga'],
                'deskripsi' => $validated['deskripsi'],
                'img' => $filePathImg,
            ]);

            // Jika digital, upload file PDF & simpan ke digitals
            if ($validated['tipe'] == 'digital') {
                $filePdf = $request->file('file_url');
                $extPdf = $filePdf->getClientOriginalExtension();
                $filenamePdf = Str::slug($validated['judul']) . '-' . Str::uuid() . '.' . $extPdf;
                $filePathPdf = "pdf/{$filenamePdf}";
                $fileBytesPdf = file_get_contents($filePdf->getPathname());

                $urlPdf = rtrim(env('SUPABASE_URL'), '/') .
                    "/storage/v1/object/" .
                    env('SUPABASE_BUCKET') .
                    "/{$filePathPdf}";

                $respPdf = Http::withHeaders([
                    'apikey' => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type' => $filePdf->getMimeType(),
                ])->withBody($fileBytesPdf, $filePdf->getMimeType())
                ->put($urlPdf);

                if (!$respPdf->successful()) {
                    return back()->withErrors(['file_url' => 'Upload file PDF ke Supabase gagal: ' . $respPdf->body()])
                                ->withInput();
                }

                Digital::create([
                    'id_buku' => $buku->id,
                    'file_url' => $filePathPdf,
                    'jumlahHalaman' => $validated['jumlahHalaman'],
                ]);
            }

            // Jika fisik, simpan stok
            elseif ($validated['tipe'] == 'fisik') {
                $fisik = Fisik::create([
                    'id_buku' => $buku->id,
                    'stok' => $validated['stok'],
                ]);

                $kodePrefix = strtoupper(substr(Str::slug($validated['judul']), 0, 3));

                for ($i = 1; $i <= $validated['stok']; $i++) {
                    $kode = $kodePrefix . str_pad($i, 3, '0', STR_PAD_LEFT);

                    Detailbuku::create([
                        'id_buku' => $buku->id,
                        'id_fisik' => $fisik->id,
                        'kode' => $kode,
                        'dipinjam' => false,
                    ]);
                }
            }

            // ✅ Kirim notifikasi ke semua anggota
            $anggotaList = \App\Models\Anggota::all();
            foreach ($anggotaList as $anggota) {
                \App\Models\Notifikasi::create([
                    'id_anggota'    => $anggota->id,
                    'judul'         => 'Buku Baru Telah Ditambahkan',
                    'pesan'         => 'Buku baru "' . $buku->judul . '" telah tersedia di perpustakaan.',
                    'status_dibaca' => false,
                ]);
            }

            return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan buku: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan buku');
        }
    }

    private function convertPdfToPngWithGhostscript(string $pdfFullPath, int $idBuku): void
    {
        try {
            $outputDir = storage_path("app/public/pdf_images/123/{$idBuku}");

            // Buat folder jika belum ada
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            // Path ke Ghostscript (pastikan benar)
            $gsPath = '"C:\\Program Files\\gs\\gs10.05.1\\bin\\gswin64c.exe"';

            // Pola nama file output, JANGAN pakai escapeshellarg agar %03d tidak rusak
            $outputPattern = $outputDir . DIRECTORY_SEPARATOR . 'page_%03d.png';

            // Escape hanya file PDF-nya
            $escapedPdfPath = escapeshellarg($pdfFullPath);

            // Susun command Ghostscript
            $cmd = $gsPath
                . ' -dNOPAUSE -dBATCH -sDEVICE=png16m -r150 '
                . '-sOutputFile="' . $outputPattern . '" '
                . $escapedPdfPath;

            Log::info('Output pattern:', ['pattern' => $outputPattern]);
            Log::info('Menjalankan perintah Ghostscript:', ['command' => $cmd]);

            exec($cmd, $output, $returnVar);

            if ($returnVar !== 0) {
                Log::error('Gagal konversi PDF ke PNG menggunakan Ghostscript', [
                    'buku_id' => $idBuku,
                    'command' => $cmd,
                    'output' => $output,
                    'returnVar' => $returnVar,
                ]);
            } else {
                $files = glob($outputDir . DIRECTORY_SEPARATOR . 'page_*.png');

                Log::info('Berhasil mengkonversi PDF ke gambar PNG', [
                    'buku_id' => $idBuku,
                    'output_dir' => $outputDir,
                    'jumlah_file' => count($files),
                    'files' => $files,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Terjadi exception saat konversi PDF', [
                'error' => $e->getMessage(),
                'buku_id' => $idBuku,
            ]);
        }
    }

    public function bacaByDigital($id)
    {
        $digital = Digital::with('buku')->find($id); // ambil dari tabel digitals

        if (!$digital) {
            return back()->with('error', 'Data buku digital tidak ditemukan.');
        }

        $jumlahHalaman = $digital->jumlahHalaman;

        if (!$jumlahHalaman || $jumlahHalaman < 1) {
            return back()->with('error', 'Jumlah halaman belum di-set.');
        }

        $baseUrl = rtrim(env('SUPABASE_URL'), '/') .
            '/storage/v1/object/public/' .
            env('SUPABASE_BUCKET') .
            "/buku_digital/{$digital->id}";

        $halaman = [];
        for ($i = 1; $i <= $jumlahHalaman; $i++) {
            $pageNum = str_pad($i, 3, '0', STR_PAD_LEFT);
            $halaman[] = "{$baseUrl}/page_{$pageNum}.png";
        }

        $buku = $digital->buku;
        $title = 'Baca Buku - ' . $buku->judul;

        // ✅ Catat log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Baca Buku Digital',
            'deskripsi' => 'Pengguna membaca buku digital: ' . $buku->judul,
            'ip_address'=> request()->ip(),
        ]);

        return view('user.components.tampilan_buku', compact('buku', 'halaman', 'title'));
    }

    public function prosesKonfirmasi(Request $request, $id) 
    {
        // Validasi input
        $request->validate([
            'id_detail_buku' => 'required|exists:detailbukus,id',
        ]);

        // Ambil data peminjaman
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        // Ambil detail buku yang dipilih dan tandai dipinjam
        $detailBuku = Detailbuku::findOrFail($request->id_detail_buku);
        $detailBuku->dipinjam = true;
        $detailBuku->save();

        // Ambil user login
        $user = Auth::user();

        // Pastikan hanya voluntter yang bisa konfirmasi
        if (!$user || $user->role !== 'voluntter') {
            return redirect()->back()->withErrors('Hanya voluntter yang bisa mengkonfirmasi.');
        }

        // Ambil data voluntter berdasarkan akun login
        $voluntter = Voluntter::where('id_akun', $user->id)->first();
        if (!$voluntter) {
            return redirect()->back()->withErrors('Akun ini belum memiliki data volunteer.');
        }

        // Update peminjaman
        $peminjaman->tanggal_ambil = Carbon::now();
        $peminjaman->status_pengembalian = 'Dipinjam';
        $peminjaman->id_detail_buku = $detailBuku->id;
        $peminjaman->id_voluntter_pinjam = $voluntter->id; // ✅ ini yang benar
        $peminjaman->save();

        // Kirim notifikasi ke anggota
        Notifikasi::create([
            'id_anggota' => $peminjaman->id_anggota,
            'judul'      => 'Peminjaman Berhasil',
            'pesan'      => 'Buku "' . $peminjaman->buku->judul . '" berhasil dipinjam dengan kode: ' . $detailBuku->kode,
        ]);

        // Tambahkan log aktivitas
        LogActivity::add(
            'Konfirmasi Peminjaman',
            'Voluntter dengan email ' . $user->email . 
            ' mengonfirmasi peminjaman buku "' . $peminjaman->buku->judul . 
            '" (kode: ' . $detailBuku->kode . ') pada ' . now()->format('d-m-Y H:i')
        );

        return redirect()->back()->with('success', 'Peminjaman berhasil dikonfirmasi oleh voluntter.');
    }

    public function getChartData()
    {
    // mapping index → nama hari (versi id-ID)
        $hari = [
            1 => 'Minggu',   // MySQL DAYOFWEEK: 1 = Minggu
            2 => 'Senin',
            3 => 'Selasa',
            4 => 'Rabu',
            5 => 'Kamis',
            6 => 'Jumat',
            7 => 'Sabtu',
        ];

        // ambil 7 hari terakhir
        $raw = DB::table('peminjamans')
            ->selectRaw('DAYOFWEEK(tanggal_pinjam) AS dow, COUNT(*) AS jumlah')
            ->whereBetween('tanggal_pinjam', [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])
            ->groupBy('dow')
            ->get()
            ->pluck('jumlah', 'dow')   // { dow => jumlah }
            ->toArray();

        // susun data lengkap Senin–Minggu (atau Minggu–Sabtu, bebas urutan)
        $labels = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $data   = [];

        foreach ([2,3,4,5,6,7,1] as $idx) {      // urut Senin–Minggu
            $data[] = $raw[$idx] ?? 0;          // 0 jika tidak ada pinjaman
        }

        return response()->json(compact('labels','data'));
    }

    public function kirimPesan(Request $request)
    {
        $pengirim = auth()->user();
        $isi = $request->input('isi');

        if ($pengirim->role === 'anggota') {
            // Anggota kirim ke semua volunteer
            Pesan::create([
                'pengirim_id' => $pengirim->id,
                'isi' => $isi
            ]);

            broadcast(new MessageSent($isi, null))->toOthers();
        } else {
            // Volunteer balas ke anggota tertentu
            $anggota_id = $request->input('anggota_id');

            Pesan::create([
                'pengirim_id' => $pengirim->id,
                'penerima_id' => $anggota_id,
                'isi' => $isi
            ]);

            broadcast(new MessageSent($isi, $anggota_id))->toOthers();
        }

        return response()->json(['status' => 'Pesan dikirim']);
    }

    public function findBukuId($id)
    {
        $buku = Buku::with(['kategori', 'fisik', 'digital'])->find($id);

        if (!$buku) {
            return response()->json(['message' => 'Data buku tidak ditemukan.'], 404);
        }

        return response()->json($buku);
    }


    public function findPeminjamanId($id)
    {       
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->find($id);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Tambahkan semua data lain yang dibutuhkan oleh view
        $peminjamans = Peminjaman::whereNotIn('status_pengembalian', ['Kembalikan', 'Tolak'])
                                ->orderByRaw("FIELD(status_pengembalian, 'Belum Diambil') DESC")
                                ->latest('tanggal_pinjam')
                                ->get();   

        $anggotaList = Anggota::all();
        $bukuList = Buku::all();

        return view('admin.components.peminjaman', [
            'title'       => 'Halaman Peminjaman',
            'peminjamans' => $peminjamans,
            'anggotaList' => $anggotaList,
            'bukuList'    => $bukuList,
            'peminjaman'  => $peminjaman, // ini untuk modal edit
        ]);
    }

    public function updateBuku(Request $request, $id)
    {
        Log::info('Mulai proses updateBuku', ['request' => $request->all(), 'id_buku' => $id]);

        try {
            $buku = Buku::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:70',
                'id_kategori' => 'required|exists:kategoris,id',
                'penulis' => 'required|string|max:30',
                'penerbit' => 'required|string|max:30',
                'tahun_terbit' => 'required|date',
                'tipe' => 'required|in:digital,fisik',
                'harga' => 'required|string|max:10',
                'deskripsi' => 'required|string',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
                'file_url' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->tipe === 'digital' && !$request->hasFile('file_url') && !$request->old_file_url) {
                            $fail('File PDF harus diunggah untuk tipe digital.');
                        }
                    },
                    'nullable', 'mimes:pdf', 'max:10000',
                ],
                'jumlahHalaman' => 'nullable|integer|min:1',
                'stok' => 'required_if:tipe,fisik|integer|min:0|nullable',
            ]);

            // Upload gambar baru ke Supabase jika ada
            if ($request->hasFile('img')) {
                $fileImg = $request->file('img');
                $filenameImg = Str::slug($validated['judul']) . '-' . Str::uuid() . '.' . $fileImg->getClientOriginalExtension();
                $filePathImg = "cover/{$filenameImg}";

                $uploadImg = Http::withHeaders([
                    'apikey' => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type' => $fileImg->getMimeType(),
                ])->withBody(file_get_contents($fileImg->getPathname()), $fileImg->getMimeType())
                ->put(rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$filePathImg}");

                if (!$uploadImg->successful()) {
                    return back()->withErrors(['img' => 'Gagal upload gambar ke Supabase: ' . $uploadImg->body()])->withInput();
                }

                $buku->img = $filePathImg;
            }

            // Update data utama buku
            $buku->update([
                'judul' => $validated['judul'],
                'id_kategori' => $validated['id_kategori'],
                'penulis' => $validated['penulis'],
                'penerbit' => $validated['penerbit'],
                'tahun_terbit' => $validated['tahun_terbit'],
                'tipe' => $validated['tipe'],
                'harga' => $validated['harga'],
                'deskripsi' => $validated['deskripsi'],
            ]);

            // Jika digital
            if ($validated['tipe'] === 'digital') {
                $digital = Digital::firstOrNew(['id_buku' => $buku->id]);

                if ($request->hasFile('file_url')) {
                    $filePdf = $request->file('file_url');
                    $filenamePdf = Str::slug($validated['judul']) . '-' . Str::uuid() . '.' . $filePdf->getClientOriginalExtension();
                    $filePathPdf = "pdf/{$filenamePdf}";

                    $uploadPdf = Http::withHeaders([
                        'apikey' => env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Content-Type' => $filePdf->getMimeType(),
                    ])->withBody(file_get_contents($filePdf->getPathname()), $filePdf->getMimeType())
                    ->put(rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$filePathPdf}");

                    if (!$uploadPdf->successful()) {
                        return back()->withErrors(['file_url' => 'Gagal upload PDF ke Supabase: ' . $uploadPdf->body()])->withInput();
                    }

                    $digital->file_url = $filePathPdf;
                }

                if ($request->filled('jumlahHalaman')) {
                    $digital->jumlahHalaman = $request->jumlahHalaman;
                }

                $digital->save();
            }

            // Jika fisik
            if ($validated['tipe'] === 'fisik') {
                $fisik = Fisik::firstOrNew(['id_buku' => $buku->id]);
                $stokLama = $fisik->stok ?? 0;
                $stokBaru = (int) $validated['stok'];

                // Update fisik
                $fisik->stok = $stokBaru;
                $fisik->save();

                // Tambah stok → buat Detailbuku baru
                if ($stokBaru > $stokLama) {
                    $tambahan = $stokBaru - $stokLama;
                    $kodePrefix = strtoupper(substr(Str::slug($validated['judul']), 0, 3));
                    $lastIndex = Detailbuku::where('id_buku', $buku->id)->count();

                    for ($i = 1; $i <= $tambahan; $i++) {
                        $kode = $kodePrefix . str_pad($lastIndex + $i, 3, '0', STR_PAD_LEFT);

                        Detailbuku::create([
                            'id_buku' => $buku->id,
                            'id_fisik' => $fisik->id,
                            'kode' => $kode,
                            'dipinjam' => false,
                        ]);
                    }
                }

                // Kurangi stok → hapus Detailbuku yang belum dipinjam
                if ($stokBaru < $stokLama) {
                    $selisih = $stokLama - $stokBaru;

                    $detailBisaDihapus = Detailbuku::where('id_buku', $buku->id)
                        ->where('id_fisik', $fisik->id)
                        ->where('dipinjam', false)
                        ->orderByDesc('id')
                        ->take($selisih)
                        ->get();

                    if ($detailBisaDihapus->count() < $selisih) {
                        return back()->withErrors(['stok' => 'Tidak bisa mengurangi stok karena ada buku yang sedang dipinjam.']);
                    }

                    foreach ($detailBisaDihapus as $detail) {
                        $detail->delete();
                    }
                }
            }

            // ✅ Log aktivitas admin
            ActivityLog::create([
                'user_id' => auth()->id(),
                'aksi' => 'Update Buku',
                'deskripsi' => 'Admin memperbarui data buku: ' . $buku->judul,
                'ip_address' => $request->ip(),
            ]);

            return redirect()->back()->with('success', 'Buku berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui buku', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui buku');
        }
    }

    public function deleteBuku($id)
    {
        try {
            $buku = Buku::with(['digital', 'fisik'])->findOrFail($id);
            $judulBuku = $buku->judul;

            // Hapus data peminjaman terkait
            \App\Models\Peminjaman::where('id_buku', $buku->id)->delete();

            // Hapus gambar dari Supabase
            if ($buku->img) {
                $imgPath = $buku->img;

                $deleteImg = Http::withHeaders([
                    'apikey'       => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization'=> 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type' => 'application/json',
                ])->delete(rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$imgPath}");

                Log::info('Gambar buku dihapus dari Supabase', ['path' => $imgPath, 'success' => $deleteImg->successful()]);
            }

            // Hapus file PDF dari Supabase
            if ($buku->digital) {
                $pdfPath = $buku->digital->file_url;

                $deletePdf = Http::withHeaders([
                    'apikey'       => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization'=> 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type' => 'application/json',
                ])->delete(rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$pdfPath}");

                Log::info('PDF buku digital dihapus dari Supabase', ['path' => $pdfPath, 'success' => $deletePdf->successful()]);

                $buku->digital()->delete();
            }

            // Hapus detail buku fisik jika ada
            if ($buku->fisik) {
                \App\Models\Detailbuku::where('id_fisik', $buku->fisik->id)->delete();
                $buku->fisik()->delete();
            }

            // Hapus data buku utama
            $buku->delete();

            // ✅ Catat aktivitas admin
            \App\Models\ActivityLog::create([
                'user_id'   => auth()->id(),
                'aksi'      => 'Hapus Buku',
                'deskripsi' => 'Admin menghapus buku: ' . $judulBuku,
                'ip_address'=> request()->ip(),
            ]);

            return redirect()->back()->with('success', 'Buku berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus buku', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus buku');
        }
    }

    public function bukuByKategori($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $bukus = Buku::where('id_kategori', $id)->with(['digital', 'fisik'])->get();

            return view('admin.components.buku', [
                'bukus' => $bukus,
                'kategori' => $kategori,
                'title' => 'Kategori: ' . $kategori->nama
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan buku berdasarkan kategori', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Kategori tidak ditemukan');
        }
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(['error' => 'File tidak ditemukan di request'], 400);
        }

        $filePath = $file->getClientOriginalName(); // Nama file yang akan disimpan di Supabase
        $fileContent = file_get_contents($file->getPathname());

        $url = env('SUPABASE_URL') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/$filePath";

        $response = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_ROLE_KEY'),  // pakai service role key untuk akses penuh
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
            'Content-Type' => $file->getMimeType(),
        ])
        ->withBody($fileContent, $file->getMimeType())
        ->put($url);

        if ($response->successful()) {
            return response()->json(['message' => 'File berhasil di-upload!']);
        } else {
            return response()->json(['error' => 'Gagal upload', 'detail' => $response->body()], 500);
        }
    }

    public function viewPdf($filename)
    {
        $url = env('SUPABASE_URL') . "/storage/v1/object/public/" . env('SUPABASE_BUCKET') . "/$filename";

        return redirect($url);
    }

    public function updatePeminjaman(Request $request, $id)
    {
        Log::info('Request updatePeminjaman:', $request->all());

        try {
            // Validasi data sesuai dengan enum dan struktur table
            $validated = $request->validate([
                'id_anggota'           => 'required|integer|exists:anggotas,id',
                'id_buku'              => 'required|integer|exists:bukus,id',
                'tanggal_pinjam'       => 'required|date',
                'tanggal_ambil'        => 'nullable|date',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_pinjam',
                'status_pengembalian'  => 'required|in:Belum Diambil,Dipinjam,Kembalikan,Terlambat,Tolak',
                'status_kondisi'       => 'nullable|in:Baik,Rusak,Hilang',
                'denda'                => 'nullable|numeric|min:0',
                'rating'               => 'nullable|integer|between:1,5',
                'ulasan'               => 'nullable|string|max:1000',
            ]);

            Log::info("Validasi berhasil untuk update peminjaman ID: $id");

            $peminjaman = Peminjaman::findOrFail($id);

            Log::info("Data peminjaman ditemukan:", $peminjaman->toArray());

            // Update data
            $peminjaman->update([
                'id_anggota'           => $validated['id_anggota'],
                'id_buku'              => $validated['id_buku'],
                'tanggal_pinjam'       => $validated['tanggal_pinjam'],
                'tanggal_ambil'        => $validated['tanggal_ambil'] ?? Carbon::now(),
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'status_pengembalian'  => $validated['status_pengembalian'],
                'status_kondisi'       => $validated['status_kondisi'] ?? 'Baik',
                'denda'                => $validated['denda'] ?? 0,
                'rating'               => $validated['rating'] ?? null,
                'ulasan'               => $validated['ulasan'] ?? null,
            ]);

            Log::info("Data peminjaman berhasil diupdate.");

            return redirect()->back()->with('success', 'Data peminjaman berhasil diupdate.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validasi gagal:", $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Terjadi kesalahan saat update peminjaman: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function deletePeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }


    public function exportPeminjaman(Request $request)
    {
         $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_sampai = $request->input('tanggal_sampai');

        return Excel::download(
            new PeminjamanExport($tanggal_mulai, $tanggal_sampai),
            'laporan_peminjaman.xlsx'
        );
    }

    public function createSetting(Request $request)
    {
        /* 1. Validasi (slug boleh sama, tidak perlu unique) */
        $validated = $request->validate([
            'slug'    => 'required|string|alpha_dash',
            'content' => 'nullable|string',
            'img'     => 'nullable|image|max:2048',
        ]);

        /* 2. Ambil atau buat record Page */
        $page = Page::firstOrCreate(
            ['slug' => $validated['slug']],
            ['content' => $validated['content']]
        );

        /* 3. Upload gambar (jika ada) */
        if ($request->hasFile('img')) {
            $file      = $request->file('img');
            $ext       = $file->getClientOriginalExtension();
            // ──> Nama file: {slug}-{UUID}.{ext}
            $filename  = $page->slug . '-' . Str::uuid() . '.' . $ext;
            // ──> Semua disimpan di folder "pages/"
            $filePath  = "pages/{$filename}";
            $fileBytes = file_get_contents($file->getPathname());

            $url = rtrim(env('SUPABASE_URL'), '/') .
                "/storage/v1/object/" .
                env('SUPABASE_BUCKET') .
                "/{$filePath}";

            $resp = Http::withHeaders([
                        'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Content-Type'  => $file->getMimeType(),
                    ])
                    ->withBody($fileBytes, $file->getMimeType())
                    ->put($url);

            if (!$resp->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $resp->body()
                ])->withInput();
            }

            // simpan path di DB (mis: pages/logo_beranda-90cb71a0.svg)
            $page->img = $filePath;
        }

        /* 4. Perbarui (atau simpan) konten */
        $page->content = $validated['content'];
        $page->save();

        /* 5. Redirect + pesan sukses */
        $message = $page->wasRecentlyCreated
            ? 'Halaman baru berhasil ditambahkan!'
            : 'Halaman berhasil diperbarui!';
        return redirect()->route('admin.settingAdmin')
                        ->with('success', $message);
    }

    public function updateSetting(Request $request, $id)
    {
        /* 1. Cari Page berdasarkan ID */
        $page = Page::findOrFail($id);

        /* 2. Validasi */
        $validated = $request->validate([
            'slug'    => 'required|string|alpha_dash|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
            'img'     => 'nullable|image|max:2048',
        ]);

        $page->slug    = $validated['slug'];
        $page->content = $validated['content'];

        /* 3. Jika upload gambar baru */
        if ($request->hasFile('img')) {

            // 3a. Hapus gambar lama dari Supabase
            if ($page->img) {
                $deleteUrl = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/" . $page->img;

                Http::withHeaders([
                    'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                ])->delete($deleteUrl);
            }

            // 3b. Upload gambar baru
            $file      = $request->file('img');
            $ext       = $file->getClientOriginalExtension();
            $filename  = $page->slug . '-' . Str::uuid() . '.' . $ext;
            $filePath  = "pages/{$filename}";
            $fileBytes = file_get_contents($file->getPathname());

            $uploadUrl = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$filePath}";

            $resp = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type'  => $file->getMimeType(),
            ])->withBody($fileBytes, $file->getMimeType())
            ->put($uploadUrl);

            if (!$resp->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $resp->body()
                ])->withInput();
            }

            // Simpan path baru ke DB
            $page->img = $filePath;
        }

        /* 4. Simpan */
        $page->save();

        /* 5. Redirect + pesan */
        return redirect()->route('admin.settingAdmin')
                        ->with('success', 'Halaman berhasil diperbarui!');
    }

    public function findSettingById($id)
    {
        // Cari page berdasarkan id
        $page = Page::find($id);

        // Jika tidak ditemukan, kembalikan 404
        if (!$page) {
            abort(404, 'Data halaman tidak ditemukan.');
        }

        return response()->json($page);
    }

    public function deleteSettingId($id)
    {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.settingAdmin')->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file dari Supabase jika ada
        if ($page->img) {
            $deleteUrl = rtrim(env('SUPABASE_URL'), '/') .
                        "/storage/v1/object/" .
                        env('SUPABASE_BUCKET') .
                        "/{$page->img}";

            $resp = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
            ])->delete($deleteUrl);

            if (!$resp->successful()) {
                return redirect()->route('admin.settingAdmin')
                                ->with('error', 'Gagal menghapus file di Supabase.');
            }
        }

        // Hapus data dari database
        $page->delete();

        return redirect()->route('admin.settingAdmin')
                        ->with('success', 'Halaman berhasil dihapus!');
    }

    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        $peminjaman->rating = $request->rating;
        $peminjaman->ulasan = $request->ulasan;
        $peminjaman->save();

        // ✅ Ambil user yang login (opsional, bisa untuk log identitas)
        $user = Auth::user();

        // ✅ Log aktivitas
        LogActivity::add(
            'Memberi Rating',
            'Anggota dengan email ' . ($user->email ?? 'tidak diketahui') .
            ' memberikan rating ' . $request->rating . ' untuk buku "' . ($peminjaman->buku->judul ?? '-') .
            '" dengan ulasan: "' . ($request->ulasan ?? '-') . '"'
        );

        return redirect()->back()->with('success', 'Terima kasih atas ulasannya!');
    }

    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'isbn' => 'required|string',
            'status_kondisi' => 'required|in:Baik,Rusak,Hilang'
        ]);

        $peminjaman = Peminjaman::with(['buku.fisik', 'detailbuku'])->findOrFail($id);

        if ($peminjaman->buku->isbn !== $request->isbn) {
            return redirect()->back()->with('error', 'ISBN tidak cocok dengan buku yang dipinjam.');
        }

        if ($peminjaman->buku->tipe === 'fisik' && !$peminjaman->detailbuku) {
            return redirect()->back()->with('error', 'Data kode buku tidak ditemukan.');
        }

        $user = Auth::user();
        if (!$user || $user->role !== 'voluntter') {
            return redirect()->back()->withErrors('Hanya voluntter yang bisa mengkonfirmasi pengembalian.');
        }

        $voluntter = Voluntter::where('id_akun', $user->id)->first();
        if (!$voluntter) {
            return redirect()->back()->withErrors('Akun ini belum terdaftar sebagai volunteer.');
        }

        $buku = $peminjaman->buku;
        $denda = 0;
        $statusPengembalian = 'Kembalikan';
        $today = now();
        $tanggalPengembalian = \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian);

        // Hitung keterlambatan
        if ($today->gt($tanggalPengembalian)) {
            $periode = \Carbon\CarbonPeriod::create($tanggalPengembalian->addDay(), $today);
            $hariTerlambat = 0;

            foreach ($periode as $hari) {
                if (!$hari->isSunday()) {
                    $hariTerlambat++;
                }
            }

            if ($hariTerlambat > 0) {
                $statusPengembalian = 'Terlambat';
                $denda += $hariTerlambat * 3000;
            }
        }

        // Tambah denda berdasarkan kondisi buku
        if ($request->status_kondisi === 'Rusak') {
            $denda += $buku->harga * 0.3;
        } elseif ($request->status_kondisi === 'Hilang') {
            $denda += $buku->harga;
        } elseif ($request->status_kondisi === 'Baik') {
            if ($buku->tipe === 'fisik') {
                if ($peminjaman->detailbuku) {
                    $peminjaman->detailbuku->dipinjam = false;
                    $peminjaman->detailbuku->save();
                }
                if ($buku->fisik) {
                    $buku->fisik->stok += 1;
                    $buku->fisik->save();
                }
            }
        }

        // Simpan data peminjaman
        $peminjaman->status_pengembalian = $statusPengembalian;
        $peminjaman->status_kondisi = $request->status_kondisi;
        $peminjaman->denda = $denda;
        $peminjaman->id_voluntter_kembali = $voluntter->id;
        $peminjaman->save();

        // Simpan ke tabel pembayaran_denda jika ada denda
        if ($denda > 0) {
            Pembayaran_denda::create([
                'id_peminjaman'     => $peminjaman->id,
                'jumlah_denda'      => $denda,
                'status_pembayaran' => 'Belum Dibayar',
                'metode_pembayaran' => null,
                'bukti_pembayaran'  => null,
                'tanggal_pembayaran'=> null,
            ]);
        }

        // Kirim notifikasi ke anggota
        Notifikasi::create([
            'id_anggota' => $peminjaman->id_anggota,
            'judul' => 'Pengembalian Berhasil',
            'pesan' => 'Pengembalian buku "' . $buku->judul . '" telah berhasil. Total denda: Rp ' . number_format($denda, 0, ',', '.'),
            'status_dibaca' => false,
        ]);

        // ✅ Log aktivitas admin
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Konfirmasi Pengembalian Buku',
            'deskripsi' => 'Voluntter mengembalikan buku "' . $buku->judul . '" (ISBN: ' . $buku->isbn . ') dengan kondisi ' . $request->status_kondisi . '. Status: ' . $statusPengembalian . ', Denda: Rp ' . number_format($denda, 0, ',', '.'),
            'ip_address'=> $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan. Status: ' . $statusPengembalian . ', Total Denda: Rp ' . number_format($denda, 0, ',', '.'));
    }

    public function getSnapToken($id)
    {
        $peminjaman = Peminjaman::with('anggota.akun')->findOrFail($id);

        $email = $peminjaman->anggota->akun->email ?? 'dummy@mail.com';
        $nama  = $peminjaman->anggota->nama ?? 'Anggota';

        $params = [
            'transaction_details' => [
                'order_id' => 'PMJ-' . $peminjaman->id . '-' . time(),
                'gross_amount' => $peminjaman->denda,
            ],
            'customer_details' => [
                'first_name' => $nama,
                'email' => $email,
            ],
            'enabled_payments' => ['qris'],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function bayarDenda(Request $request)
    {
        $request->validate([
            'pinjam_id' => 'required|exists:peminjamans,id',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil data peminjaman + relasi buku & anggota (jika ada)
        $peminjaman = Peminjaman::with(['buku', 'anggota'])->findOrFail($request->pinjam_id);
        $jumlahDenda = $peminjaman->denda;

        // Upload ke Supabase
        $file = $request->file('bukti_pembayaran');
        $ext = $file->getClientOriginalExtension();
        $filename = 'bukti_denda-' . Str::uuid() . '.' . $ext;
        $filePath = "bukti_denda/{$filename}";
        $fileBytes = file_get_contents($file->getPathname());

        $uploadUrl = rtrim(env('SUPABASE_URL'), '/') .
            "/storage/v1/object/" .
            env('SUPABASE_BUCKET') .
            "/{$filePath}";

        $response = Http::withHeaders([
            'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
            'Content-Type'  => $file->getMimeType(),
        ])
        ->withBody($fileBytes, $file->getMimeType())
        ->put($uploadUrl);

        if (!$response->successful()) {
            return back()->withErrors([
                'bukti_pembayaran' => 'Gagal upload bukti pembayaran: ' . $response->body()
            ])->withInput();
        }

        // Simpan atau update data pembayaran denda
        $pembayaranDenda = Pembayaran_denda::where('id_peminjaman', $request->pinjam_id)->first();

        if ($pembayaranDenda) {
            $pembayaranDenda->update([
                'jumlah_denda'       => $jumlahDenda,
                'metode_pembayaran'  => 'QRIS',
                'status_pembayaran'  => 'Belum Dibayar',
                'tanggal_pembayaran' => now(),
                'bukti_pembayaran'   => $filePath,
            ]);
        } else {
            Pembayaran_denda::create([
                'id_peminjaman'      => $request->pinjam_id,
                'jumlah_denda'       => $jumlahDenda,
                'metode_pembayaran'  => 'QRIS',
                'status_pembayaran'  => 'Belum Dibayar',
                'tanggal_pembayaran' => now(),
                'bukti_pembayaran'   => $filePath,
            ]);
        }

        // ✅ Tambahkan log aktivitas (lebih informatif)
        $namaBuku = $peminjaman->buku->judul ?? 'Tidak Diketahui';
        $namaAnggota = $peminjaman->anggota->nama ?? 'Tidak Diketahui';

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Bayar Denda',
            'deskripsi' => "Anggota '{$namaAnggota}' mengajukan bukti pembayaran denda untuk buku '{$namaBuku}' (Peminjaman ID: {$peminjaman->id})",
            'ip_address'=> $request->ip(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diajukan.');
    }


    public function konfirmasiPembayaran($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'anggota'])->findOrFail($id);

        // Pastikan sudah ada bukti pembayaran
        $pembayaranDenda = $peminjaman->pembayaranDenda;
        if (!$pembayaranDenda || !$pembayaranDenda->bukti_pembayaran) {
            return redirect()->back()->with('error', 'Bukti pembayaran belum diunggah.');
        }

        // Update status pembayaran di tabel pembayaran_denda
        $pembayaranDenda->status_pembayaran = 'Sudah Dibayar';
        $pembayaranDenda->save();

        // Set denda di tabel peminjaman menjadi 0
        $peminjaman->denda = 0;
        $peminjaman->save();

        // Ambil nama buku dan anggota untuk log
        $judulBuku = $peminjaman->buku->judul ?? 'Tidak Diketahui';
        $namaAnggota = $peminjaman->anggota->nama ?? 'Tidak Diketahui';

        // ✅ Tambahkan log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Konfirmasi Pembayaran Denda',
            'deskripsi' => "Admin mengkonfirmasi pembayaran denda anggota '{$namaAnggota}' untuk buku '{$judulBuku}' (Peminjaman ID: {$peminjaman->id})",
            'ip_address'=> request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function transaksiGagal($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'anggota'])->findOrFail($id);
        $pembayaranDenda = $peminjaman->pembayaranDenda;

        if (!$pembayaranDenda || !$pembayaranDenda->bukti_pembayaran) {
            return back()->with('error', 'Bukti pembayaran belum diunggah.');
        }

        if ($pembayaranDenda->status_pembayaran === 'Sudah Dibayar') {
            return back()->with('error', 'Transaksi ini sudah dikonfirmasi sebagai berhasil.');
        }

        // Tandai sebagai gagal
        $pembayaranDenda->status_pembayaran = 'Transaksi Gagal';
        $pembayaranDenda->save();

        // Ambil nama buku dan anggota untuk log
        $judulBuku = $peminjaman->buku->judul ?? 'Tidak Diketahui';
        $namaAnggota = $peminjaman->anggota->nama ?? 'Tidak Diketahui';

        // ✅ Tambahkan log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Tandai Transaksi Gagal',
            'deskripsi' => "Admin menandai transaksi pembayaran denda milik anggota '{$namaAnggota}' untuk buku '{$judulBuku}' (Peminjaman ID: {$peminjaman->id}) sebagai gagal.",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Transaksi berhasil ditandai sebagai gagal.');
    }

    public function bayarCash(Request $request, $id)
    {
        // Validasi input jumlah
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Ambil data peminjaman beserta relasi
        $peminjaman = Peminjaman::with(['buku', 'anggota'])->findOrFail($id);

        // Informasi untuk log
        $judulBuku = $peminjaman->buku->judul ?? 'Tidak Diketahui';
        $namaAnggota = $peminjaman->anggota->nama ?? 'Tidak Diketahui';

        // Cek apakah sudah ada pembayaran
        $existingPayment = Pembayaran_denda::where('id_peminjaman', $peminjaman->id)->first();

        if ($existingPayment) {
            if ($existingPayment->status_pembayaran === 'Sudah Dibayar') {
                return back()->with('error', 'Peminjaman ini sudah dibayar sebelumnya.');
            }

            // Update pembayaran dan set denda jadi 0
            $existingPayment->update([
                'metode_pembayaran' => 'Bayar di Tempat',
                'status_pembayaran' => 'Sudah Dibayar',
                'jumlah_denda' => $request->jumlah,
                'tanggal_pembayaran' => now(),
            ]);

            $peminjaman->update([
                'denda' => 0,
            ]);

            // ✅ Log aktivitas
            ActivityLog::create([
                'user_id'   => auth()->id(),
                'aksi'      => 'Pembayaran Cash Denda',
                'deskripsi' => "Admin mencatat pembayaran cash dari '{$namaAnggota}' untuk buku '{$judulBuku}' (Peminjaman ID: {$peminjaman->id}) sebesar Rp{$request->jumlah}.",
                'ip_address'=> $request->ip(),
            ]);

            return back()->with('success', 'Pembayaran cash berhasil diperbarui dan denda dihapus.');
        }

        // Belum ada pembayaran, buat baru
        Pembayaran_denda::create([
            'id_peminjaman' => $peminjaman->id,
            'jumlah_denda' => $request->jumlah,
            'metode_pembayaran' => 'Bayar di Tempat',
            'status_pembayaran' => 'Sudah Dibayar',
            'tanggal_pembayaran' => now(),
        ]);

        $peminjaman->update([
            'denda' => 0,
        ]);

        // ✅ Log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Pembayaran Cash Denda',
            'deskripsi' => "Admin mencatat pembayaran cash dari '{$namaAnggota}' untuk buku '{$judulBuku}' (Peminjaman ID: {$peminjaman->id}) sebesar Rp{$request->jumlah}.",
            'ip_address'=> $request->ip(),
        ]);

        return back()->with('success', 'Pembayaran cash berhasil dicatat dan denda dihapus.');
    }

    public function tandaiSudahDibaca(Request $request)
    {
        if (auth()->check() && auth()->user()->anggota) {
            Notifikasi::where('id_anggota', auth()->user()->anggota->id)
                    ->where('dibaca', false)
                    ->update(['dibaca' => true]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    public function tolakPengembalian($id)
    {
        $peminjaman = Peminjaman::with(['buku.fisik', 'anggota'])->findOrFail($id);

        // Ubah status pengembalian jadi "Tolak"
        $peminjaman->status_pengembalian = 'Tolak';
        $peminjaman->save();

        // Kembalikan stok buku jika ada relasi fisik
        if ($peminjaman->buku && $peminjaman->buku->fisik) {
            $peminjaman->buku->fisik->stok += 1;
            $peminjaman->buku->fisik->save();
        }

        // Tambahkan notifikasi ke anggota
        Notifikasi::create([
            'id_anggota'     => $peminjaman->id_anggota,
            'judul'          => 'Permintaan Pengembalian Ditolak',
            'pesan'          => 'Maaf, buku "' . $peminjaman->buku->judul . '" tidak dapat dipinjam saat ini.',
            'status_dibaca'  => false,
        ]);

        // Informasi tambahan untuk log
        $judulBuku = $peminjaman->buku->judul ?? 'Tidak Diketahui';
        $namaAnggota = $peminjaman->anggota->nama ?? 'Tidak Diketahui';

        // ✅ Log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Tolak Pengembalian',
            'deskripsi' => "Admin menolak pengembalian buku '{$judulBuku}' milik anggota '{$namaAnggota}' (Peminjaman ID: {$peminjaman->id}). Stok buku dikembalikan.",
            'ip_address'=> request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Permintaan pengembalian ditolak dan stok buku dikembalikan.');
    }

    public function updateOnlyAnggota(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama'   => 'required|string|max:50',
            'notlp'  => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
            'alamat' => 'required|string|max:50',
            'img'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'notlp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'img.max'     => 'Ukuran gambar maksimal 2MB.',
            'img.mimes'   => 'Format gambar harus jpg, jpeg, png, atau webp.',
        ]);

        // Ambil data anggota
        $anggota = Anggota::findOrFail($id);
        $anggota->nama   = $request->nama;
        $anggota->notlp  = $request->notlp;
        $anggota->alamat = $request->alamat;

        // Upload gambar ke Supabase
        if ($request->hasFile('img')) {
            $file     = $request->file('img');
            $ext      = $file->getClientOriginalExtension();
            $filename = 'anggota-' . Str::uuid() . '.' . $ext;
            $filePath = "anggota/{$filename}";
            $fileBytes = file_get_contents($file->getPathname());

            $url = rtrim(env('SUPABASE_URL'), '/') .
                "/storage/v1/object/" .
                env('SUPABASE_BUCKET') .
                "/{$filePath}";

            $response = Http::withHeaders([
                'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                'Content-Type'  => $file->getMimeType(),
            ])
            ->withBody($fileBytes, $file->getMimeType())
            ->put($url);

            if (!$response->successful()) {
                return back()->withErrors([
                    'img' => 'Upload gambar ke Supabase gagal: ' . $response->body()
                ])->withInput();
            }

            $anggota->img = $filePath;
        }

        // Simpan perubahan
        $anggota->save();

        // Tambahkan log aktivitas
        $akun = auth()->user();
        if ($akun) {
            ActivityLog::create([
                'user_id'    => $akun->id,
                'aksi'       => 'Update Profil Anggota',
                'deskripsi'  => 'Anggota dengan email ' . $akun->email . ' memperbarui profil dengan nama ' . $anggota->nama,
                'ip_address' => $request->ip(),
            ]);
        }

        // Kirim pesan sukses ke view
        return redirect()->back()->with('success', '✅ Data anggota berhasil diperbarui.');
    }

    public function exportAnggota()
    {
        $fileName = 'data_anggota_' . now()->format('Ymd_His') . '.xlsx';

        // ✅ Log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Export Data Anggota',
            'deskripsi' => 'Admin mengekspor data semua anggota ke file ' . $fileName,
            'ip_address'=> request()->ip(),
        ]);

        return Excel::download(new AnggotaExport, $fileName);
    }

    public function exportVoluntter()
    {
        $fileName = 'data_voluntter_' . now()->format('Ymd_His') . '.xlsx';

        // ✅ Log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Export Data Voluntter',
            'deskripsi' => 'Admin mengekspor data semua voluntter ke file ' . $fileName,
            'ip_address'=> request()->ip(),
        ]);

        return Excel::download(new VolunteerExport, $fileName);
    }

    public function logLihatPengumuman(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            ActivityLog::create([
                'user_id'   => $user->id,
                'aksi'      => 'Lihat Pengumuman',
                'deskripsi' => 'Melihat detail pengumuman: ' . $request->judul,
                'ip_address'=> $request->ip(),
            ]);
        }

        return response()->json(['success' => true]);
    }

}

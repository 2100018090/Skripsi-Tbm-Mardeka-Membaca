<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Akun;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Digital;
use App\Models\Fisik;
use App\Models\Kategori;
use App\Models\Page;
use App\Models\Pembayaran_denda;
use App\Models\Peminjaman;
use App\Models\Pengumuman;
use App\Models\Voluntter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class LayoutController extends Controller
{

    public function beranda(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login dulu');
        }

        Log::info('Akses ke beranda oleh user', ['user_id' => $user->id]);

        // ==========================
        // FILTER: Peminjaman
        // ==========================
        $tanggalMulai  = $request->input('tanggal_mulai');
        $tanggalSampai = $request->input('tanggal_sampai');

        $queryPeminjaman = Peminjaman::query()
            ->whereHas('buku', function ($q) {
                $q->where('tipe', 'fisik');
            })
            ->whereIn('status_pengembalian', ['Dipinjam', 'Kembalikan', 'Terlambat']); // ✅ hanya tampilkan yang diinginkan


        if ($tanggalMulai && !$tanggalSampai) {
            $start = Carbon::parse($tanggalMulai)->startOfMonth();
            $end   = Carbon::parse($tanggalMulai)->endOfMonth();
            $queryPeminjaman->whereBetween('tanggal_pinjam', [$start, $end]);
        } elseif ($tanggalMulai && $tanggalSampai) {
            $start = Carbon::parse($tanggalMulai)->startOfMonth();
            $end   = Carbon::parse($tanggalSampai)->endOfMonth();
            $queryPeminjaman->whereBetween('tanggal_pinjam', [$start, $end]);
        }

        $allPeminjamans = $queryPeminjaman->with(['anggota', 'buku', 'voluntterPinjam', 'voluntterKembali'])->get();
        $peminjamans    = $queryPeminjaman->with(['anggota', 'buku', 'voluntterPinjam', 'voluntterKembali'])->paginate(5);


        // ==========================
        // FILTER: Buku (judul, isbn, tipe)
        // ==========================
        $jenisBuku = $request->input('tipe');   // fisik/digital
        $judul     = $request->input('judul');  // nama buku
        $isbn      = $request->input('isbn');   // ISBN

        $queryBuku = Buku::query()->with(['fisik', 'digital']);

        if (!empty($jenisBuku)) {
            $queryBuku->where('tipe', strtolower($jenisBuku));
            Log::info('Filter tipe diterapkan:', ['tipe' => strtolower($jenisBuku)]);
        }

        if (!empty($judul)) {
            $queryBuku->where('judul', 'like', '%' . $judul . '%');
            Log::info('Filter judul diterapkan:', ['judul' => '%' . $judul . '%']);
        }

        if (!empty($isbn)) {
            $queryBuku->where('isbn', 'like', '%' . $isbn . '%');
            Log::info('Filter ISBN diterapkan:', ['isbn' => '%' . $isbn . '%']);
        }

        $bukus = $queryBuku->paginate(5)->appends($request->all());

        // ==========================
        // Data Lainnya
        // ==========================
        $totalPendapatanDenda = Pembayaran_denda::where('status_pembayaran', 'Sudah Dibayar')->sum('jumlah_denda');
        $dataDenda = Pembayaran_denda::select('pembayaran_dendas.*')
            ->leftJoin('peminjamans', 'pembayaran_dendas.id_peminjaman', '=', 'peminjamans.id')
            ->with(['peminjaman.anggota', 'peminjaman.buku'])
            ->orderByDesc('peminjamans.tanggal_pengembalian')
            ->paginate(5);



        return view('admin.components.beranda', [
            'userData' => $user,
            'title' => 'Beranda Admin',

            // Data untuk filter buku
            'bukus' => $bukus,
            'filter' => [
                'jenis_buku' => $jenisBuku,
                'nama_buku' => $judul,
                'isbn' => $isbn,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_sampai' => $tanggalSampai,
            ],

            // Statistik umum
            'totalFisik' => Fisik::count(),
            'totalDigital' => Digital::count(),
            'totalBuku' => Buku::count(),
            'totalPeminjaman' => Peminjaman::count(),
            'totalVoluntter' => Voluntter::count(),
            'totalAnggota' => Anggota::count(),
            'totalPendapatanDenda' => $totalPendapatanDenda,
            'dataDenda' => $dataDenda,

            // Data peminjaman
            'peminjamans' => isset($peminjamans) ? $peminjamans : collect(),
            'grafikData' => $allPeminjamans->groupBy(fn($item) => $item->buku->judul ?? 'Tidak Diketahui')->map(fn($g) => $g->count()),
            'tanggalTerakhir' => $allPeminjamans->groupBy(fn($item) => $item->buku->judul ?? 'Tidak Diketahui')->map(fn($g) => $g->max('tanggal_pinjam')),
            'grafikTanggal' => $allPeminjamans->groupBy(fn($item) => $item->tanggal_pinjam)->map(fn($g) => $g->count()),
        ]);
    }

    public function cariLaporanbuku(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        Log::info('Akses ke laporan buku oleh user', ['user_id' => $user->id]);

        // Ambil filter dari request
        $jenisBuku = $request->input('tipe');   // tipe: fisik / digital
        $judul     = $request->input('judul');  // nama buku
        $isbn      = $request->input('isbn');   // ISBN

        Log::info('Filter yang diterima:', [
            'tipe' => $jenisBuku,
            'judul' => $judul,
            'isbn' => $isbn,
        ]);

        // Query buku + relasi ke fisik / digital
        $query = Buku::query()->with(['fisik', 'digital']);

        // Filter berdasarkan tipe
        if (!empty($jenisBuku)) {
            $query->where('tipe', strtolower($jenisBuku));
            Log::info('Filter tipe diterapkan:', ['tipe' => strtolower($jenisBuku)]);
        }

        // Filter berdasarkan judul
        if (!empty($judul)) {
            $query->where('judul', 'like', '%' . $judul . '%');
            Log::info('Filter judul diterapkan:', ['judul' => '%' . $judul . '%']);
        }

        // Filter berdasarkan ISBN
        if (!empty($isbn)) {
            $query->where('isbn', 'like', '%' . $isbn . '%');
            Log::info('Filter ISBN diterapkan:', ['isbn' => '%' . $isbn . '%']);
        }

        // Ambil data buku dengan pagination (5 per halaman)
        $bukus = $query->paginate(5)->appends($request->all());

        Log::info('Hasil query buku:', ['jumlah_data' => $bukus->total()]);

        // Statistik tambahan
        $totalFisik      = Fisik::count();
        $totalDigital    = Digital::count();
        $totalBuku       = Buku::count();
        $totalPeminjaman = Peminjaman::count();
        $totalVoluntter  = Voluntter::count();
        $totalAnggota    = Anggota::count();

        $totalPendapatanDenda = Pembayaran_denda::where('status_pembayaran', 'Sudah Dibayar')->sum('jumlah_denda');
        $dataDenda = Pembayaran_denda::with(['peminjaman.anggota', 'peminjaman.buku'])->get();

        return view('admin.components.beranda', [
            'userData'        => $user,
            'title'           => 'Laporan Buku',
            'bukus'           => $bukus,
            'filter'          => [
                'jenis_buku' => $jenisBuku,
                'nama_buku'  => $judul,
                'isbn'       => $isbn,
            ],
            'totalFisik'      => $totalFisik,
            'totalDigital'    => $totalDigital,
            'totalBuku'       => $totalBuku,
            'totalPeminjaman' => $totalPeminjaman,
            'totalVoluntter'  => $totalVoluntter,
            'totalAnggota'    => $totalAnggota,
            'totalPendapatanDenda' => $totalPendapatanDenda,
            'dataDenda' => $dataDenda,
        ]);
    }

    public function profileAdmin(Request $request)
    {
        $user = auth()->user();

        // Cek jika belum login
        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        // Cek role dan ambil data profile sesuai role
        switch ($user->role) {
            case 'admin':
                $profileData = Admin::where('id_akun', $user->id)->first();
                $roleName = 'Admin';
                break;

            case 'voluntter':
                $profileData = Voluntter::where('id_akun', $user->id)->first();
                $roleName = 'Voluntter';
                break;

            default:
                return redirect()->route('dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        if (!$profileData) {
            return redirect()->route('dashboard')->withErrors('Data profil tidak ditemukan.');
        }

        return view('admin.components.profile', [
            'title'        => 'Setting ' . $roleName,
            'userData'     => $user,
            'profileData'  => $profileData,
            'roleName'     => $roleName,
        ]);
    }

    public function data_anggota(Request $request)
    {
        $user = Auth::user(); // Ambil data akun yang login

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        $role = $user->role;
        $userDetail = null;

        // Ambil detail user sesuai role
        if ($role === 'admin') {
            $userDetail = Admin::where('id_akun', $user->id)->first();
            if (!$userDetail) {
                return redirect()->route('dashboard')->withErrors('Data admin tidak ditemukan.');
            }
        } elseif ($role === 'voluntter') {
            $userDetail = Voluntter::where('id_akun', $user->id)->first();
            if (!$userDetail) {
                return redirect()->route('dashboard')->withErrors('Data voluntter tidak ditemukan.');
            }
        } else {
            return redirect()->route('dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        // ✅ Ambil data anggota yang akunnya role 'anggota' DENGAN PAGINASI
        $anggotas = Anggota::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('role', 'anggota');
            })
            ->paginate(5); // Menampilkan 5 per halaman

        return view('admin.components.data_anggota', [
            'userDetail' => $userDetail,
            'userData'   => $user,
            'title'      => 'Data Anggota',
            'anggotas'   => $anggotas,
            'role'       => $role,
        ]);
    }

    public function konfirmasiVoluntter(Request $request)
    {
        $user = Auth::user(); // Ambil data akun yang login

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        $role = $user->role;
        $userDetail = null;

        // Ambil detail user sesuai role
        if ($role === 'admin') {
            $userDetail = Admin::where('id_akun', $user->id)->first();
            if (!$userDetail) {
                return redirect()->route('dashboard')->withErrors('Data admin tidak ditemukan.');
            }
        } elseif ($role === 'voluntter') {
            $userDetail = Voluntter::where('id_akun', $user->id)->first();
            if (!$userDetail) {
                return redirect()->route('dashboard')->withErrors('Data voluntter tidak ditemukan.');
            }
        } else {
            return redirect()->route('dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        // ✅ Ambil data anggota dengan role 'anggota' DAN akses = 'pending'
        $anggotas = Anggota::with('akun')
            ->whereHas('akun', function ($query) {
                $query->where('role', 'anggota');
            })
            ->where('akses', 'pending') // 🔹 Tambahkan filter akses
            ->paginate(5); // Menampilkan 5 per halaman

        return view('admin.components.konfirmasiVoluntter', [
            'userDetail' => $userDetail,
            'userData'   => $user,
            'title'      => 'Data Konfirmasi',
            'anggotas'   => $anggotas,
            'role'       => $role,
        ]);
    }

    public function data_voluntter(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        // Pastikan user yang login adalah admin
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil data admin berdasarkan id akun (asumsi id_akun di Admin mengacu ke id user)
        $admin = Admin::where('id_akun', $user->id)->first();

        if (!$admin) {
            return redirect()->route('dashboard')->withErrors('Data admin tidak ditemukan.');
        }

        // Ambil semua data voluntter yang memiliki akun dengan role 'voluntter'
        $voluntters = Voluntter::with('akun') // pastikan relasi akun sudah ada di model Voluntter
            ->whereHas('akun', function ($query) {
                $query->where('role', 'voluntter');
            })
            ->get();

        return view('admin.components.data_voluntter', [
            'admin' => $admin,
            'userData' => $user,
            'title' => 'Data Voluntter',
            'voluntters' => $voluntters,
        ]);
    }


    public function pengumuman(Request $request)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        if (!$user) {
            // Kalau user tidak login, redirect ke login
            return redirect()->route('login')->withErrors('Harap login dulu');
        }

        // Ambil data admin dari relasi akun
        $admin = Admin::where('id_akun', $user->id)->first();

        // Ambil semua data pengumuman, urutkan berdasarkan waktu terbaru
        $pengumumans = Pengumuman::latest()->get();

        return view('admin.components.pengumuman', [
            'admin'        => $admin,
            'userData'     => $user, // bisa digunakan di view
            'title'        => 'Pengumuman',
            'pengumumans'  => $pengumumans,
        ]);
    }

    public function anggota_pengumuman(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login dulu');
        }

        $admin = Admin::where('id_akun', $user->id)->first();

        // Ambil pengumuman terbaru (yang paling baru tampil pertama)
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->paginate(6);

        return view('user.components.pengumuman', [
            'admin' => $admin,
            'userData' => $user,
            'title' => 'Pengumuman',
            'pengumumans' => $pengumumans,
        ]);
    }

    public function landingpage()
    {
        $volunteers = Voluntter::all(); // Ambil semua data volunteer dari model
        $title = 'Landing Page - TBM Mardeka Membaca'; // Sesuaikan judul sesuai kebutuhanmu

        // Ambil 5 buku dengan rating rata-rata tertinggi dari peminjaman
        $topBooks = Peminjaman::select('id_buku', DB::raw('AVG(rating) as avg_rating'))
            ->whereNotNull('rating') // hanya yang sudah ada rating
            ->groupBy('id_buku')
            ->orderByDesc('avg_rating')
            ->limit(5)
            ->with('buku')  // eager loading relasi buku
            ->get();

        return view('user.components.landing_page', compact('volunteers', 'title', 'topBooks'));
    }

    public function layoutsDataBuku(Request $request)
    {
        $user = Auth::user(); // Ambil akun yang sedang login

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        // Ambil semua kategori buku
        $kategoris = Kategori::all();

        // Ambil data buku dengan relasi kategori dan pagination
        $bukus = Buku::with('kategori')->paginate(5);

        return view('admin.components.buku', [
            'userData'  => $user,
            'title'     => 'Halaman Buku',
            'kategoris' => $kategoris,
            'bukus'     => $bukus,
        ]);
    }


    public function layoutskoleksi()
    {
        $judul = request('judul');
        $kategori = request('kategori');

        // Ambil daftar kategori unik dari tabel kategori
        $kategoriList = Kategori::select('nama')->distinct()->pluck('nama');

        if ($judul || $kategori) {
            $bukus = Buku::with(['fisik', 'kategori'])
                ->when($judul, fn($query) => $query->where('judul', 'like', "%$judul%"))
                ->when($kategori, fn($query) =>
                    $query->whereHas('kategori', fn($q) =>
                        $q->where('nama', 'like', "%$kategori%")
                    )
                )
                ->get()
                ->groupBy(fn($b) => $b->judul . '|' . $b->id_kategori);

            // Konversi hasil groupBy ke bentuk array untuk pagination
            $grouped = $bukus->values(); // reset key numerik

            // Pagination manual
            $perPage = 5;
            $currentPage = request()->get('page', 1);
            $pagedData = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginatedGroups = new LengthAwarePaginator(
                $pagedData,
                $grouped->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            // ✅ Tambahkan alert jika hasil pencarian kosong
            if ($grouped->isEmpty()) {
                session()->flash('error', 'Buku tidak ditemukan dengan kata kunci tersebut.');
            }
        } else {
            $paginatedGroups = new LengthAwarePaginator([], 0, 5); // kosong tapi tetap format paginator
        }

        // Hitung total buku fisik
        $totalFisik = Buku::where('tipe', 'fisik')->count();

        // Hitung total buku digital
        $totalDigital = Buku::where('tipe', 'digital')->count();

        return view('user.components.koleksi', [
            'title' => 'Koleksi',
            'bookGroups' => $paginatedGroups,
            'totalFisik' => $totalFisik,
            'totalDigital' => $totalDigital,
            'kategoriList' => $kategoriList,
            'filter' => [
                'judul' => $judul,
                'kategori' => $kategori,
            ],
        ]);
    }

    public function layoutsRiwayat()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors('Harap login terlebih dahulu.');
        }

        $anggota = $user->anggota;

        // Jika belum ada data anggota, kembalikan halaman tanpa error
        if (!$anggota) {
            $peminjamanKosong = new LengthAwarePaginator([], 0, 10);

            return view('user.components.riwayat', [
                'title' => 'Halaman Riwayat',
                'peminjaman' => $peminjamanKosong,
                'anggota' => null,
                'pesan' => 'Silakan lengkapi profil terlebih dahulu di halaman Profil.',
                'tampilkanKolomDenda' => false
            ]);
        }

        // Hitung denda otomatis jika ada keterlambatan
        $tarifPerHari = 3000;
        $peminjamanTerlambat = Peminjaman::where('id_anggota', $anggota->id)
            ->where('status_pengembalian', '!=', 'Kembalikan')
            ->whereDate('tanggal_pengembalian', '<', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('denda')->orWhere('denda', 0);
            })
            ->get();

        foreach ($peminjamanTerlambat as $pinjam) {
            $hariTerlambat = Carbon::parse($pinjam->tanggal_pengembalian)->diffInDays(Carbon::now());
            $denda = $hariTerlambat * $tarifPerHari;

            $pinjam->denda = $denda;
            $pinjam->save();
        }

        $peminjaman = Peminjaman::with('buku')
            ->where('id_anggota', $anggota->id)
            ->orderByRaw("rating IS NULL DESC")
            ->orderByRaw("FIELD(status_pengembalian, 'Belum Diambil', 'Dipinjam', 'Tolak')")
            ->orderByDesc('tanggal_pinjam')
            ->paginate(10);

        $tampilkanKolomDenda = $peminjaman->contains(function ($pinjam) {
            return $pinjam->denda > 0 && $pinjam->status_pengembalian !== 'Belum Diambil';
        });

        return view('user.components.riwayat', [
            'title' => 'Halaman Riwayat',
            'peminjaman' => $peminjaman,
            'anggota' => $anggota,
            'tampilkanKolomDenda' => $tampilkanKolomDenda,
            'pesan' => null
        ]);
    }

    public function layoutsPeminjaman()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('masuk')->withErrors('Harap login terlebih dahulu.');
        }

        if ($user->role === 'admin') {
            $peminjamans = Peminjaman::where('status_pengembalian', '!=', 'Belum Diambil')
                                    ->latest('tanggal_pinjam')
                                    ->get();
        } else {
            $peminjamans = Peminjaman::whereNotIn('status_pengembalian', ['Kembalikan', 'Tolak'])
                                    ->orderByRaw("FIELD(status_pengembalian, 'Belum Diambil') DESC")
                                    ->latest('tanggal_pinjam')
                                    ->get();
        }

        $anggotaList = Anggota::all();
        $bukuList    = Buku::all();
        $bukuFisik = Buku::where('tipe', 'fisik')->get();

        return view('admin.components.peminjaman', [
            'title'       => 'Halaman Peminjaman',
            'peminjamans' => $peminjamans,
            'anggotaList' => $anggotaList,
            'bukuList'    => $bukuList,
            'bukuFisik' => $bukuFisik,
            'role'        => $user->role, // ✅ tambahan ini
        ]);
    }

    public function layoutsKonfirmasi()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')
                            ->withErrors('Harap login terlebih dahulu.');
        }

        // Ambil semua peminjaman dengan status 'Belum Diambil'
        $peminjamans = Peminjaman::with(['buku', 'anggota']) // relasi buku dan anggota jika perlu
                                ->where('status_pengembalian', 'Belum Diambil')
                                ->latest('tanggal_pinjam')
                                ->get();

        // Ambil semua detail buku yang belum dipinjam, dikelompokkan berdasarkan id_buku
        $detailbukus = \App\Models\Detailbuku::where('dipinjam', false)->get()->groupBy('id_buku');

        return view('admin.components.konfirmasi', [
            'title' => 'Halaman Konfirmasi',
            'peminjamans' => $peminjamans,
            'detailbukus' => $detailbukus
        ]);
    }

    public function berandaAnggota()
    {
        $volunteers = Voluntter::all(); // Ambil semua data volunteer dari model
        $title = 'Beranda - TBM Mardeka Membaca'; // Judul halaman

        // Ambil 5 buku dengan rating rata-rata tertinggi dari peminjaman
        $topBooks = Peminjaman::select('id_buku', DB::raw('AVG(rating) as avg_rating'))
            ->whereNotNull('rating')
            ->groupBy('id_buku')
            ->orderByDesc('avg_rating')
            ->with('buku')
            ->limit(5)
            ->get();

        // Ambil 3 buku terbaru
        $bukuBaru = Buku::orderBy('created_at', 'desc')->paginate(3);

        // Ambil 3 data peminjaman terbaru yang masih belum dikembalikan
        $user = auth()->user();

        if ($user && $user->anggota) {
            $idAnggota = $user->anggota->id;

            $peminjamanAnggota = Peminjaman::with('anggota', 'buku')
                ->where('id_anggota', $idAnggota)
                ->whereIn('status_pengembalian', ['Belum diambil', 'Dipinjam'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        } else {
            // Tangani kasus jika user belum login atau belum punya data anggota
            $peminjamanAnggota = collect(); // kosongkan koleksi
        }


        return view('user.components.beranda', compact(
            'volunteers',
            'title',
            'topBooks',
            'bukuBaru',
            'peminjamanAnggota'
        ));
    }

    public function profileAnggota()
    {
        // 1. Pastikan pengguna sudah login
        $akun = auth()->user();
        if (!$akun) {
            return redirect()->route('login')
                            ->withErrors('Harap login terlebih dahulu.');
        }

        // 2. Ambil data anggota milik akun tersebut melalui relasi
        $anggota = $akun->anggota;

        // 3. Jika data anggota belum dibuat, arahkan ke form isi profil
        if (!$anggota) {
            return view('user.components.profile.profile', [
                'title' => 'Lengkapi Profil',
                'akun'  => $akun
            ]);
        }

        // 4. Jika sudah ada, tampilkan profil lengkap
        return view('user.components.profile.profile', [
            'title'   => 'Profil Saya',
            'akun'    => $akun,
            'anggota' => $anggota,
        ]);
    }

    public function layoutsChat()
    {
        $user = Auth::user(); // Ambil data akun yang login

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        return view('admin.components.chat', [
            'title' => 'Halaman Chaat',
        ]);


    }

    public function settingAdmin(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            // Kalau user belum login, redirect ke login dengan pesan error
            return redirect()->route('login')->withErrors('Harap login dulu');
        }

        // Pastikan user adalah admin
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
        }

            // Ambil semua data anggota untuk pilihan dropdown atau kebutuhan lain
        $settingLists = Page::all();

        return view('admin.components.setting', [
            'userData' => $user,
            'settingLists' => $settingLists,
            'title' => 'Setting Admin',
        ]);
    }

    public function layoutsPeminjamanDenda()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')
                            ->withErrors('Harap login terlebih dahulu.');
        }

        // Ambil hanya data peminjaman yang status_pengembalian-nya 'Kembalikan' DAN memiliki denda
        $peminjamans = Peminjaman::where('status_pengembalian', 'Kembalikan')
                                ->where('denda', '>', 0)
                                ->get();

        // Ambil semua data anggota
        $anggotaList = Anggota::all();

        // Ambil semua data buku
        $bukuList = Buku::all();

        return view('admin.components.denda', [
            'title'       => 'Halaman Denda',
            'peminjamans' => $peminjamans,
            'anggotaList' => $anggotaList,
            'bukuList'    => $bukuList,
        ]);
    }

    public function logs_activity(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors('Harap login terlebih dahulu.');
        }

        $role = $user->role;
        $userDetail = null;

        // Validasi akses admin / voluntter
        if ($role === 'admin') {
            $userDetail = Admin::where('id_akun', $user->id)->first();
        } elseif ($role === 'voluntter') {
            $userDetail = Voluntter::where('id_akun', $user->id)->first();
        }

        if (!$userDetail) {
            return redirect()->route('dashboard')->withErrors('Data pengguna tidak ditemukan.');
        }

        // Ambil semua user_id yang punya log
        $userIdsWithLogs = ActivityLog::pluck('user_id')->unique()->toArray();

        // Ambil anggota dan voluntter yang memiliki log
        $anggotas = Anggota::with('akun')
            ->whereHas('akun', function ($query) use ($userIdsWithLogs) {
                $query->where('role', 'anggota')
                    ->whereIn('id', $userIdsWithLogs);
            })
            ->get();

        $voluntters = Voluntter::with('akun')
            ->whereHas('akun', function ($query) use ($userIdsWithLogs) {
                $query->where('role', 'voluntter')
                    ->whereIn('id', $userIdsWithLogs);
            })
            ->get();

        // Gabungkan dan urutkan berdasarkan nama
        $penggunas = collect($anggotas)->merge($voluntters)->sortBy('nama')->values();

        // Manual pagination (karena pakai Collection biasa)
        $page = $request->get('page', 1);
        $perPage = 5;
        $offset = ($page - 1) * $perPage;

        $paginatedPenggunas = new LengthAwarePaginator(
            $penggunas->slice($offset, $perPage)->values(),
            $penggunas->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil log umum jika diperlukan
        $logAktivitas = ActivityLog::with('akun')
            ->whereIn('user_id', $userIdsWithLogs)
            ->latest()
            ->take(20)
            ->get();

        return view('admin.components.logs_activity', [
            'userDetail'    => $userDetail,
            'userData'      => $user,
            'title'         => 'Log Aktivitas Pengguna',
            'penggunas'     => $paginatedPenggunas, // sudah paginasi
            'logAktivitas'  => $logAktivitas,
        ]);
    }
}

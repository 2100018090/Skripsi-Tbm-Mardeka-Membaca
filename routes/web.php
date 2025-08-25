<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing page
Route::get('/', [LayoutController::class, 'landingpage'])->name('landingpage');

// Volunteer landing page
Route::get('/volunteer', function () {
    return view('voluntter.layouts.default.landing_page', ['title' => 'Landing Page Volunteer']);
})->name('volunteer_landing');

// Authentication routes
Route::get('/masuk', function () {
    return view('user.autentikasi.login', ['title' => 'Halamaan Masuk']);
})->name('masuk');

Route::get('/daftar', function () {
    return view('user.autentikasi.registrasi', ['title' => 'Halaman Daftar']);
})->name('daftar');

// halaman Anggota

Route::prefix('anggota')->name('anggota.')->middleware('check.user.cookie')->group(function () {

    Route::get('/profileAnggota', [LayoutController::class, 'profileAnggota'])->name('profileAnggota');

    Route::get('/layoutsRiwayat', [LayoutController::class, 'layoutsRiwayat'])->name('layoutsRiwayat');

    Route::post('/pinjamLangsung', [SettingController::class, 'pinjamLangsung'])->name('pinjamLangsung');

    Route::get('/anggota_pengumuman', [LayoutController::class, 'anggota_pengumuman'])->name('anggota_pengumuman');

    Route::get('/layoutskoleksi', [LayoutController::class, 'layoutskoleksi'])->name('layoutskoleksi');

    Route::post('/peminjaman/{id}/rating', [SettingController::class, 'storeRating'])->name('storeRating');

    Route::get('/find-buku/{id}', [SettingController::class, 'findBuku'])->name('findBukuById');

    Route::get('/get-snap-token/{id}', [SettingController::class, 'getSnapToken'])->name('bayarDenda');

    Route::post('/bayar-denda', [SettingController::class, 'bayarDenda'])->name('denda');


    // Route::get('/koleksi', function () {
    //     return view('user.components.koleksi', ['title' => 'Koleksi']);
    // })->name('koleksi');

    Route::get('/artikel', function () {
        return view('user.layouts.default.user.artikel', ['title' => 'Artikel']);
    })->name('artikel');

    Route::get('/berandaAnggota', [LayoutController::class, 'berandaAnggota'])->name('berandaAnggota');

});


// Volunteer Pages (Grouped under 'volunteer.' prefix)
Route::prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/tambah_buku', function () {
        return view('voluntter.layouts.default.tambah_buku', ['title' => 'Tambah Buku']);
    })->name('tambah_buku');

    Route::get('/tambah_pengumuman', function () {
        return view('voluntter.layouts.default.tambah_pengumuman', ['title' => 'Tambah Pengumuman']);
    })->name('tambah_pengumuman');

    Route::get('/edit_buku', function () {
        return view('voluntter.layouts.default.edit_buku', ['title' => 'Edit Buku']);
    })->name('edit_buku');

    Route::get('/edit_pengumuman', function () {
        return view('voluntter.layouts.default.edit_pengumuman', ['title' => 'Edit Pengumuman']);
    })->name('edit_pengumuman');

    Route::get('/tambah_kategori', function () {
        return view('voluntter.layouts.default.tambah_kategori', ['title' => 'Tambah Kategori']);
    })->name('tambah_kategori');

    Route::get('/edit_kategori', function () {
        return view('voluntter.layouts.default.edit_kategori', ['title' => 'Edit Kategori']);
    })->name('edit_kategori');

});

// Admin Pages (Grouped under 'admin.' prefix)
Route::prefix('admin')->name('admin.')->middleware('check.user.cookie')->group(function () {
    Route::get('/', [LayoutController::class, 'beranda'])->name('beranda');

    Route::get('/cariLaporanbuku', [LayoutController::class, 'cariLaporanbuku'])->name('cariLaporanbuku');

    Route::get('/data_anggota', [LayoutController::class, 'data_anggota'])->name('data_anggota');

    Route::get('/konfirmasiVoluntter', [LayoutController::class, 'konfirmasiVoluntter'])->name('konfirmasiVoluntter');

    Route::get('/data_voluntter', [LayoutController::class, 'data_voluntter'])->name('data_voluntter');

    Route::get('/logs_activity', [LayoutController::class, 'logs_activity'])->name('logs_activity');

    Route::get('/profileAdmin', [LayoutController::class, 'profileAdmin'])->name('profileAdmin');

    Route::get('/pengumuman', [LayoutController::class, 'pengumuman'])->name('pengumuman');

    Route::post('/uploadImg', [SettingController::class, 'uploadImg'])->name('uploadImg');

    Route::post('/createAnggota', [Controller::class, 'createAnggota'])->name('createAnggota');

    Route::get('/findAnggotaId/{id}', [Controller::class, 'findAnggotaId'])->name('findAnggotaId');

    Route::put('/updateAnggota/{id}', [Controller::class, 'updateAnggota'])->name('updateAnggota');

    Route::delete('/deleteAnggota/{id}', [Controller::class, 'deleteAnggota'])->name('deleteAnggota');

    Route::get('/cariAnggota', [Controller::class, 'cariAnggota'])->name('cariAnggota');

    Route::post('/createVoluntter', [Controller::class, 'createVoluntter'])->name('createVoluntter');

    Route::get('/findVoluntterId/{id}', [Controller::class, 'findVoluntterId'])->name('findVoluntterId');

    Route::put('/updateVoluntter/{id}', [Controller::class, 'updateVoluntter'])->name('updateVoluntter');
    
    Route::get('/cariVoluntter', [Controller::class, 'cariVoluntter'])->name('cariVoluntter');

    Route::get('/cariPenggunaLog', [Controller::class, 'cariPenggunaLog'])->name('cariPenggunaLog');

    Route::delete('/deleteVoluntter/{id}', [Controller::class, 'deleteVoluntter'])->name('deleteVoluntter');

    Route::post('/logout', [Controller::class, 'logout'])->name('logout');
    // fungsi pengumuman
    Route::post('/createPengumuman', [SettingController::class, 'createPengumuman'])->name('createPengumuman');

    Route::get('/findPengumumanId/{id}', [SettingController::class, 'findPengumumanId'])->name('findPengumumanId');

    Route::put('/updatePengumuman/{id}', [SettingController::class, 'updatePengumuman'])->name('updatePengumuman');

    Route::delete('/deletePengumuman/{id}', [SettingController::class, 'deletePengumuman'])->name('deletePengumuman');

    Route::get('/cariPengumuman', [SettingController::class, 'cariPengumuman'])->name('cariPengumuman');

    Route::get('/cariBuku', [SettingController::class, 'cariBuku'])->name('cariBuku');

    // fungsi buku
    Route::get('/Data_Buku', [LayoutController::class, 'layoutsDataBuku'])->name('layoutsbukufisik');

    Route::get('/findBukuId/{id}', [SettingController::class, 'findBukuId'])->name('findBukuId');

    Route::put('/updateBuku/{id}', [SettingController::class, 'updateBuku'])->name('updateBuku');

    Route::delete('/deleteBuku/{id}', [SettingController::class, 'deleteBuku'])->name('deleteBuku');

    Route::get('/bukuByKategori/{id}', [SettingController::class, 'bukuByKategori'])->name('bukuByKategori');


    Route::get('/findPeminjamanId/{id}', [SettingController::class, 'findPeminjamanId'])->name('findPeminjamanId');

    Route::post('/createKategori', [SettingController::class, 'createKategori'])->name('createKategori');

    Route::post('/createBuku', [SettingController::class, 'createBuku'])->name('createBuku');

    Route::get('/layoutsPeminjaman', [LayoutController::class, 'layoutsPeminjaman'])->name('layoutsPeminjaman');

    Route::get('/cariPeminjaman', [Controller::class, 'cariPeminjaman'])->name('cariPeminjaman');

    Route::get('/LayouteditPeminjaman/{id}/edit', [LayoutController::class, 'LayouteditPeminjaman'])->name('LayouteditPeminjaman');

    Route::put('/updatePeminjaman/{id}', [SettingController::class, 'updatePeminjaman'])->name('updatePeminjaman');

    Route::delete('/deletePeminjaman/{id}', [SettingController::class, 'deletePeminjaman'])->name('deletePeminjaman');

    Route::get('/layoutsKonfirmasi', [LayoutController::class, 'layoutsKonfirmasi'])->name('layoutsKonfirmasi');

    Route::post('prosesKonfirmasi/{id}', [SettingController::class, 'prosesKonfirmasi'])->name('prosesKonfirmasi');

    Route::get('/getChartData', [SettingController::class, 'getChartData']);

    Route::get('/layoutsChat', [LayoutController::class, 'layoutsChat'])->name('layoutsChat');

    Route::get('/exportPeminjaman', [SettingController::class, 'exportPeminjaman'])->name('exportPeminjaman');

    Route::get('/exportAnggota', [SettingController::class, 'exportAnggota'])->name('exportAnggota');

    Route::get('/exportVoluntter', [SettingController::class, 'exportVoluntter'])->name('exportVoluntter');

    Route::get('/settingAdmin', [LayoutController::class, 'settingAdmin'])->name('settingAdmin');

    Route::post('/createSetting', [SettingController::class, 'createSetting'])->name('createSetting');

    Route::put('/updateSetting/{id}', [SettingController::class, 'updateSetting'])->name('updateSetting');

    Route::get('/findSettingById/{id}', [SettingController::class, 'findSettingById'])->name('findSettingById');

    Route::get('/deleteSettingId/{id}', [SettingController::class, 'deleteSettingId'])->name('deleteSettingId');

    Route::put('/admin/kembalikan/{id}', [SettingController::class, 'kembalikan'])->name('kembalikanBuku');

    Route::get('/layoutsPeminjamanDenda', [LayoutController::class, 'layoutsPeminjamanDenda'])->name('layoutsPeminjamanDenda');

    Route::put('/konfirmasi-pembayaran/{id}', [SettingController::class, 'konfirmasiPembayaran'])->name('konfirmasiDenda');

    Route::put('/transaksiGagal/{id}', [SettingController::class, 'transaksiGagal'])->name('transaksiGagal');

    Route::post('/bayar-cash/{id}', [SettingController::class, 'bayarCash'])->name('bayarCash');
});

// Admin Fungsi (Grouped under 'akun.' prefix)
Route::prefix('akun')->name('akun.')->group(function () {
    Route::post('/', [Controller::class, 'createAkun'])->name('createAkun');
    Route::get('/', [Controller::class, 'getAllAkun']);
    Route::get('/{id}', [Controller::class, 'findAkunId']);
    Route::put('/{id}', [Controller::class, 'updateAkunId']);
    Route::delete('/{id}', [Controller::class, 'deleteAkunId']);

    Route::post('/createOnlyAnggota', [SettingController::class, 'createOnlyAnggota'])->name('createOnlyAnggota');
    Route::put('/akun/anggota/{id}', [SettingController::class, 'updateOnlyAnggota'])->name('updateOnlyAnggota');
});



    Route::get('/voluntter/login', function () {
        return view('user.autentikasi.loginVoluntter', ['title' => 'Login Voluntter dan Admin']);
    })->name('loginVoluntter');

    Route::get('/403page', function () {
        return view('user.autentikasi.403page', ['title' => '403']);
    })->name('403page');

    Route::post('/prosesLogin', [Controller::class, 'prosesLogin'])->name('prosesLogin');
    Route::post('/loginVoluntter', [Controller::class, 'loginVoluntter'])->name('loginVoluntter');

//  Route::post('/prosesLogin', [Controller::class, 'prosesLogin'])->name('prosesLogin');
    Route::post('/admin/create', [Controller::class, 'createAdmin']);


 // Admin Fungsi (Grouped under 'akun.' prefix)
Route::prefix('chat')->name('chat.')->group(function () {

    Route::post('/chat/send-anggota', [ChatController::class, 'sendFromAnggota'])->name('sendFromAnggota');
    Route::post('/chat/send-volunteer', [ChatController::class, 'sendFromVolunteer'])->name('sendFromVolunteer');
    Route::get('/chat/messages', [ChatController::class, 'getChat'])->name('getChat');

});


Route::prefix('buku')->name('buku.')->group(function () {

    Route::get('/baca-buku/digital/{id}', [SettingController::class, 'bacaByDigital'])->name('baca');
    
});

Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.getMessages');
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

// uji coba
Route::post('/upload', [SettingController::class, 'upload']);

Route::get('/view-pdf/{filename}', [SettingController::class, 'viewPdf']);

Route::get('/Buku/{id}', [SettingController::class, 'Buku'])->name('Buku');

Route::post('/notifikasi/baca', [SettingController::class, 'tandaiSudahDibaca'])->name('notifikasi.baca');

Route::post('/peminjaman/{id}/tolak', [SettingController::class, 'tolakPengembalian'])->name('peminjaman.tolak');

Route::get('/verifikasi-email', [Controller::class, 'verifikasiEmail'])->name('verifikasi.email');

Route::post('/admin/buku/{id}/update-stok', [Controller::class, 'updateStokFisik'])->name('updateStokBuku');


Route::get('/cek-akun', function () {
    $anggotas = \App\Models\Anggota::with('akun')->get();

    foreach ($anggotas as $anggota) {
        echo "<pre>";
        echo "Nama: {$anggota->nama}\n";
        echo "Email: " . optional($anggota->akun)->email ?? 'TIDAK ADA' . "\n";
        echo "</pre>";
    }
});

Route::get('/api/detail-buku/{id}', [Controller::class, 'getDetailBukuByBukuId']);

Route::post('/peminjaman/baru', [Controller::class, 'buatPeminjamanLengkap'])->name('peminjaman.baru');

Route::post('/log-pengumuman', [SettingController::class, 'logLihatPengumuman'])->name('log.pengumuman');


Route::post('/lupa-password', [Controller::class, 'kirimLinkReset'])->name('password.kirim');
Route::get('/reset-password/{token}', [Controller::class, 'formReset'])->name('password.reset.form');
Route::post('/reset-password', [Controller::class, 'resetPassword'])->name('password.reset');

Route::get('/anggota/{id}/apply', [Controller::class, 'apply'])->name('anggota.apply');
Route::put('/admin/tolak-anggota/{id}', [Controller::class, 'reject'])->name('admin.tolakAnggota');
Route::put('/admin/setuju-anggota/{id}', [Controller::class, 'approve'])->name('admin.setujuAnggota');
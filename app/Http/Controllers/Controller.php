<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Admin;
use App\Models\Akun;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Voluntter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerifyEmail;
use App\Models\ActivityLog;
use App\Models\Detailbuku;
use App\Models\Fisik;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function createAkun(Request $request)
    {
        // ✅ Validasi awal + reCAPTCHA
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('akuns', 'email'),
            ],
            'username' => 'required|string|max:15',
            'password' => 'required|string|min:6',
            'role' => [
                'required',
                Rule::in(['admin', 'anggota', 'volunteer']),
            ],
            'img_identitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'g-recaptcha-response' => 'required', // ✅ reCAPTCHA wajib
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal 15 karakter.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',

            'img_identitas.image' => 'File harus berupa gambar.',
            'img_identitas.mimes' => 'Format gambar hanya boleh JPG, JPEG, atau PNG.',
            'img_identitas.max' => 'Ukuran gambar maksimal 2MB.',

            'g-recaptcha-response.required' => 'Verifikasi CAPTCHA wajib dilakukan.',
        ]);

        // ✅ Verifikasi reCAPTCHA ke Google
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY'); // dari Google
        $recaptchaResponse = $request->input('g-recaptcha-response');

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $captchaSuccess = $verify->json();

        if (empty($captchaSuccess['success']) || $captchaSuccess['success'] !== true) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi CAPTCHA gagal, silakan coba lagi.'
            ])->withInput();
        }

        // ✅ Cek jika role admin hanya boleh satu
        if ($validated['role'] === 'admin' && Akun::where('role', 'admin')->exists()) {
            return back()->withErrors(['role' => 'Akun admin sudah ada. Tidak bisa membuat lebih dari satu.'])->withInput();
        }

        // Buat token verifikasi email
        $token = Str::random(64);

        // Default imgPath null
        $imgPath = null;

        // ✅ Upload gambar identitas ke Supabase
        if ($request->hasFile('img_identitas')) {
            $file     = $request->file('img_identitas');
            $ext      = $file->getClientOriginalExtension();
            $filename = 'identitas-' . Str::uuid() . '.' . $ext;
            $filePath = "identitas/{$filename}";
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
                    'img_identitas' => 'Upload gambar ke Supabase gagal: ' . $response->body()
                ])->withInput();
            }

            $imgPath = $filePath;
        }

        try {
            $akun = Akun::create([
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'session_token' => Str::random(60),
                'email_verification_token' => $token,
                'email_verified_at' => null,
                'img_identitas' => $imgPath,
            ]);

            Mail::to($akun->email)->send(new VerifyEmail($akun));

            return redirect()->route('masuk')->with('success', 'Akun berhasil dibuat. Silakan cek email untuk verifikasi sebelum login.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage())->withInput();
        }
    }

    public function getAllAkun()
    {
        $semuaAkun = Akun::all(); // ambil semua data akun
        return response()->json([
            'status' => 'success',
            'message' => 'Data akun berhasil diambil',
            'data' => $semuaAkun
        ]);
    }

    public function findAkunId($id)
    {
        $akun = Akun::find($id);

        if (!$akun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $akun
        ]);
    }

    public function updateAkunId(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:akuns,email,' . $id,
            'username' => 'required|string|max:15',
            'role' => 'required|in:admin,anggota,voluntter',
            // password optional
        ]);

        // Cari data akun berdasarkan id
        $akun = Akun::find($id);
        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        // Update data akun
        $akun->email = $request->email;
        $akun->username = $request->username;
        $akun->role = $request->role;

        // Update password jika ada
        if ($request->filled('password')) {
            $akun->password = bcrypt($request->password);
        }

        $akun->save();

        return response()->json([
            'message' => 'Akun berhasil diupdate',
            'akun' => $akun
        ]);
    }

    public function deleteAkunId($id)
    {
        $akun = Akun::find($id);
        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $akun->delete();

        return response()->json(['message' => 'Akun berhasil dihapus'], 200);
    }

    public function prosesLogin(Request $request)
    {
        // 1. Validasi input + reCAPTCHA
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'g-recaptcha-response' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'g-recaptcha-response.required' => 'Silakan selesaikan verifikasi CAPTCHA.'
        ]);

        // 2. Verifikasi reCAPTCHA
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaResponse = $request->input('g-recaptcha-response');

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $captchaSuccess = $verify->json();

        if (empty($captchaSuccess['success']) || $captchaSuccess['success'] !== true) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi CAPTCHA gagal, silakan coba lagi.'
            ])->withInput();
        }

        // 3. Cek login ganda
        $cacheKey = 'user_logged_in_' . $request->email;
        $currentSessionId = session()->getId();

        if (Cache::has($cacheKey)) {
            $cachedSessionId = Cache::get($cacheKey);

            if ($cachedSessionId !== $currentSessionId) {
                return redirect()->back()->with('error', 'Maaf, akun ini sedang digunakan di perangkat lain.');
            }
        }

        // 4. Ambil credentials tanpa field reCAPTCHA
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.'
            ])->onlyInput('email');
        }

        $akun = Auth::user();

        // 5. Hanya izinkan role anggota
        if ($akun->role !== 'anggota') {
            Auth::logout();
            return back()->with('error', 'Akses ditolak. Hanya anggota yang dapat login di halaman ini.');
        }

        // 6. Cek verifikasi email anggota
        if (is_null($akun->email_verified_at)) {
            Auth::logout();
            return back()->with('error', 'Email belum diverifikasi. Silakan cek email Anda.');
        }

        // 7. Regenerasi session & simpan session baru di cache
        $request->session()->regenerate();
        $newSessionId = session()->getId();
        Cache::put($cacheKey, $newSessionId, now()->addMinutes(config('session.lifetime')));

        // 8. Ubah status jadi aktif
        Anggota::where('id_akun', $akun->id)->update(['status' => 'active']);

        // 9. Catat aktivitas login anggota
        LogActivity::add('Login', 'Akun anggota ' . $akun->email . ' berhasil login.');

        // 10. Redirect ke beranda anggota
        return redirect()->route('anggota.berandaAnggota')
            ->with('success', 'Selamat datang! Silakan menikmati buku yang tersedia.');
    }

    public function loginVoluntter(Request $request)
    {
        // 1. Validasi input + reCAPTCHA
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'g-recaptcha-response' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'g-recaptcha-response.required' => 'Silakan selesaikan verifikasi CAPTCHA.'
        ]);

        // 2. Verifikasi reCAPTCHA
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaResponse = $request->input('g-recaptcha-response');

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        $captchaSuccess = $verify->json();

        if (empty($captchaSuccess['success']) || $captchaSuccess['success'] !== true) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi CAPTCHA gagal, silakan coba lagi.'
            ])->withInput();
        }

        // 3. Cek login ganda
        $cacheKey = 'user_logged_in_' . $request->email;
        $currentSessionId = session()->getId();

        if (Cache::has($cacheKey)) {
            $cachedSessionId = Cache::get($cacheKey);

            if ($cachedSessionId !== $currentSessionId) {
                return redirect()->back()->with('error', 'Maaf, akun ini sedang digunakan di perangkat lain.');
            }
        }

        // 4. Ambil credentials
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.'
            ])->onlyInput('email');
        }

        $akun = Auth::user();

        // 5. Izinkan admin, voluntter, dan anggota dengan akses 'plus'
        if (!in_array($akun->role, ['admin', 'voluntter'])) {
            if ($akun->role === 'anggota') {
                // Ambil data anggota
                $anggota = Anggota::where('id_akun', $akun->id)->first();

                // Cek apakah anggota punya akses plus
                if ($anggota && strtolower($anggota->akses) === 'plus') {
                    // Login sebagai voluntter tanpa ubah role asli
                    session(['temporary_role' => 'voluntter']);
                } else {
                    Auth::logout();
                    return back()->with('error', 'Akses ditolak. Anda harus menjadi voluntter untuk login di halaman ini.');
                }
            } else {
                Auth::logout();
                return back()->with('error', 'Akses ditolak. Hanya admin atau voluntter yang dapat login di halaman ini.');
            }
        }

        // 6. Regenerasi session & simpan di cache
        $request->session()->regenerate();
        $newSessionId = session()->getId();
        Cache::put($cacheKey, $newSessionId, now()->addMinutes(config('session.lifetime')));

        // 7. Update status jadi aktif jika voluntter
        if ($akun->role === 'voluntter' || session('temporary_role') === 'voluntter') {
            Voluntter::where('id_akun', $akun->id)->update(['status' => 'active']);
        }

        // 8. Catat aktivitas login
        LogActivity::add('Login', 'Akun ' . $akun->email . ' berhasil login sebagai ' . ($akun->role === 'anggota' ? 'voluntter' : $akun->role));

        // 9. Redirect sesuai role atau temporary role
        if ($akun->role === 'admin') {
            return redirect()->route('admin.beranda')->with('success', 'Login admin berhasil!');
        }

        if ($akun->role === 'voluntter' || session('temporary_role') === 'voluntter') {
            return redirect()->route('admin.beranda')->with('success', 'Login voluntter berhasil!');
        }
    }


    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors('Tidak ada pengguna yang sedang login.');
        }

        //  Ubah status ke offline untuk anggota dan voluntter
        if ($user->role === 'anggota') {
            Anggota::where('id_akun', $user->id)->update(['status' => 'offline']);
        } elseif ($user->role === 'voluntter') {
            Voluntter::where('id_akun', $user->id)->update(['status' => 'offline']);
        }

        //  Logging ke file log
        Log::info('Logout berhasil', [
            'email' => $user->email,
            'role'  => $user->role,
            'ip'    => $request->ip(),
        ]);

        // 🟢 3. Tambahkan ke activity_logs (hanya untuk anggota & voluntter)
        if (in_array($user->role, ['anggota', 'voluntter'])) {
            LogActivity::add('Logout', 'Akun dengan email ' . $user->email . ' telah logout.', $request->ip());
        }

        // Hapus cache session login
        Cache::forget('user_logged_in_' . $user->email);

        // Proses logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landingpage')->with('success', 'Silahkan Datang Kembali');
    }

    public function createAnggota(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email|unique:akuns,email',
            'username' => 'required|string|max:255|unique:akuns,username',
            'password' => 'required|string|min:6',
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string',
            'notlp'    => 'required|string|max:20',
            'status'   => 'nullable|in:active,offline',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $akun = Akun::create([
                'email'             => $validated['email'],
                'username'          => $validated['username'],
                'password'          => Hash::make($validated['password']),
                'role'              => 'anggota',
                'session_token'     => Str::random(60),
                'email_verified_at' => now(),
            ]);

            $imgPath = null;

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

                $imgPath = $filePath;
            }

            Anggota::create([
                'id_akun' => $akun->id,
                'nama'    => $validated['nama'],
                'alamat'  => $validated['alamat'],
                'notlp'   => $validated['notlp'],
                'status'  => $validated['status'] ?? 'offline',
                'img'     => $imgPath,
            ]);

            DB::commit();

            return redirect()->route('admin.data_anggota')->with('success', '✅ Anggota berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat anggota: ' . $e->getMessage());
        }
    }

    public function findAnggotaId($id)
    {
        $anggota = Anggota::with('akun')->find($id);
        if (!$anggota) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }
        return response()->json($anggota);
    }

    public function updateAnggota(Request $request, $id)
    {
        $request->validate([
            'email'    => 'required|email',
            'username' => 'required',
            'nama'     => 'required',
            'alamat'   => 'required',
            'notlp'    => 'required',
            'status'   => 'required|in:active,offline',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $anggota = Anggota::with('akun')->findOrFail($id);
            $akun    = $anggota->akun;

            // --- Update akun ---
            $akun->email    = $request->email;
            $akun->username = $request->username;
            $akun->save();

            $imgPath = $anggota->img;

            // === Upload gambar baru jika ada ===
            if ($request->hasFile('img')) {
                // --- Hapus gambar lama dari Supabase jika ada ---
                if ($imgPath) {
                    $deleteUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/' .
                                env('SUPABASE_BUCKET') . '/' . $imgPath;

                    $deleteResp = Http::withHeaders([
                        'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    ])->delete($deleteUrl);

                    // Hanya log error jika gagal, tapi tidak menghentikan proses
                    if (!$deleteResp->successful()) {
                        Log::warning('Gagal menghapus gambar lama di Supabase', [
                            'response' => $deleteResp->body()
                        ]);
                    }
                }

                // --- Upload gambar baru ke Supabase ---
                $file     = $request->file('img');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'anggota-' . Str::uuid() . '.' . $ext;
                $filePath = "anggota/{$filename}";
                $fileBytes = file_get_contents($file->getRealPath());

                $uploadUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/' .
                            env('SUPABASE_BUCKET') . '/' . $filePath;

                $uploadResp = Http::withHeaders([
                    'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody($fileBytes, $file->getMimeType())
                ->put($uploadUrl);

                if (!$uploadResp->successful()) {
                    return back()->withErrors([
                        'img' => 'Gagal upload gambar baru ke Supabase: ' . $uploadResp->body()
                    ])->withInput();
                }

                $imgPath = $filePath;
            }

            // --- Update data anggota ---
            $anggota->nama   = $request->nama;
            $anggota->alamat = $request->alamat;
            $anggota->notlp  = $request->notlp;
            $anggota->status = $request->status;
            $anggota->img    = $imgPath;
            $anggota->save();

            DB::commit();

            return redirect()->route('admin.data_anggota')->with('success', '✅ Anggota berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Gagal memperbarui anggota: ' . $e->getMessage());
        }
    }

    public function deleteAnggota($id)
    {
        Log::info('Memulai proses hapus akun dan anggota', ['id' => $id]);

        try {
            $akun = Akun::findOrFail($id);

            // Cek apakah akun ini adalah anggota
            if ($akun->anggota) {
                // Hapus data anggota
                $akun->anggota->delete();

                // Baru hapus akun
                $akun->delete();

                return redirect()->route('admin.data_anggota')->with('success', 'Anggota dan akun berhasil dihapus.');
            } else {
                return redirect()->route('admin.data_anggota')->with('error', 'Akun ini bukan anggota.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus akun atau anggota', ['error' => $e->getMessage()]);
            return redirect()->route('admin.data_anggota')->with('error', 'Gagal menghapus akun dan anggota.');
        }
    }

    public function cariAnggota(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));

        // ✅ Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('masuk')->withErrors('Harap login terlebih dahulu.');
        }

        $user = Auth::user(); // jika kamu butuh menampilkannya

        // ✅ Query dasar: hanya akun dengan role "anggota"
        $query = Anggota::with('akun')
            ->whereHas('akun', function ($q) {
                $q->where('role', 'anggota');
            });

        // ✅ Tambahkan filter pencarian jika ada keyword
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'LIKE', "%{$keyword}%")
                ->orWhereHas('akun', function ($q2) use ($keyword) {
                    $q2->where('email', 'LIKE', "%{$keyword}%");
                });
            });
        }

        // ✅ Paginate hasil
        $anggotas = $query->paginate(10)->appends(['keyword' => $keyword]);

        // ✅ Jika request AJAX (live search)
        if ($request->ajax()) {
            return view('admin.components.data_anggota', compact('anggotas'))->render();
        }

        // ✅ Full page
        return view('admin.components.data_anggota', [
            'title'    => 'Data Anggota',
            'anggotas' => $anggotas,
            'keyword'  => $keyword,
            'user'     => $user,
        ]);
    }

    public function createVoluntter(Request $request)
    {
        try {
            $validated = $request->validate([
                'email'    => 'required|email|unique:akuns,email',
                'username' => 'required|string|max:255|unique:akuns,username',
                'password' => 'required|string|min:6',
                'nama'     => 'required|string|max:255',
                'jabatan'  => 'required|string|max:100',
                'status'   => 'nullable|in:active,offline',
                'img'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // ✅ Buat akun dengan token dan email_verified langsung
            $akun = Akun::create([
                'email'             => $validated['email'],
                'username'          => $validated['username'],
                'password'          => Hash::make($validated['password']),
                'role'              => 'voluntter',
                'session_token'     => Str::random(60),
                'email_verified_at' => now(), // ✅ langsung dianggap terverifikasi
            ]);

            $imgPath = null;

            if ($request->hasFile('img')) {
                $file     = $request->file('img');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'voluntter-' . Str::uuid() . '.' . $ext;
                $filePath = "voluntter/{$filename}";
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

            Voluntter::create([
                'id_akun' => $akun->id,
                'nama'    => $validated['nama'],
                'jabatan' => $validated['jabatan'],
                'status'  => $validated['status'] ?? 'offline',
                'img'     => $imgPath,
            ]);

            DB::commit();

            return redirect()->route('admin.data_voluntter')
                ->with('success', '✅ Voluntter berhasil dibuat!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->withErrors([
                    'email' => 'Email atau Username sudah digunakan.'
                ])->withInput();
            }

            Log::error('Gagal membuat voluntter (QueryException)', [
                'error_message' => $e->getMessage(),
                'request_data'  => $request->except('password'),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan ke database.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal membuat voluntter', [
                'error_message' => $e->getMessage(),
                'request_data'  => $request->except('password'),
            ]);
            return back()->with('error', 'Gagal membuat voluntter: ' . $e->getMessage());
        }
    }

    public function findVoluntterId($id)
    {
        $voluntter = Voluntter::with('akun')->find($id);
        if (!$voluntter) {
            return response()->json(['message' => 'Voluntter tidak ditemukan'], 404);
        }
        return response()->json($voluntter);
    }

    public function updateVoluntter(Request $request, $id)
    {
        $request->validate([
            'email'    => 'required|email',
            'username' => 'required|string|max:255',
            'nama'     => 'required|string|max:255',
            'jabatan'  => 'required|string|max:100',
            'status'   => 'required|in:active,offline',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $voluntter = Voluntter::with('akun')->findOrFail($id);
            $akun = $voluntter->akun;

            // Update akun
            $akun->email = $request->email;
            $akun->username = $request->username;
            $akun->save();

            // Jika ada gambar baru
            if ($request->hasFile('img')) {
                // Hapus file lama dari Supabase
                if ($voluntter->img) {
                    $deleteUrl = rtrim(env('SUPABASE_URL'), '/') .
                        "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$voluntter->img}";

                    $deleteResponse = Http::withHeaders([
                        'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    ])->delete($deleteUrl);

                    if (!$deleteResponse->successful()) {
                        Log::warning('Gagal hapus gambar lama Supabase', [
                            'path'     => $voluntter->img,
                            'response' => $deleteResponse->body()
                        ]);
                    }
                }

                // Upload gambar baru
                $file     = $request->file('img');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'voluntter-' . Str::uuid() . '.' . $ext;
                $filePath = "voluntter/{$filename}";
                $fileBytes = file_get_contents($file->getPathname());

                $uploadUrl = rtrim(env('SUPABASE_URL'), '/') .
                    "/storage/v1/object/" . env('SUPABASE_BUCKET') . "/{$filePath}";

                $uploadResponse = Http::withHeaders([
                    'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type'  => $file->getMimeType(),
                ])->withBody($fileBytes, $file->getMimeType())->put($uploadUrl);

                if (!$uploadResponse->successful()) {
                    DB::rollBack();
                    return back()->withErrors([
                        'img' => 'Upload gambar ke Supabase gagal: ' . $uploadResponse->body()
                    ])->withInput();
                }

                $voluntter->img = $filePath;
            }

            $voluntter->nama = $request->nama;
            $voluntter->jabatan = $request->jabatan;
            $voluntter->status = $request->status;
            $voluntter->save();

            DB::commit();

            return redirect()->route('admin.data_voluntter')->with('success', '✅ Data voluntter berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal update voluntter', [
                'error_message' => $e->getMessage(),
                'request_data'  => $request->except('password'),
            ]);
            return back()->with('error', 'Gagal update voluntter: ' . $e->getMessage());
        }
    }

    public function cariVoluntter(Request $request)
    {
        // 1. Ambil keyword dan bersihkan spasi
        $keyword = trim($request->input('keyword', ''));

        // 2. Validasi: user harus login
        if (!Auth::check()) {
            return redirect()->route('masuk')->withErrors('Harap login terlebih dahulu.');
        }

        $user = Auth::user(); // jika ingin ditampilkan di view

        // 3. Query dasar: hanya akun dengan role 'voluntter'
        $query = Voluntter::with('akun')
            ->whereHas('akun', function ($q) {
                $q->where('role', 'voluntter');
            });

        // 4. Tambahkan filter keyword jika ada
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'LIKE', "%{$keyword}%")
                ->orWhereHas('akun', function ($q2) use ($keyword) {
                    $q2->where('email', 'LIKE', "%{$keyword}%");
                });
            });
        }

        // 5. Paginate hasil
        $voluntters = $query->paginate(10)->appends(['keyword' => $keyword]);

        // 6. Jika request AJAX (misalnya live search)
        if ($request->ajax()) {
            return view('admin.components.data_voluntter', compact('voluntters'))->render();
        }

        // 7. Full page
        return view('admin.components.data_voluntter', [
            'title'      => 'Data Voluntter',
            'voluntters' => $voluntters,
            'keyword'    => $keyword,
            'user'       => $user,
        ]);
    }

    public function deleteVoluntter($id)
    {
        Log::info('Memulai proses hapus akun dan voluntter', ['id' => $id]);

        try {
            $akun = Akun::findOrFail($id);

            // Pastikan ini adalah akun voluntter
            if ($akun->voluntter) {
                $imgPath = $akun->voluntter->img;

                // Hapus gambar di Supabase jika ada
                if ($imgPath) {
                    $deleteUrl = rtrim(env('SUPABASE_URL'), '/') .
                        "/storage/v1/object/" .
                        env('SUPABASE_BUCKET') . "/{$imgPath}";

                    $deleteResponse = Http::withHeaders([
                        'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                        'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    ])->delete($deleteUrl);

                    if (!$deleteResponse->successful()) {
                        Log::warning('Gagal menghapus gambar di Supabase', [
                            'path' => $imgPath,
                            'response' => $deleteResponse->body()
                        ]);
                    }
                }

                // Hapus data voluntter dan akun
                $akun->voluntter->delete();
                $akun->delete();

                return redirect()->route('admin.data_voluntter')
                    ->with('success', 'Voluntter dan akun berhasil dihapus.');
            } else {
                return redirect()->route('admin.data_voluntter')
                    ->with('error', 'Akun ini bukan Voluntter.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghapus akun atau voluntter', [
                'error' => $e->getMessage()
            ]);
            return redirect()->route('admin.data_voluntter')
                ->with('error', 'Gagal menghapus akun dan voluntter.');
        }
    }

    public function cariPeminjaman(Request $request)
    {
        // -------- 1. Ambil & rapikan keyword --------
        $keyword = trim($request->input('keyword', ''));

        // -------- 2. Validasi sesi admin seperti biasa --------
        if (!Auth::check()) {
            return redirect()->route('masuk')
                            ->withErrors('Harap login terlebih dahulu.');
        }

        $user = Auth::user();
        abort_if($user->role !== 'admin', 403, 'Hanya admin yang dapat mengakses.');

        // -------- 3. Query Peminjaman dengan relasi --------
        $query = Peminjaman::with(['anggota', 'buku']);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('anggota', function ($anggotaQuery) use ($keyword) {
                    $anggotaQuery->where('nama', 'LIKE', "%{$keyword}%");
                })->orWhereHas('buku', function ($bukuQuery) use ($keyword) {
                    $bukuQuery->where('judul', 'LIKE', "%{$keyword}%");
                });
            });
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(20);

        // ✅ Ambil semua data anggota
        $anggotaList = Anggota::all();
        $bukuList    = Buku::all();
        $bukuFisik = Buku::where('tipe', 'fisik')->get();

        // -------- 5. Response: AJAX vs halaman penuh --------
        if ($request->ajax()) {
            return view('admin.components.data_anggota', compact('peminjamans'))->render();
        }

        return view('admin.components.peminjaman', [
            'title'        => 'Data Peminjaman',
            'peminjamans'  => $peminjamans,
            'keyword'      => $keyword,
            'bukuList'    => $bukuList,
            'bukuFisik' => $bukuFisik,
            'anggotaList'  => $anggotaList, // ✅ ditambahkan
        ]);
    }

    public function cariBuku(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));

        // ✅ Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('masuk')->withErrors('Harap login terlebih dahulu.');
        }

        // Dapatkan user jika diperlukan
        $user = Auth::user();

        // ✅ Sertakan relasi kategori untuk efisiensi query
        $query = Buku::with('kategori');

        // Filter pencarian jika ada keyword
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'LIKE', "%{$keyword}%")
                ->orWhere('penulis', 'LIKE', "%{$keyword}%")
                ->orWhereHas('kategori', function ($q2) use ($keyword) {
                    $q2->where('nama', 'LIKE', "%{$keyword}%");
                });
            });
        }

        // Paginate hasil
        $bukus = $query->paginate(10)->appends(['keyword' => $keyword]);

        // Jika request AJAX (live search)
        if ($request->ajax()) {
            return view('components.data_buku', compact('bukus'))->render();
        }

        // Full page
        return view('admin.components.buku', [
            'title'   => 'Data Buku',
            'bukus'   => $bukus,
            'user'    => $user,
            'keyword' => $keyword,
        ]);
    }

    public function verifikasiEmail(Request $request)
    {
        $akun = Akun::where('email_verification_token', $request->query('token'))->first();

        if (!$akun) {
            return redirect()->route('masuk')->with('error', 'Token tidak valid atau sudah digunakan.');
        }

        $akun->update([
            'email_verification_token' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('masuk')->with('success', 'Email berhasil diverifikasi. Silakan login.');
    }

    public function updateStokFisik(Request $request, $id)
    {
        $validated = $request->validate([
            'fisik' => 'required|integer|min:0',
        ]);

        $buku = Buku::findOrFail($id);
        $fisik = Fisik::where('id_buku', $buku->id)->first();

        if (!$fisik) {
            return back()->withErrors(['stok' => 'Data stok fisik belum tersedia.']);
        }

        $stokLama = $fisik->stok;
        $stokBaru = (int) $validated['fisik'];

        if ($stokBaru == $stokLama) {
            return back()->with('info', 'Tidak ada perubahan pada jumlah stok.');
        }

        if ($stokBaru > $stokLama) {
            // Tambah stok
            $tambahan = $stokBaru - $stokLama;

            $kodePrefix = strtoupper(substr(Str::slug($buku->judul), 0, 3));
            $lastIndex = Detailbuku::where('id_buku', $buku->id)->count();

            for ($i = 1; $i <= $tambahan; $i++) {
                $kode = $kodePrefix . str_pad($lastIndex + $i, 3, '0', STR_PAD_LEFT);

                Detailbuku::create([
                    'id_buku'  => $buku->id,
                    'id_fisik' => $fisik->id,
                    'kode'     => $kode,
                    'dipinjam' => false,
                ]);
            }
        } else {
            // Kurangi stok
            $selisih = $stokLama - $stokBaru;

            $detailBisaDihapus = Detailbuku::where('id_buku', $buku->id)
                ->where('id_fisik', $fisik->id)
                ->where('dipinjam', false)
                ->orderByDesc('id')
                ->take($selisih)
                ->get();

            if ($detailBisaDihapus->count() < $selisih) {
                return back()->withErrors(['fisik' => 'Tidak bisa mengurangi stok karena ada buku yang sedang dipinjam.']);
            }

            foreach ($detailBisaDihapus as $detail) {
                $detail->delete();
            }
        }

        // Update stok fisik
        $fisik->stok = $stokBaru;
        $fisik->save();

        // ✅ Tambahkan log aktivitas
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aksi'      => 'Perbarui Stok Buku Fisik',
            'deskripsi' => "Admin mengubah stok buku '{$buku->judul}' dari $stokLama menjadi $stokBaru.",
            'ip_address'=> $request->ip(),
        ]);

        return back()->with('success', 'Stok buku berhasil diperbarui.');
    }


    public function getDetailBukuByBukuId($id)
    {
        $details = DetailBuku::where('id_buku', $id)
                    ->where('dipinjam', false)
                    ->select('id', 'kode') // ✅ gunakan kolom yang sesuai
                    ->get();

        return response()->json($details);
    }

    public function buatPeminjamanLengkap(Request $request)
    {
        $validated = $request->validate([
            // Akun & Anggota
            'email'    => 'required|email|unique:akuns,email',
            'username' => 'required|string|max:255|unique:akuns,username',
            'password' => 'required|string|min:6',
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string',
            'notlp'    => 'required|string|max:20',
            'img'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Peminjaman
            'id_buku'              => 'required|exists:bukus,id',
            'id_detail_buku'       => 'nullable|exists:detailbukus,id',
            'tanggal_pinjam'       => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        DB::beginTransaction();

        try {
            // 1. Buat akun
            $akun = Akun::create([
                'email'             => $validated['email'],
                'username'          => $validated['username'],
                'password'          => Hash::make($validated['password']),
                'role'              => 'anggota',
                'session_token'     => Str::random(60),
                'email_verified_at' => now(),
            ]);

            // 2. Upload gambar ke Supabase (jika ada)
            $imgPath = null;
            if ($request->hasFile('img')) {
                $file     = $request->file('img');
                $ext      = $file->getClientOriginalExtension();
                $filename = 'anggota-' . Str::uuid() . '.' . $ext;
                $filePath = "anggota/{$filename}";
                $fileBytes = file_get_contents($file->getPathname());

                $url = rtrim(env('SUPABASE_URL'), '/') . "/storage/v1/object/" .
                    env('SUPABASE_BUCKET') . "/{$filePath}";

                $response = Http::withHeaders([
                    'apikey'        => env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_ROLE_KEY'),
                    'Content-Type'  => $file->getMimeType(),
                ])
                ->withBody($fileBytes, $file->getMimeType())
                ->put($url);

                if (!$response->successful()) {
                    return back()->withErrors(['img' => 'Upload gambar ke Supabase gagal'])->withInput();
                }

                $imgPath = $filePath;
            }

            // 3. Buat anggota
            $anggota = Anggota::create([
                'id_akun' => $akun->id,
                'nama'    => $validated['nama'],
                'alamat'  => $validated['alamat'],
                'notlp'   => $validated['notlp'],
                'status'  => 'offline',
                'img'     => $imgPath,
            ]);

            // 4. Tandai detail buku sebagai dipinjam (jika dipilih)
            if ($validated['id_detail_buku']) {
                DetailBuku::where('id', $validated['id_detail_buku'])->update(['dipinjam' => true]);
            }

            // 5. Buat peminjaman
            Peminjaman::create([
                'id_anggota'           => $anggota->id,
                'id_buku'              => $validated['id_buku'],
                'id_detail_buku'       => $validated['id_detail_buku'],
                'tanggal_pinjam'       => $validated['tanggal_pinjam'],
                'tanggal_ambil'        => now()->toDateString(),
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'status_pengembalian'  => 'Dipinjam',
            ]);

            // ✅ Log aktivitas
            ActivityLog::create([
                'user_id'   => auth()->id(),
                'aksi'      => 'Tambah Peminjaman Sekaligus Akun Anggota',
                'deskripsi' => "Admin menambahkan anggota baru '{$anggota->nama}' dan membuat peminjaman buku ID: {$validated['id_buku']}.",
                'ip_address'=> $request->ip(),
            ]);

            DB::commit();

            return redirect()->route('admin.layoutsPeminjaman')->with('success', 'Peminjaman berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function cariPenggunaLog(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));

        // Ambil user_id yang memiliki log
        $userIdsWithLogs = ActivityLog::pluck('user_id')->unique()->toArray();

        // Ambil anggota yang cocok keyword di nama atau email
        $anggotas = Anggota::with('akun')
            ->whereHas('akun', function ($q) use ($userIdsWithLogs, $keyword) {
                $q->where('role', 'anggota')
                ->whereIn('id', $userIdsWithLogs)
                ->where('email', 'like', "%$keyword%");
            })
            ->orWhere(function ($q) use ($userIdsWithLogs, $keyword) {
                $q->where('nama', 'like', "%$keyword%")
                ->whereHas('akun', function ($q2) use ($userIdsWithLogs) {
                    $q2->where('role', 'anggota')
                        ->whereIn('id', $userIdsWithLogs);
                });
            })
            ->get();

        // Ambil voluntter yang cocok keyword di nama atau email
        $voluntters = Voluntter::with('akun')
            ->whereHas('akun', function ($q) use ($userIdsWithLogs, $keyword) {
                $q->where('role', 'voluntter')
                ->whereIn('id', $userIdsWithLogs)
                ->where('email', 'like', "%$keyword%");
            })
            ->orWhere(function ($q) use ($userIdsWithLogs, $keyword) {
                $q->where('nama', 'like', "%$keyword%")
                ->whereHas('akun', function ($q2) use ($userIdsWithLogs) {
                    $q2->where('role', 'voluntter')
                        ->whereIn('id', $userIdsWithLogs);
                });
            })
            ->get();

        // Gabungkan hasil
        $penggunas = collect($anggotas)->merge($voluntters)->sortBy('nama')->values();

        // Pagination manual
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

        return view('admin.components.logs_activity', [
            'penggunas' => $paginatedPenggunas,
            'keyword'   => $keyword,
            'title'     => 'Log Aktivitas Pengguna',
        ]);
    }

    public function kirimLinkReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:akuns,email',
        ]);

        $token = Str::random(64);
        $akun = Akun::where('email', $request->email)->first();

        $akun->update([
            'reset_password_token' => $token,
            'reset_password_expires_at' => Carbon::now()->addMinutes(5), // Link berlaku 5 menit
        ]);

        Mail::send('emails.lupa_password', [
            'token' => $token,
            'email' => $akun->email,
        ], function ($message) use ($akun) {
            $message->to($akun->email)->subject('Reset Password TBM Mardeka Membaca');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function formReset($token)
    {
        $akun = Akun::where('reset_password_token', $token)->firstOrFail();

        if (!$akun || Carbon::now()->gt($akun->reset_password_expires_at)) {
            return redirect()->route('password.form')->withErrors(['expired' => 'Token sudah kadaluarsa.']);
        }

        $title = 'Reset Password'; // ✅ Tambahkan ini

        return view('user.autentikasi.reset_password', compact('token', 'title'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $akun = Akun::where('reset_password_token', $request->token)->firstOrFail();

        if (Carbon::now()->gt($akun->reset_password_expires_at)) {
            return redirect()->route('password.form')->withErrors(['expired' => 'Token sudah kadaluarsa.']);
        }

        $akun->update([
            'password' => bcrypt($request->password),
            'reset_password_token' => null,
            'reset_password_expires_at' => null,
        ]);

        return redirect()->route('masuk')->with('success', 'Password berhasil direset, silakan login.');
    }

    // Anggota mengajukan jadi volunteer
    public function apply($id)
    {
        $anggota = Anggota::findOrFail($id);

        // hanya anggota reguler yang bisa ajukan
        if ($anggota->akses === 'reguler') {
            $anggota->update(['akses' => 'pending']);
            return back()->with('success', 'Pengajuan volunteer berhasil, menunggu konfirmasi admin.');
        }

        return back()->with('error', 'Tidak bisa mengajukan volunteer.');
    }

    public function reject($id)
    {
        // Cari anggota berdasarkan ID akun (bukan id anggota langsung)
        $anggota = Anggota::where('id_akun', $id)->firstOrFail();

        if ($anggota->akses === 'pending') {
            $anggota->update(['akses' => 'reguler']);
            return back()->with('success', 'Pengajuan volunteer ditolak.');
        }

        return back()->with('error', 'Aksi tidak valid atau anggota bukan pending.');
    }

     // Admin menyetujui
    public function approve($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->akses === 'pending') {
            $anggota->update(['akses' => 'plus']);
            return back()->with('success', 'Anggota berhasil disetujui menjadi volunteer.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

}   

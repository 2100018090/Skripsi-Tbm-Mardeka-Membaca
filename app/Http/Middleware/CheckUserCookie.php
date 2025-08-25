<?php

namespace App\Http\Middleware;

use App\Models\Akun;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah user masih login
        if (!Auth::check()) {
            // Ambil ID session saat ini
            $sessionId = session()->getId();

            // (Opsional) Jika kamu simpan sesi login berdasarkan session ID, kamu bisa cari dan hapus cache
            // Tapi di sini kita skip, cukup arahkan ke login
            return redirect()->route('masuk')
                            ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // 2. Ambil user yang login
        $user = Auth::user();

        // 3. Ambil ID session sekarang
        $currentSessionId = session()->getId();

        // 4. Ambil session ID yang tersimpan di cache
        $cachedSessionId = Cache::get('user_logged_in_' . $user->email);

        // 5. Jika session di cache tidak ada, artinya sesi sudah tidak valid → logout
        if (!$cachedSessionId) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('masuk')->with('error', 'Sesi Anda sudah tidak aktif. Silakan login kembali.');
        }

        // 6. Jika session ID sekarang tidak sama dengan yang ada di cache → user login di tempat lain
        if ($cachedSessionId !== $currentSessionId) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('masuk')->with('error', 'Akun Anda digunakan di perangkat lain.');
        }

        // 7. Share user data ke controller jika diperlukan
        $request->merge(['user_data' => $user]);

        return $next($request);
    }


}

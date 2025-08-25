<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Anggota;
use App\Models\Voluntter;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('*', function ($view) {
            $user = Auth::user();
            $admin = null;
            $voluntter = null;
            $anggota = null;

            if ($user) {
                if ($user->role === 'admin') {
                    $admin = Admin::where('id_akun', $user->id)->first();
                } elseif ($user->role === 'voluntter') {
                    $voluntter = Voluntter::where('id_akun', $user->id)->first();
                } elseif ($user->role === 'anggota') {
                    $anggota = Anggota::where('id_akun', $user->id)->first();
                }
            }

            $view->with(compact('admin', 'voluntter', 'anggota', 'user'));
        });
    }
}

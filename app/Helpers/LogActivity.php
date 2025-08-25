<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    public static function add($aksi, $deskripsi = null)
    {
        ActivityLog::create([
            'user_id'    => Auth::check() ? Auth::id() : null,
            'aksi'       => $aksi,
            'deskripsi'  => $deskripsi,
            'ip_address' => Request::ip(),
        ]);
    }
}

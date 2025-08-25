<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Pesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends Controller
{

    public function getMessages()
    {
        $user = Auth::user();

        if ($user->role === 'anggota') {
            $messages = Pesan::where(function($q) use ($user) {
                $q->where('pengirim_id', $user->id)
                ->orWhere('penerima_id', $user->id);
            })->orderBy('created_at', 'asc')->get();
        } else {
            $messages = Pesan::where(function($q) use ($user) {
                $q->whereNull('penerima_id')
                ->orWhere('pengirim_id', $user->id);
            })->orderBy('created_at', 'asc')->get();
        }

        // ✅ Perbaikan: bungkus hasil dalam kunci 'messages'
        return response()->json([
            'messages' => $messages
        ]);
    }


    public function sendMessage(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:500',
            'penerima_id' => 'nullable|exists:akuns,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Ambil data valid
        $data = $validator->validated();
        $user = Auth::user();
        $penerimaId = $data['penerima_id'] ?? null;

        // 3. Logika pengiriman berdasarkan role
        if ($user->role === 'anggota') {
            // Anggota bisa kirim ke volunteer → broadcast ke semua
            $penerimaId = null;
        } elseif (!$penerimaId) {
            return response()->json([
                'status' => 'error',
                'errors' => ['penerima_id' => ['Volunteer wajib memilih anggota penerima']]
            ], 422);
        }

        // 4. Simpan pesan pengguna
        $pesan = Pesan::create([
            'pengirim_id' => $user->id,
            'penerima_id' => $penerimaId,
            'isi' => $data['message'],
        ]);

        broadcast(new MessageSent($pesan))->toOthers();

        // 5. Cek trigger auto-reply jika isi pesan mengandung "hallo"
        $isi = strtolower(trim($data['message']));
        if ($user->role === 'anggota' && Str::startsWith($isi, 'hallo')) {
            // Simpan pesan balasan otomatis
            $balasan = Pesan::create([
                'pengirim_id' => null, // null untuk sistem/bot
                'penerima_id' => $user->id,
                'isi' => 'Hai juga! Ada yang bisa kami bantu?',
            ]);

            broadcast(new MessageSent($balasan))->toOthers();
        }

        // 6. Response JSON
        return response()->json([
            'status' => 'success',
            'pesan' => $pesan
        ]);
    }
 
}

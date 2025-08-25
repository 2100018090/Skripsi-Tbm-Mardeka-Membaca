<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Akun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $akun = Akun::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'Admin',
                'password' => Hash::make('password'), // ganti sesuai kebutuhan
                'role' => 'admin'
            ]
        );

        Admin::firstOrCreate([
            'id_akun' => $akun->id,
        ], [
            'nama' => 'Admin Default',
            'alamat' => 'Alamat Admin',
            'notlp' => '081234567890',
            'img' => null,
        ]);
    }
}

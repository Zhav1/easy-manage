<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            ['name' => 'Perawat', 'description' => 'Perawat bertugas merawat pasien'],
            ['name' => 'Dokter', 'description' => 'Dokter umum dan spesialis'],
            ['name' => 'Bidan', 'description' => 'Membantu persalinan dan perawatan ibu-anak'],
            ['name' => 'Apoteker', 'description' => 'Bertanggung jawab terhadap obat-obatan'],
            ['name' => 'Admin', 'description' => 'Administrasi dan pencatatan rekam medis'],
        ];

        Position::insert($positions);
    }
}

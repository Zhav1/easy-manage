<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
    [
        'name'        => 'Perawat Pelaksana',
        'description' => 'Perawat bertugas merawat pasien',
    ],
    [
        'name'        => 'Penanggung Jawab Shift',
        'description' => 'Mengatur dan Membagi Tugas Tim',
    ],
    [
        'name'        => 'Kepala Ruangan',
        'description' => 'Memanajemen Segala Kepentingan Ruangan',
    ],
    [
        'name'        => 'Ketua Tim',
        'description' => 'Bertanggung jawab terhadap anggota tim',
    ],
    [
        'name'        => 'Other',
        'description' => 'Jabatan Lainnya',
    ],
];


        Position::insert($positions);
    }
}

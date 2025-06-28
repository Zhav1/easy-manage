<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Hospital;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'IGD (Instalasi Gawat Darurat)',
            'ICU (Intensive Care Unit)',
            'NICU (Neonatal ICU)',
            'PICU (Pediatric ICU)',
            'OK (Operasi)',
            'Ruang VIP',
            'Ruang Kelas 1',
            'Ruang Kelas 2',
            'Ruang Kelas 3',
            'Ruang Isolasi',
            'Ruang Persalinan',
            'Ruang Perawatan',
            'Rawat Jalan',
            'Laboratorium',
            'Radiologi',
            'Fisioterapi',
            'Hemodialisa',
            'Kamar Mayat',
        ];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}

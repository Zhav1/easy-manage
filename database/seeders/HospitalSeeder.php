<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = [
            'RSUP H. Adam Malik',
            'RSU dr. Pirngadi Medan',
            'RSUD Deli Serdang',
            'RS Bhayangkara TK II Medan',
            'RS Haji Medan',
            'RS Royal Prima Medan',
            'RS Siloam Medan',
            'RS Universitas Sumatera Utara',
            'RS Mitra Sejati',
            'RS Bunda Thamrin',
        ];

        foreach ($list as $name) {
            Hospital::firstOrCreate(['name' => $name]);
        }
    }
}

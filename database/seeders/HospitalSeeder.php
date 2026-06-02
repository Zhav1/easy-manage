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
            'RS Universitas Sumatera Utara',
            'RS Mitra Sejati',
            'RS Bunda Thamrin',
            'RSUD T Mansyur Tanjung Balai',
            'RSUD A MANAN Kisaran',
            'RSUD PANYABUNGAN',
            'RSUD Sultan Sulaiman',
            'RSUD Porsea',
            'RSUD Sidempuan',
            'RSUD Samosir',
            'RSUD FL Tobing Sibolga',
            'RSUD Tarutung',
            
            
        ];

        foreach ($list as $name) {
            Hospital::firstOrCreate(['name' => $name]);
        }
    }
}

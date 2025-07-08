<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logistic;
use App\Models\Department;

class LogisticsSeeder extends Seeder
{
   
    public function run()
    {
        $this->call([
        LogisticsSeeder::class,
    ]);
        // Get all departments or create a default one
        $departments = Department::all();
        if ($departments->isEmpty()) {
            $department = Department::create(['name' => 'IGD']);
            $departments = collect([$department]);
        }

        $medicalEquipment = [
            ['item_name' => 'ECG Machine', 'unit_of_measure' => 'unit'],
            ['item_name' => 'Defibrillator', 'unit_of_measure' => 'unit'],
            ['item_name' => 'Infusion Pump', 'unit_of_measure' => 'unit'],
            ['item_name' => 'Syringe Pump', 'unit_of_measure' => 'unit'],
            ['item_name' => 'Patient Monitor', 'unit_of_measure' => 'unit'],
        ];

        $consumableItems = [
            ['item_name' => 'Masker Bedah', 'unit_of_measure' => 'pack'],
            ['item_name' => 'Sarung Tangan', 'unit_of_measure' => 'box'],
            ['item_name' => 'Jarum Suntik', 'unit_of_measure' => 'box'],
            ['item_name' => 'Kassa Steril', 'unit_of_measure' => 'pack'],
            ['item_name' => 'Plaster Luka', 'unit_of_measure' => 'pack'],
        ];

        foreach ($departments as $department) {
            // Seed medical equipment
            foreach ($medicalEquipment as $item) {
                Logistic::create([
                    'department_id' => $department->id,
                    'category' => 'Alat Kesehatan',
                    'item_name' => $item['item_name'],
                    'unit_of_measure' => $item['unit_of_measure'],
                    'condition' => 'Baik',
                    'stock' => rand(1, 10),
                ]);
            }

            // Seed consumable items
            foreach ($consumableItems as $item) {
                Logistic::create([
                    'department_id' => $department->id,
                    'category' => 'Barang Habis Pakai',
                    'item_name' => $item['item_name'],
                    'unit_of_measure' => $item['unit_of_measure'],
                    'condition' => 'Baik',
                    'stock' => rand(1, 20),
                ]);
            }
        }
    }
}
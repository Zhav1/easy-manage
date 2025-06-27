<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Rawat Inap', 'code' => 'RI'],
            ['name' => 'IGD', 'code' => 'IGD'],
            ['name' => 'ICU', 'code' => 'ICU'],
            ['name' => 'Radiologi', 'code' => 'RAD'],
            ['name' => 'Laboratorium', 'code' => 'LAB'],
        ];

        Department::insert($departments);
    }
}

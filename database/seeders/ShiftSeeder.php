<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        DB::table('shifts')->insert([
            ['code' => 'Pagi', 'start' => '07:00:00', 'end' => '14:00:00'],
            ['code' => 'Siang', 'start' => '14:00:00', 'end' => '21:00:00'],
            ['code' => 'Malam', 'start' => '21:00:00', 'end' => '07:00:00'],
        ]);
    }
}


<?php

namespace Database\Seeders;

use App\Models\JangkaWaktuOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JangkaWaktuOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JangkaWaktuOption::factory()->createMany([
            ['value' => 'Kurang dari 6 bulan', 'is_other' => false],
            ['value' => '6 bulan', 'is_other' => false],
            ['value' => '1 tahun', 'is_other' => false],
            ['value' => '2 tahun', 'is_other' => false],
            ['value' => '3 tahun', 'is_other' => false],
        ]);
    }
}

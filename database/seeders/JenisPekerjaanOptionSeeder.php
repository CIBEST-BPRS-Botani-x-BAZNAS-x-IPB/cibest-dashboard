<?php

namespace Database\Seeders;

use App\Models\JenisPekerjaanOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPekerjaanOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPekerjaanOption::factory()->createMany([
            ['value' => 'Karyawan swasta', 'is_other' => false],
            ['value' => 'Petani', 'is_other' => false],
            ['value' => 'Pedagang', 'is_other' => false],
            ['value' => 'Buruh', 'is_other' => false],
            ['value' => 'Nelayan', 'is_other' => false],
        ]);
    }
}

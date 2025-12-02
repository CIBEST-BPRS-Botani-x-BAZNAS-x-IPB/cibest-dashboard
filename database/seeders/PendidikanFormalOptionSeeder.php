<?php

namespace Database\Seeders;

use App\Models\PendidikanFormalOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendidikanFormalOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PendidikanFormalOption::factory()->createMany([
            ['value' => 'Tidak Bersekolah'],
            ['value' => 'SD/SDLB/Paket A'],
            ['value' => 'Madrasah Ibtidaiyah'],
            ['value' => 'SMP/SMPLB/Paket B'],
            ['value' => 'Madrasah Tsanawiyah'],
            ['value' => 'SMA/SMK/SMALB/Paket C'],
            ['value' => 'Madrasah Aliyah'],
            ['value' => 'Perguruan tinggi (D1/D2/D3/S1)'],
            ['value' => 'Tidak disebutkan'],
        ]);
    }
}

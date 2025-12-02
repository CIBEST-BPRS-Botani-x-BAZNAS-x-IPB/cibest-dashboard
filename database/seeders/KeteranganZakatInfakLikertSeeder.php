<?php

namespace Database\Seeders;

use App\Models\KeteranganZakatInfakLikert;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeteranganZakatInfakLikertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KeteranganZakatInfakLikert::factory()->createMany([
            ['value' => '1', 'description' => 'Melarang orang lain berzakat dan infak'],
            ['value' => '2', 'description' => 'Menolak zakat dan infak'],
            ['value' => '3', 'description' => 'Tidak pernah berinfak walau sekali dalam setahun'],
            ['value' => '4', 'description' => 'Membayar zakat fitrah dan/atau zakat harta (mal)/hanya melakukan infak sesekali'],
            ['value' => '5', 'description' => 'Membayar zakat fitrah, zakat harta, dan infak/sedekah'],
        ]);
    }
}

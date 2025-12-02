<?php

namespace Database\Seeders;

use App\Models\PendidikanNonformalOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendidikanNonformalOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PendidikanNonformalOption::factory()->createMany([
            ['value' => 'Pernah mengikuti (ada sertifikat)'],
            ['value' => 'Tidak pernah mengikuti'],
            ['value' => 'Tidak disebutkan'],
        ]);
    }
}

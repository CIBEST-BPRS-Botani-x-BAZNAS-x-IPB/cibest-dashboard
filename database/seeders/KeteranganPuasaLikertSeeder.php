<?php

namespace Database\Seeders;

use App\Models\KeteranganPuasaLikert;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeteranganPuasaLikertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KeteranganPuasaLikert::factory()->createMany([
            ['value' => '1', 'description' => 'Melarang orang lain berpuasa'],
            ['value' => '2', 'description' => 'Menolak konsep puasa'],
            ['value' => '3', 'description' => 'Melaksanakan puasa wajib tidak penuh'],
            ['value' => '4', 'description' => 'Melaksanakan puasa wajib secara penuh'],
            ['value' => '5', 'description' => 'Melaksanakan puasa wajib penuh dan puasa sunnah'],
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\BantuanKonsumtifSection;
use App\Models\BantuanProduktifSection;
use App\Models\BantuanZiswafSection;
use App\Models\CibestForm;
use App\Models\CibestQuadrant;
use App\Models\KarakteristikRumahTanggaSection;
use App\Models\PembiayaanSyariahSection;
use App\Models\PembinaanPendampinganSection;
use App\Models\PendapatanKetenagakerjaanSection;
use App\Models\PovertyStandard;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CibestFormService
{
    /**
     * Process form data from import and save to database
     *
     * @param array $importData The imported data rows
     * @return void
     */
    public function processFormData(array $importData, string $type)
    {
        foreach ($importData as $row) {
            $bantuanZiswaf = null;
            if ($row['bantuan_ziswaf_section']) {
                // Section
                $bantuanKonsumtifSection = BantuanKonsumtifSection::create(
                    Arr::get($row, 'bantuan_ziswaf_section.bantuan_konsumtif_section')
                );
                $bantuanProduktifSection = BantuanProduktifSection::create(
                    Arr::get($row, 'bantuan_ziswaf_section.bantuan_konsumtif_section')
                );

                // Main
                $bantuanZiswaf = BantuanZiswafSection::create([
                    ...Arr::except($row['bantuan_ziswaf_section'], 'lembaga_ziswaf_checkbox', 'program_bantuan_checkbox', 'pembiayaan_lain_checkbox'),
                    'bantuan_konsumtif_section_id' => $bantuanKonsumtifSection->id,
                    'bantuan_produktif_section_id' => $bantuanProduktifSection->id,
                ]);

                // Checkbox
                $bantuanZiswaf->lembagaZiswafCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.lembaga_ziswaf_checkbox'));
                $bantuanZiswaf->programBantuanCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.program_bantuan_checkbox'));
                $bantuanZiswaf->pembiayaanLainCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.pembiayaan_lain_checkbox'));
            }

            $pembiayaanSyariah = null;
            if ($row['pembiayaan_syariah_section']) {
                // Main
                $pembiayaanSyariah = PembiayaanSyariahSection::create([
                    ...Arr::except($row['pembiayaan_syariah_section'], 'akad_pembiayaan_checkbox', 'penggunaan_pembiayaan_checkbox', 'pembiayaan_lain_checkbox'),
                ]);

                // Checkbox
                $pembiayaanSyariah->akadPembiayaanCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.akad_pembiayaan_checkbox'));
                $pembiayaanSyariah->penggunaanPembiayaanCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.penggunaan_pembiayaan_checkbox'));
                $pembiayaanSyariah->pembiayaanLainCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.pembiayaan_lain_checkbox'));
            }

            $pembinaanPendampingan = null;
            if ($row['pembinaan_pendampingan_section']) {
                // Main
                $pembinaanPendampingan = PembinaanPendampinganSection::create([
                    ...Arr::except($row['pembinaan_pendampingan_section'], 'jenis_pelatihan_checkbox', 'pelatihan_sangat_membantu_checkbox')
                ]);

                // Checkbox
                $pembinaanPendampingan->jenisPelatihanCheckboxes()->sync(Arr::get($row, 'pembinaan_pendampingan_section.jenis_pelatihan_checkbox'));
                $pembinaanPendampingan->pelatihanSangatMembantuCheckboxes()->sync(Arr::get($row, 'pembinaan_pendampingan_section.pelatihan_sangat_membantu_checkbox'));
            }

            $cibestForm = CibestForm::create([
                ...Arr::except($row,
                    'bantuan_ziswaf_section',
                    'pembiayaan_syariah_section',
                    'karakteristik_rumah_tangga_section',
                    'pendapatan_ketenagakerjaan_section',
                    'pembinaan_pendampingan_section',
                ),
                'bantuan_ziswaf_section_id' => $bantuanZiswaf->id ?? null,
                'pembiayaan_syariah_section_id' => $pembiayaanSyariah->id ?? null,
                'pembinaan_pendampingan_section_id' => $pembinaanPendampingan->id ?? null,
                'user_id' => Auth::user()->id,
                'type' => $type,
            ]);

            if ($row['karakteristik_rumah_tangga_section']) {
                foreach ($row['karakteristik_rumah_tangga_section'] as $member) {
                    KarakteristikRumahTanggaSection::create([
                        ...$member,
                        'cibest_form_id' => $cibestForm->id
                    ]);
                }
            }

            if ($row['pendapatan_ketenagakerjaan_section']) {
                foreach ($row['pendapatan_ketenagakerjaan_section'] as $member) {
                    PendapatanKetenagakerjaanSection::create([
                        ...$member,
                        'cibest_form_id' => $cibestForm->id
                    ]);
                }
            }

            // Hitung kuadran setelah semua data disimpan dan simpan ke tabel cibest_quadrants
            $quadrantResults = $this->hitungKuadran($cibestForm);

            foreach ($quadrantResults as $quadrantResult) {
                CibestQuadrant::create([
                    'poverty_id' => $quadrantResult['poverty_standard_id'],
                    'form_id' => $cibestForm->id,
                    'kuadran_sebelum' => $quadrantResult['kuadran_sebelum'],
                    'kuadran_setelah' => $quadrantResult['kuadran_setelah']
                ]);
            }
        }
    }

    /**
     * Fungsi untuk menghitung kuadran berdasarkan pendapatan dan nilai spiritual
     * untuk semua standar kemiskinan yang tersedia
     *
     * @param CibestForm $cibestForm
     * @return array Array of quadrants data for each poverty standard
     */
    public function hitungKuadran(CibestForm $cibestForm)
    {
        // Ambil semua standar kemiskinan dari database
        $povertyStandards = PovertyStandard::all();

        // Hitung total pendapatan dari semua anggota rumah tangga
        $totalPendapatanSebelum = 0;
        $totalPendapatanSetelah = 0;
        foreach ($cibestForm->pendapatanKetenagakerjaanSections as $pendapatan) {
            $totalPendapatanSebelum += $pendapatan->total_pendapatan_sebelum;
            $totalPendapatanSetelah += $pendapatan->total_pendapatan_setelah;
        }

        // Hitung rata-rata nilai spiritual sebelum
        $nilai_spiritual_sebelum = [
            $cibestForm->shalatSebelum->value,
            $cibestForm->puasaSebelum->value,
            $cibestForm->zakatInfakSebelum->value,
            $cibestForm->lingkunganKeluargaSebelum->value,
            $cibestForm->kebijakanPemerintahSebelum->value
        ];

        $total_nilai_sebelum = 0;
        $jumlah_nilai_sebelum = 0;
        foreach ($nilai_spiritual_sebelum as $nilai) {
            $total_nilai_sebelum += $nilai;
            $jumlah_nilai_sebelum++;
        }

        $rata_rata_spiritual_sebelum = $total_nilai_sebelum / $jumlah_nilai_sebelum;
        $s_sebelum = ($rata_rata_spiritual_sebelum > 3) ? 1 : 0;

        // Hitung rata-rata nilai spiritual setelah
        $nilai_spiritual_setelah = [
            $cibestForm->shalatSetelah->value,
            $cibestForm->puasaSetelah->value,
            $cibestForm->zakatInfakSetelah->value,
            $cibestForm->lingkunganKeluargaSetelah->value,
            $cibestForm->kebijakanPemerintahSetelah->value
        ];

        $total_nilai_setelah = 0;
        $jumlah_nilai_setelah = 0;
        foreach ($nilai_spiritual_setelah as $nilai) {
            $total_nilai_setelah += $nilai;
            $jumlah_nilai_setelah++;
        }

        $rata_rata_spiritual_setelah = $total_nilai_setelah / $jumlah_nilai_setelah;
        $s_setelah = ($rata_rata_spiritual_setelah > 3) ? 1 : 0;

        // Array untuk menyimpan hasil kuadran untuk setiap standar kemiskinan
        $quadrantResults = [];

        foreach ($povertyStandards as $povertyStandard) {
            // Bandingkan pendapatan dengan nilai standar dari database
            $m_sebelum = ($totalPendapatanSebelum > $povertyStandard->nilai_keluarga) ? 1 : 0;
            $m_setelah = ($totalPendapatanSetelah > $povertyStandard->nilai_keluarga) ? 1 : 0;

            // Petakan ke kuadran sebelum
            $kuadran_sebelum = $this->tentukanKuadran($m_sebelum, $s_sebelum);

            // Petakan ke kuadran setelah
            $kuadran_setelah = $this->tentukanKuadran($m_setelah, $s_setelah);

            $quadrantResults[] = [
                'poverty_standard_id' => $povertyStandard->id,
                'kuadran_sebelum' => $kuadran_sebelum,
                'kuadran_setelah' => $kuadran_setelah
            ];
        }

        return $quadrantResults;
    }

    /**
     * Fungsi untuk menentukan kuadran berdasarkan M dan S
     *
     * @param int $m Material (pendapatan > standar kemiskinan = 1, else 0)
     * @param int $s Spiritual (rata-rata spiritual > 3 = 1, else 0)
     * @return int Kuadran (1-4)
     */
    private function tentukanKuadran($m, $s)
    {
        // Matriks kuadran:
        // Material (M) | Spiritual (S) | Kuadran
        //      1       |       1       |    1
        //      0       |       1       |    2
        //      1       |       0       |    3
        //      0       |       0       |    4

        if ($m == 1 && $s == 1) {
            return 1;
        } elseif ($m == 0 && $s == 1) {
            return 2;
        } elseif ($m == 1 && $s == 0) {
            return 3;
        } else { // $m == 0 && $s == 0
            return 4;
        }
    }
}
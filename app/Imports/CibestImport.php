<?php

namespace App\Imports;

use App\Models\CibestForm;
use App\Models\JenisKelaminOption;
use App\Models\KeteranganKebijakanPemerintahLikert;
use App\Models\KeteranganLingkunganKeluargaLikert;
use App\Models\KeteranganPuasaLikert;
use App\Models\KeteranganShalatLikert;
use App\Models\KeteranganZakatInfakLikert;
use App\Models\PendidikanFormalOption;
use App\Models\PendidikanNonformalOption;
use App\Models\StatusPerkawinanOption;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use phpDocumentor\Reflection\Types\This;
use Throwable;

HeadingRowFormatter::default('none');

class CibestImport implements ToModel, WithHeadingRow
{
    private function getOptionId(string $model, string|null $value, string $columnName)
    {
        // Jika data kosong → kembalikan null
        if (!$value) {
            return null;
        }

        // Normalisasi whitespace
        $value = trim($value);

        // Query berdasarkan value
        $record = $model::where('value', $value)->first();

        // Jika tidak ditemukan → throw error
        if (!$record) {
            throw new \Exception("Nilai '{$value}' pada kolom '{$columnName}' tidak ditemukan di tabel {$model}.");
        }

        return $record->id;
    }

    private function getJenisKelaminId(string|null $data)
    {
        return $this->getOptionId(JenisKelaminOption::class, $data, '9. Jenis kelamin');
    }

    private function getStatusPerkawinanId($data)
    {
        return $this->getOptionId(StatusPerkawinanOption::class, $data, '10. Status perkawinan');
    }

    private function getPendidikanFormalId($data)
    {
        return $this->getOptionId(PendidikanFormalOption::class, $data, '11. Jenjang pendidikan formal terakhir');
    }

    private function getPendidikanNonFormalId($data)
    {
        return $this->getOptionId(PendidikanNonformalOption::class, $data, '12. Pendidikan non-formal');
    }

    private function getKeteranganShalatId($data, string $columnName)
    {
        return $this->getOptionId(KeteranganShalatLikert::class, $data, $columnName);
    }

    private function getKeteranganPuasaId($data, string $columnName)
    {
        return $this->getOptionId(KeteranganPuasaLikert::class, $data, $columnName);
    }

    private function getKeteranganZakatInfakId($data, string $columnName)
    {
        return $this->getOptionId(KeteranganZakatInfakLikert::class, $data, $columnName);
    }

    private function getKeteranganLingkunganKeluargaId($data, string $columnName)
    {
        return $this->getOptionId(KeteranganLingkunganKeluargaLikert::class, $data, $columnName);
    }

    private function getKeteranganKebijakanPemerintahId($data, string $columnName)
    {
        return $this->getOptionId(KeteranganKebijakanPemerintahLikert::class, $data, $columnName);
    }

    private $rows = 0;

    public function getRowCount(): int
    {
        return $this->rows;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            ++$this->rows;

            return new CibestForm([
                'user_id' => Auth::user()->id,
    
                // --- Enumerator ---
                'nama_enumerator'           => $row['Nama Enumerator'],
                'waktu_pengambilan_data'    => $row['Waktu Pengambilan Data'],
    
                // --- Karakteristik Responden ---
                'nama_responden'    => $row['1. Nama responden'],
                'nomor_kontak'      => $row['2. Nomor kontak (jika ada)'] ?? null,
                'alamat'            => $row['3. Alamat (nama jalan/gang, RT/RW/dusun)'],
                'provinsi'          => $row['4. Provinsi'],
                'kabupaten_kota'    => $row['5. Kabupaten/kota'],
                'kecamatan'         => $row['6. Kecamatan'],
                'desa_kelurahan'    => $row['7. Desa/kelurahan'],
                'usia'              => $row['8. Usia responden (tahun)'] ?? 2,
                'jenis_kelamin_option_id'         => $this->getJenisKelaminId(Str::ucfirst($row['9. Jenis kelamin'])),
                'status_perkawinan_option_id'     => $this->getStatusPerkawinanId($row['10. Status perkawinan']),
                'pendidikan_formal_option_id'     => $this->getPendidikanFormalId($row['11. Jenjang pendidikan formal terakhir']),
                'pendidikan_nonformal_option_id'  => $this->getPendidikanNonFormalId($row['12. Pendidikan non-formal']),
    
                // --- Usaha dan Profit ---
                'memiliki_usaha_sendiri' => $row['13. Apakah Anda memiliki usaha sendiri sebelum menerima bantuan?'],
                'rata_rata_profit'       => $row['13. Berapa rata-rata profit/keuntungan dari usaha yang Anda miliki per bulan? Rp………./bulan'] ?? 0,
    
                // // --- Bantuan ZISWAF ---
                // 'bantuan_ziswaf_section_id' => $row['15. Apakah Anda pernah menerima ziswaf?'] ?? null,
    
                // // --- Pembiayaan Syariah ---
                // 'pembiayaan_syariah_section_id' => $row['23. Apakah Anda pernah menerima pembiayaan syariah?'] ?? null,
    
                // --- Pengeluaran Rumah Tangga ---
                'pangan'            => $row['1. Rata-rata Pengeluaran (Rp) untuk Pangan (beras atau makanan pokok lainnya, sayur mayur, ayam/daging/ikan, susu, telur, makanan jadi, dll) (*satu minggu terakhir)'] ?? 0,
                'rokok_tembakau'    => $row['2. Rata-rata Pengeluaran (Rp) untuk Rokok/tembakau (*satu minggu terakhir)'] ?? 0,
                'sewa_rumah'        => $row['3. Rata-rata Pengeluaran (Rp) untuk Sewa rumah/kontrakan/kosan (*satu bulan terakhir)'] ?? 0,
                'listrik'           => $row['4. Rata-rata Pengeluaran (Rp) untuk Listrik (*satu bulan terakhir)'] ?? 0,
                'air'               => $row['5. Rata-rata Pengeluaran (Rp) untuk Air (*satu bulan terakhir)'] ?? 0,
                'bahan_bakar'       => $row['6. Rata-rata Pengeluaran (Rp) untuk Gas/bahan bakar lainnya (*satu bulan terakhir)'] ?? 0,
                'sandang'           => $row['7. Rata-rata Pengeluaran (Rp) untuk Sandang (pakaian, sepatu/sandal, jahit/permak) (*satu bulan terakhir)'] ?? 0,
                'pendidikan'        => $row['8. Rata-rata Pengeluaran (Rp) untuk Pendidikan (uang sekolah, buku, alat tulis, transport anak sekolah, seragam, dll) (*satu bulan terakhir)'] ?? 0,
                'kesehatan'         => $row['9. Rata-rata Pengeluaran (Rp) untuk Pendidikan Kesehatan (mencakup obat-obatan, biaya berobat, pemeriksaan kesehatan, BPJS, jamkes) (*satu bulan terakhir)'] ?? 0,
                'transportasi'      => $row['10. Rata-rata Pengeluaran (Rp) untuk Transportasi (mencakup biaya bis, ojek, angkot, perahu, dan biaya perbaikan kendaraan, bahan bakar kendaraan dan sejenisnya) (*satu bulan terakhir)'] ?? 0,
                'komunikasi'        => $row['11. Rata-rata Pengeluaran (Rp) untuk Komunikasi (pembayaran rekening telepon dan pembelian voucher/isi pulsa, kartu perdana, paket data internet) (*satu bulan terakhir)'] ?? 0,
                'rekreasi_hiburan'  => $row['12. Rata-rata Pengeluaran (Rp) untuk Rekreasi dan hiburan (mencakup jalan-jalan/liburan, kegiatan rekreasi sederhana) (*satu bulan terakhir)'] ?? 0,
                'perawatan_badan'   => $row['13. Rata-rata Pengeluaran (Rp) untuk Kebutuhan perawatan badan dan muka (mencakup sabun mandi, pasta/sikat gigi, perawatan muka) (*satu bulan terakhir)'] ?? 0,
                'sosial_keagamaan'  => $row['14. Rata-rata Pengeluaran (Rp) untuk Sosial & keagamaan (zakat, infak, sedekah, sumbangan sosial, kegiatan masjid) (*satu bulan terakhir)'] ?? 0,
                'angsuran_kredit'   => $row['15. Rata-rata Pengeluaran (Rp) untuk Pembayaran angsuran kredit/cicilan utang (*satu bulan terakhir)'] ?? 0,
                'lain_lain'         => $row['16. Rata-rata Pengeluaran (Rp) untuk Lain-lain (pengeluaran tidak rutin, seperti kondangan, pesta, biaya darurat, perbaikan rumah, dll.) (*satu bulan terakhir)'] ?? 0,
    
                // --- Tabungan ---
                'memiliki_tabungan_bank_konvensional'     => $row['1. Apakah Anda Memiliki tabungan di bank konvensional'] === "Ya" ? true : false,
                'memiliki_tabungan_bank_syariah'          => $row['2. Apakah Anda Memiliki tabungan di bank Syariah'] === "Ya" ? true : false,
                'memiliki_tabungan_koperasi_konvensional' => $row['3. Apakah Anda Memiliki tabungan di koperasi konvensional'] === "Ya" ? true : false,
                'memiliki_tabungan_koperasi_syariah'      => $row['4. Apakah Anda Memiliki tabungan di koperasi syariah/BMT'] === "Ya" ? true : false,
                'memiliki_tabungan_lembaga_zakat'         => $row['5. Apakah Anda Memiliki tabungan di lembaga zakat'] === "Ya" ? true : false,
                'mengikuti_arisan_rutin'                  => $row['6. Apakah Anda mengikuti arisan rutin'] === "Ya" ? true : false,
                'memiliki_simpanan_rumah'                 => $row['7. Apakah Anda  Memiliki simpanan di rumah dalam bentuk celengan brankas, dan sebagainya'] === "Ya" ? true : false,
    
                // --- Spiritual Sebelum ---
                'shalat_sebelum'               => $this->getKeteranganShalatId(
                    $row['8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Shalat'],
                    '8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Shalat'
                ),
    
                'puasa_sebelum'                => $this->getKeteranganPuasaId(
                    $row['8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Puasa'],
                    '8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Puasa'
                ),
    
                'zakat_infak_sebelum'          => $this->getKeteranganZakatInfakId(
                    $row['8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Zakat/infak'],
                    '8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Zakat/infak'
                ),
    
                'lingkungan_keluarga_sebelum'  => $this->getKeteranganLingkunganKeluargaId(
                    $row['8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Lingkungan Keluarga'],
                    '8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Lingkungan Keluarga'
                ),
    
                'kebijakan_pemerintah_sebelum' => $this->getKeteranganKebijakanPemerintahId(
                    $row['8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Kebijakan Pemerintah'],
                    '8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Kebijakan Pemerintah'
                ),
    
    
                // --- Spiritual Setelah ---
                'shalat_setelah'               => $this->getKeteranganShalatId(
                    $row['8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Shalat'],
                    '8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Shalat'
                ),
    
                'puasa_setelah'                => $this->getKeteranganPuasaId(
                    $row['8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Puasa'],
                    '8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Puasa'
                ),
    
                'zakat_infak_setelah'          => $this->getKeteranganZakatInfakId(
                    $row['8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Zakat/infak'],
                    '8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Zakat/infak'
                ),
    
                'lingkungan_keluarga_setelah'  => $this->getKeteranganLingkunganKeluargaId(
                    $row['8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Lingkungan Keluarga'],
                    '8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Lingkungan Keluarga'
                ),
    
                'kebijakan_pemerintah_setelah' => $this->getKeteranganKebijakanPemerintahId(
                    $row['8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Kebijakan Pemerintah'],
                    '8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Kebijakan Pemerintah'
                ),
                // // --- Pembinaan & Pendampingan ---
                // 'pembinaan_pendampingan_section_id' => $row['1.  Apakah Anda pernah mendapatkan pendampingan, pelatihan, atau bimbingan selama menerima bantuan?'] ?? null,
            ]);
        } catch (Throwable $e) {
            throw new Exception(
                "Error pada baris {$this->getRowCount()}: " . $e->getMessage()
            );
        }
    }
}

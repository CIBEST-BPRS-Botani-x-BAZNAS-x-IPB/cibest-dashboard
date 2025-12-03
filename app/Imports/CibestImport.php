<?php

namespace App\Imports;

use App\Models\AkadPembiayaanCheckbox;
use App\Models\FrekuensiPendampinganOption;
use App\Models\JangkaWaktuOption;
use App\Models\JenisKelaminOption;
use App\Models\JenisPekerjaanOption;
use App\Models\JenisPelatihanCheckbox;
use App\Models\KeteranganKebijakanPemerintahLikert;
use App\Models\KeteranganLingkunganKeluargaLikert;
use App\Models\KeteranganPuasaLikert;
use App\Models\KeteranganShalatLikert;
use App\Models\KeteranganZakatInfakLikert;
use App\Models\LembagaZiswafCheckbox;
use App\Models\PembiayaanLainCheckbox;
use App\Models\PembinaanPendampinganSection;
use App\Models\PendidikanFormalOption;
use App\Models\PendidikanNonformalOption;
use App\Models\PenggunaanPembiayaanCheckbox;
use App\Models\ProgramBantuanCheckbox;
use App\Models\Province;
use App\Models\StatusPekerjaanOption;
use App\Models\StatusPerkawinanOption;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CibestImport extends BaseImport
{   
    public function startRow(): int
    {
        return 2;
    }

    public function mapping(int $index): array|string|null
    {
        $map = [
            0 => "Timestamp",
            1 => "Email Address",
            2 => "Nama Enumerator",
            3 => "Waktu Pengambilan Data",
            4 => "1. Nama responden",
            5 => "2. Nomor kontak (jika ada)",
            6 => "3. Alamat (nama jalan/gang, RT/RW/dusun)",
            7 => "4. Provinsi",
            8 => "5. Kabupaten/kota",
            9 => "6. Kecamatan",
            10 => "7. Desa/kelurahan",
            11 => "8. Usia (tahun)",
            12 => "9. Jenis kelamin",
            13 => "10. Status perkawinan",
            14 => "11. Jenjang pendidikan formal terakhir",
            15 => "12. Pendidikan non-formal",
            16 => "13. Apakah Anda memiliki usaha sendiri sebelum menerima bantuan?",
            17 => "13. Berapa rata-rata profit/keuntungan dari usaha yang Anda miliki per bulan? Rp………./bulan",
            18 => "15. Apakah Anda pernah menerima ziswaf?",
            19 => "16. Kapan pertama kali Anda menerima bantuan dari lembaga zakat (ZISWAF)? Bulan………... Tahun……………",
            20 => "17.  Apa nama lembaga ziswaf yang memberikan bantuan?",
            21 => "18.  Apa nama lembaga ziswaf yang memberikan bantuan?",
            22 => "19. Selama satu tahun terakhir sudah berapa kali Anda menerima bantuan ziswaf?....................kali.",
            23 => "20. Selama satu tahun terakhir berapa total nilai bantuan ziswaf yang diterima (perkiraan dalam Rupiah)? Rp………………..",
            24 => "21. a) Apa jenis ziswaf yang diterima? Pangan (Rp)",
            25 => "21. b) Apa jenis ziswaf yang diterima? Kesehatan (Rp)",
            26 => "21. c) Apa jenis ziswaf yang diterima? Pendidikan/Beasiswa (Rp)",
            27 => "21. d) Apa jenis ziswaf yang diterima? Bantuan Konsumtif Lainnya (Rp)",
            28 => "21. e) Apa jenis ziswaf yang diterima? Bantuan Modal Usaha (Rp)",
            29 => "21. f) Apa jenis ziswaf yang diterima? Bantuan Peralatan Usaha (Rp)",
            30 => "21. g) Apa jenis ziswaf yang diterima? Bantuan Produktif Lainnya  (Rp)",
            31 => "22. Selain bantuan ziswaf, apakah Anda menerima bantuan pembiayaan dari pihak lain?",
            32 => "22. Jika ya, dari siapa",
            33 => "23. Apakah Anda pernah menerima pembiayaan syariah?",
            34 => "24. Kapan pertama kali Anda mendapatkan bantuan pembiayaan syariah tersebut? Bulan………... Tahun……………",
            35 => "25. Apa nama lembaga keuangan syariah yang memberikan bantuan pembiayaan? Sebutkan……….",
            36 => "26. Apa jenis akad pembiayaan yang digunakan?",
            37 => "27. Berapa lama jangka waktu pembiayaan yang diberikan?",
            38 => "28. Selama satu tahun terakhir sudah berapa kali Anda menerima bantuan pembiayaan syariah dari lembaga tersebut?....................kali.",
            39 => "29. Selama satu tahun terakhir berapa total nilai bantuan pembiayaan syariah yang Anda terima? Rp……………...…..",
            40 => "30. Pembiayaan syariah yang diperoleh digunakan untuk apa saja?",
            41 => "31. Selain pembiayaan syariah, apakah Anda menerima bantuan pembiayaan dari pihak lain?",
            42 => "31. Jika menjawab iya pada pertanyaan sebelumnya, sebutkan bantuan yang diterima",
            43 => "32. Sebelumnya, apakah Anda pernah mendapatkan pembiayaan dari lembaga keuangan syariah lainnya? Jika iya, sebutkan nama lembaga keuangannya",
            44 => "33. Apakah Anda pernah mendapatkan kredit dari lembaga keuangan konvensional (misal bank konvensional, BPR, koperasi?) Jika iya, sebutkan nama lembaga keuangann ▶",
            45 => "1. Nama Anggota Keluarga",
            46 => "1. Hubungan dengan Kepala Keluarga",
            47 => "1. Usia (tahun)",
            48 => "1. Jenis Kelamin",
            49 => "1. Status Perkawinan",
            50 => "1. Jenjang Pendidikan Formal Terakhir",
            51 => "1. Pendidikan Non Formal",
            52 => "2. Nama Anggota Keluarga",
            53 => "2. Hubungan dengan Kepala Keluarga",
            54 => "2. Usia (tahun)",
            55 => "2. Jenis Kelamin",
            56 => "2. Status Perkawinan",
            57 => "2. Jenjang Pendidikan Formal Terakhir",
            58 => "2. Pendidikan Non Formal",
            59 => "3. Nama Anggota Keluarga",
            60 => "3. Hubungan dengan Kepala Keluarga",
            61 => "3. Usia (tahun)",
            62 => "3. Jenis Kelamin",
            63 => "3. Status Perkawinan",
            64 => "3. Jenjang Pendidikan Formal Terakhir",
            65 => "3. Pendidikan Non Formal",
            66 => "4. Nama Anggota Keluarga",
            67 => "4. Hubungan dengan Kepala Keluarga",
            68 => "4. Usia (tahun)",
            69 => "4. Jenis Kelamin",
            70 => "4. Status Perkawinan",
            71 => "4. Jenjang Pendidikan Formal Terakhir",
            72 => "4. Pendidikan Non Formal",
            73 => "5. Nama Anggota Keluarga",
            74 => "5. Hubungan dengan Kepala Keluarga",
            75 => "5. Usia (tahun)",
            76 => "5. Jenis Kelamin",
            77 => "5. Status Perkawinan",
            78 => "5. Jenjang Pendidikan Formal Terakhir",
            79 => "5. Pendidikan Non Formal",
            80 => "6. Nama Anggota Keluarga",
            81 => "6. Hubungan dengan Kepala Keluarga",
            82 => "6. Usia (tahun)",
            83 => "6. Jenis Kelamin",
            84 => "6. Status Perkawinan",
            85 => "6. Jenjang Pendidikan Formal Terakhir",
            86 => "6. Pendidikan Non Formal",
            87 => "7. Nama Anggota Keluarga",
            88 => "7. Hubungan dengan Kepala Keluarga",
            89 => "7. Usia (tahun)",
            90 => "7. Jenis Kelamin",
            91 => "7. Status Perkawinan",
            92 => "7. Jenjang Pendidikan Formal Terakhir",
            93 => "7. Pendidikan Non Formal",
            94 => "8. Nama Anggota Keluarga",
            95 => "8. Hubungan dengan Kepala Keluarga",
            96 => "7. Usia (tahun)",
            97 => "8. Jenis Kelamin",
            98 => "8. Status Perkawinan",
            99 => "8. Jenjang Pendidikan Formal Terakhir",
            100 => "8. Pendidikan Non Formal",
            101 => "9. Nama Anggota Keluarga",
            102 => "9. Hubungan dengan Kepala Keluarga",
            103 => "9. Usia (tahun)",
            104 => "9. Jenis Kelamin",
            105 => "9. Status Perkawinan",
            106 => "9. Jenjang Pendidikan Formal Terakhir",
            107 => "9. Pendidikan Non Formal",
            108 => "1. Nama Anggota Rumah Tangga",
            109 => "1. Status Pekerjaan (sebulan terakhir)",
            110 => "1. Jenis Pekerjaan",
            111 => "1. Rata-rata Pendapatan (Rp/bulan)",
            112 => "1. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            113 => "1. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            114 => "1. Total Pendapatan (Rp/bulan)",
            115 => "2. Nama Anggota Rumah Tangga",
            116 => "2. Status Pekerjaan (sebulan terakhir)",
            117 => "2. Jenis Pekerjaan",
            118 => "2. Rata-rata Pendapatan (Rp/bulan)",
            119 => "2. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            120 => "2. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            121 => "2. Total Pendapatan (Rp/bulan)",
            122 => "3. Nama Anggota Rumah Tangga",
            123 => "3. Status Pekerjaan (sebulan terakhir)",
            124 => "3. Jenis Pekerjaan",
            125 => "3. Rata-rata Pendapatan (Rp/bulan)",
            126 => "3. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            127 => "3. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            128 => "3. Total Pendapatan (Rp/bulan)",
            129 => "4. Nama Anggota Rumah Tangga",
            130 => "4. Status Pekerjaan (sebulan terakhir)",
            131 => "4. Jenis Pekerjaan",
            132 => "4. Rata-rata Pendapatan (Rp/bulan)",
            133 => "4. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            134 => "4. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            135 => "4. Total Pendapatan (Rp/bulan)",
            136 => "5. Nama Anggota Rumah Tangga",
            137 => "5. Status Pekerjaan (sebulan terakhir)",
            138 => "5. Jenis Pekerjaan",
            139 => "5. Rata-rata Pendapatan (Rp/bulan)",
            140 => "5. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            141 => "5. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            142 => "5. Total Pendapatan (Rp/bulan)",
            143 => "6. Nama Anggota Rumah Tangga",
            144 => "6. Status Pekerjaan (sebulan terakhir)",
            145 => "6. Jenis Pekerjaan",
            146 => "6. Rata-rata Pendapatan (Rp/bulan)",
            147 => "6. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            148 => "6. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            149 => "6. Total Pendapatan (Rp/bulan)",
            150 => "7. Nama Anggota Rumah Tangga",
            151 => "7. Status Pekerjaan (sebulan terakhir)",
            152 => "7. Jenis Pekerjaan",
            153 => "7. Rata-rata Pendapatan (Rp/bulan)",
            154 => "7. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            155 => "7. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            156 => "7. Total Pendapatan (Rp/bulan)",
            157 => "8. Nama Anggota Rumah Tangga",
            158 => "8. Status Pekerjaan (sebulan terakhir)",
            159 => "8. Jenis Pekerjaan",
            160 => "8. Rata-rata Pendapatan (Rp/bulan)",
            161 => "8. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            162 => "8. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            163 => "8. Total Pendapatan (Rp/bulan)",
            164 => "9. Nama Anggota Rumah Tangga",
            165 => "9. Status Pekerjaan (sebulan terakhir)",
            166 => "9. Jenis Pekerjaan",
            167 => "9. Rata-rata Pendapatan (Rp/bulan)",
            168 => "9. Pendapatan tidak tetap (Rp/bulan) Misal: kiriman keluarga, bantuan ziswaf, bantuan pemerintah, pekerjaan sampingan, dll",
            169 => "9. Pendapatan dari aset yang disewakan (Rp/bulan) Misal tanah, kontrakan/kosan, ternak, kendaraan, alat usaha, dll",
            170 => "9. Total Pendapatan (Rp/bulan)",
            171 => "1. Rata-rata Pengeluaran (Rp) untuk Pangan (beras atau makanan pokok lainnya, sayur mayur, ayam/daging/ikan, susu, telur, makanan jadi, dll) (*satu minggu terakhir)",
            172 => "2. Rata-rata Pengeluaran (Rp) untuk Rokok/tembakau (*satu minggu terakhir)",
            173 => "3. Rata-rata Pengeluaran (Rp) untuk Sewa rumah/kontrakan/kosan (*satu bulan terakhir)",
            174 => "4. Rata-rata Pengeluaran (Rp) untuk Listrik (*satu bulan terakhir)",
            175 => "5. Rata-rata Pengeluaran (Rp) untuk Air (*satu bulan terakhir)",
            176 => "6. Rata-rata Pengeluaran (Rp) untuk Gas/bahan bakar lainnya (*satu bulan terakhir)",
            177 => "7. Rata-rata Pengeluaran (Rp) untuk Sandang (pakaian, sepatu/sandal, jahit/permak) (*satu bulan terakhir)",
            178 => "8. Rata-rata Pengeluaran (Rp) untuk Pendidikan (uang sekolah, buku, alat tulis, transport anak sekolah, seragam, dll) (*satu bulan terakhir)",
            179 => "9. Rata-rata Pengeluaran (Rp) untuk Pendidikan Kesehatan (mencakup obat-obatan, biaya berobat, pemeriksaan kesehatan, BPJS, jamkes) (*satu bulan terakhir)",
            180 => "10. Rata-rata Pengeluaran (Rp) untuk Transportasi (mencakup biaya bis, ojek, angkot, perahu, dan biaya perbaikan kendaraan, bahan bakar kendaraan dan sejenisnya ▶ (*satu bulan terakhir)",
            181 => "11. Rata-rata Pengeluaran (Rp) untuk Komunikasi (pembayaran rekening telepon dan pembelian voucher/isi pulsa, kartu perdana, paket data internet) (*satu bulan terakhir)",
            182 => "12. Rata-rata Pengeluaran (Rp) untuk Rekreasi dan hiburan (mencakup jalan-jalan/liburan, kegiatan rekreasi sederhana) (*satu bulan terakhir)",
            183 => "13. Rata-rata Pengeluaran (Rp) untuk Kebutuhan perawatan badan dan muka (mencakup sabun mandi, pasta/sikat gigi, perawatan muka) (*satu bulan terakhir)",
            184 => "14. Rata-rata Pengeluaran (Rp) untuk Sosial & keagamaan (zakat, infak, sedekah, sumbangan sosial, kegiatan masjid) (*satu bulan terakhir)",
            185 => "15. Rata-rata Pengeluaran (Rp) untuk Pembayaran angsuran kredit/cicilan utang (*satu bulan terakhir)",
            186 => "16. Rata-rata Pengeluaran (Rp) untuk Lain-lain (pengeluaran tidak rutin, seperti kondangan, pesta, biaya darurat, perbaikan rumah, dll.) (*satu bulan terakhir)",
            187 => "1. Apakah Anda Memiliki tabungan di bank konvensional",
            188 => "2. Apakah Anda Memiliki tabungan di bank Syariah",
            189 => "3. Apakah Anda Memiliki tabungan di koperasi konvensional",
            190 => "4. Apakah Anda Memiliki tabungan di koperasi syariah/BMT",
            191 => "5. Apakah Anda Memiliki tabungan di lembaga zakat",
            192 => "6. Apakah Anda mengikuti arisan rutin",
            193 => "7. Apakah Anda  Memiliki simpanan di rumah dalam bentuk celengan brankas, dan sebagainya",
            194 => "8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Shalat",
            195 => "8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Puasa",
            196 => "8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Zakat/infak",
            197 => "8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Lingkungan Keluarga",
            198 => "8.1. Kondisi Spiritual Responden Sebelum Menerima Bantuan Kebijakan Pemerintah",
            199 => "8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Shalat",
            200 => "8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Puasa",
            201 => "8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Zakat/infak",
            202 => "8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Lingkungan Keluarga",
            203 => "8.2. Kondisi Spiritual Responden Setelah Menerima Bantuan Kebijakan Pemerintah",
            204 => "1.  Apakah Anda pernah mendapatkan pendampingan, pelatihan, atau bimbingan selama menerima bantuan?",
            205 => "2. Jika Ya, seberapa sering Anda mengikuti pendampingan, pelatihan, atau bimbingan yang diberikan selama menerima bantuan?",
            206 => "3. Apa saja jenis pendampingan dan pelatihan yang diikuti (jawaban boleh lebih dari satu)?",
            207 => "4. Jenis pelatihan apa yang dirasa sangat membantu mengembangkan usaha Anda (jawaban boleh lebih dari satu)?",
            208 => "5. Pembinaan spiritual (pengajian/pertemuan rutin) sekurang-kurangnya 1x dalam satu (1) bulan",
            209 => "6. Pembinaan dan peningkatan kapasitas usaha sekurang-kurangnya 1x dalam enam (6) bulan",
            210 => "7. Pendampingan rutin (monitoring program) sekurang-kurangnya 1x dalam 1 bulan",
        ];

        return $map[$index] ?? null;
    }

    private function getKarakteristikRumahTangga($row): array
    {
        $data = [];

        $start = 45;
        $columns = 7;
        $maxMember = 9;

        for ($i = 0; $i < $maxMember; $i++) {
            $base = $start + ($i * $columns);

            // Jika nama anggota kosong, skip blok ini
            if (empty($row[$base])) continue;

            $data[] = [
                'nama_anggota' => $row[$base],
                'hubungan_kepala_keluarga' => $row[$base + 1],
                'usia' => $row[$base + 2],
                'jenis_kelamin_id' => $this->getOptionId(JenisKelaminOption::class, $row[$base + 3], $base + 3),
                'status_perkawinan_id' => $this->getOptionId(StatusPerkawinanOption::class, $row[$base + 4], $base + 4),
                'pendidikan_formal_id' => $this->getOptionId(PendidikanFormalOption::class, $row[$base + 5], $base + 5),
                'pendidikan_non_id' => $this->getOptionId(PendidikanNonformalOption::class, $row[$base + 6], $base + 6),
            ];
        }

        return $data;
    }

    private function getPendapatanKetenagakerjaan($row): array
    {
        $data = [];

        $start = 108;
        $columns = 7;
        $maxMember = 9;

        for ($i = 0; $i < $maxMember; $i++) {
            $base = $start + ($i * $columns);

            // Jika nama anggota kosong, skip blok ini
            if (empty($row[$base])) continue;

            $data[] = [
                'nama_anggota' => $row[$base],
                'status_id' => $this->getOptionId(StatusPekerjaanOption::class, $row[$base + 1], $base + 1),
                'jenis_id' => $this->getOptionId(JenisPekerjaanOption::class, $row[$base + 2] ?? 'Tidak disebutkan', $base + 2, true),
                'rata_rata_pendapatan' => $row[$base + 3] ?? 0,
                'pendapatan_tidak_tetap' => $row[$base + 4] ?? 0,
                'pendapatan_aset' => $row[$base + 5] ?? 0,
                'total_pendapatan_sebelum' => $row[$base + 6] ?? 0,
                'total_pendapatan_setelah' => $row[$base + 6] ?? 0,
            ];
        }

        return $data;
    }

    public function collection(Collection $rows)
    {
        // dd($rows->count());
        foreach ($rows as $row)
        {
            $this->data[] = [
                // --- Enumerator ---
                'nama_enumerator'           => $row[2],
                'waktu_pengambilan_data'    => Carbon::parse($row[3])->format('Y-m-d'),
    
                // --- Karakteristik Responden ---
                'nama_responden'    => $row[4],
                'nomor_kontak'      => $row[5] ?? null,
                'alamat'            => $row[6],
                'province_id'       => $this->getOptionId(Province::class, Str::title($row[7]), 7),
                'kabupaten_kota'    => $row[8],
                'kecamatan'         => $row[9],
                'desa_kelurahan'    => $row[10],
                'usia'              => $row[11] ?? 2,
                'jenis_kelamin_option_id'         => $this->getOptionId(JenisKelaminOption::class, Str::ucfirst($row[12]), 12),
                'status_perkawinan_option_id'     => $this->getOptionId(StatusPerkawinanOption::class, $row[13], 13),
                'pendidikan_formal_option_id'     => $this->getOptionId(PendidikanFormalOption::class, $row[14], 14),
                'pendidikan_nonformal_option_id'  => $this->getOptionId(PendidikanNonformalOption::class, $row[15], 15),
    
                // --- Usaha dan Profit ---
                'memiliki_usaha_sendiri' => ($row[16] ?? null) === "Ya",
                'rata_rata_profit'       => $row[17] ?? 0,
    
                // --- Bantuan ZISWAF ---
                'bantuan_ziswaf_section' => $row[18] === 'Ya' ? 
                    [
                        'bulan_tahun_menerima' => Carbon::parse($row[3])->format('Y-m-d'),
                        'lembaga_ziswaf_checkbox' => $this->getCheckboxId(LembagaZiswafCheckbox::class, $row[20]),
                        'program_bantuan_checkbox' => $this->getCheckboxId(ProgramBantuanCheckbox::class, $row[21]),
                        'frekuensi_penerimaan' => $row[22],
                        'total_nilai_bantuan' => $row[23],
                        'bantuan_konsumtif_section' => [
                            'pangan' => $row[24] ?? 0,
                            'kesehatan' => $row[25] ?? 0,
                            'pendidikan' => $row[26] ?? 0,
                            'lainnya' => $row[27] ?? 0,
                        ],
                        'bantuan_produktif_section' => [
                            'modal_usaha' => $row[28] ?? 0,
                            'peralatan_usaha' => $row[29] ?? 0,
                            'lainnya' => $row[30] ?? 0,
                        ],
                        'pembiayaan_lain_checkbox' => $this->getCheckboxId(PembiayaanLainCheckbox::class, $row[32])
                    ]
                    : null,
    
                // --- Pembiayaan Syariah ---
                'pembiayaan_syariah_section' => $row[33] === 'Ya' ? 
                    [
                        'bulan_tahun_menerima' => Carbon::parse($row[34])->format('Y-m-d'),
                        'lembaga_keuangan_syariah' => $row[35],
                        'akad_pembiayaan_checkbox' => $this->getCheckboxId(AkadPembiayaanCheckbox::class, $row[36]),
                        'jangka_waktu_option_id' => $this->getOptionId(JangkaWaktuOption::class, $row[37], 37),
                        'frekuensi_penerimaan' => $row[38],
                        'total_nilai_pembiayaan' => $row[39],
                        'penggunaan_pembiayaan_checkbox' => $this->getCheckboxId(PenggunaanPembiayaanCheckbox::class, $row[40]),
                        'pembiayaan_lain_checkbox' => $this->getCheckboxId(PembiayaanLainCheckbox::class, $row[42]),
                        'lembaga_syariah_lain' => $row[43],
                        'lembaga_konvensional' => $row[44],
                    ]
                    : null,

                // --- Karakteristik Rumah Tangga ---
                'karakteristik_rumah_tangga_section' => $this->getKarakteristikRumahTangga($row),

                // --- Pendapatan Ketenagakerjaan ---
                'pendapatan_ketenagakerjaan_section' => $this->getPendapatanKetenagakerjaan($row),
    
                // --- Pengeluaran Rumah Tangga ---
                'pangan'            => $row[171] ?? 0,
                'rokok_tembakau'    => $row[172] ?? 0,
                'sewa_rumah'        => $row[173] ?? 0,
                'listrik'           => $row[174] ?? 0,
                'air'               => $row[175] ?? 0,
                'bahan_bakar'       => $row[176] ?? 0,
                'sandang'           => $row[177] ?? 0,
                'pendidikan'        => $row[178] ?? 0,
                'kesehatan'         => $row[179] ?? 0,
                'transportasi'      => $row[180] ?? 0,
                'komunikasi'        => $row[181] ?? 0,
                'rekreasi_hiburan'  => $row[182] ?? 0,
                'perawatan_badan'   => $row[183] ?? 0,
                'sosial_keagamaan'  => $row[184] ?? 0,
                'angsuran_kredit'   => $row[185] ?? 0,
                'lain_lain'         => $row[186] ?? 0,
    
                // --- Tabungan ---
                'memiliki_tabungan_bank_konvensional'     => ($row[187] ?? null) === "Ya",
                'memiliki_tabungan_bank_syariah'          => ($row[188] ?? null) === "Ya",
                'memiliki_tabungan_koperasi_konvensional' => ($row[189] ?? null) === "Ya",
                'memiliki_tabungan_koperasi_syariah'      => ($row[190] ?? null) === "Ya",
                'memiliki_tabungan_lembaga_zakat'         => ($row[191] ?? null) === "Ya",
                'mengikuti_arisan_rutin'                  => ($row[192] ?? null) === "Ya",
                'memiliki_simpanan_rumah'                 => ($row[193] ?? null) === "Ya",
    
                // --- Spiritual Sebelum ---
                'shalat_sebelum'               => $this->getOptionId(KeteranganShalatLikert::class, $row[194], 194),
                'puasa_sebelum'                => $this->getOptionId(KeteranganPuasaLikert::class, $row[195], 195),
                'zakat_infak_sebelum'          => $this->getOptionId(KeteranganZakatInfakLikert::class, $row[196], 196),
                'lingkungan_keluarga_sebelum'  => $this->getOptionId(KeteranganLingkunganKeluargaLikert::class, $row[197], 197),
                'kebijakan_pemerintah_sebelum' => $this->getOptionId(KeteranganKebijakanPemerintahLikert::class, $row[198], 198),

                // --- Spiritual Setelah ---
                'shalat_setelah'               => $this->getOptionId(KeteranganShalatLikert::class, $row[199], 199),
                'puasa_setelah'                => $this->getOptionId(KeteranganPuasaLikert::class, $row[200], 200),
                'zakat_infak_setelah'          => $this->getOptionId(KeteranganZakatInfakLikert::class, $row[201], 201),
                'lingkungan_keluarga_setelah'  => $this->getOptionId(KeteranganLingkunganKeluargaLikert::class, $row[202], 202),
                'kebijakan_pemerintah_setelah' => $this->getOptionId(KeteranganKebijakanPemerintahLikert::class, $row[203], 203),

                // --- Pembinaan & Pendampingan ---
                'pembinaan_pendampingan_section' => [
                    'frekuensi_id' => $this->getOptionId(FrekuensiPendampinganOption::class, $row[205], 205),
                    'jenis_pelatihan_checkbox' => $this->getCheckboxId(JenisPelatihanCheckbox::class, $row[206]),
                    'pelatihan_sangat_membantu_checkbox' => $this->getCheckboxId(JenisPelatihanCheckbox::class, $row[207]),
                    'pembinaan_spiritual' => ($row[208] ?? null) === "Ya",
                    'pembinaan_usaha' => ($row[209] ?? null) === "Ya",
                    'pendampingan_rutin' => ($row[210] ?? null) === "Ya",
                ],
            ];
        }
    }

    public function rules(): array
    {
        return [
            // --- Enumerator ---
            '2' => 'required|string',
            '3' => 'required|date',

            // --- Karakteristik Responden ---
            '4' => 'required|string',
            '5' => 'nullable|string',
            '6' => 'required|string',
            '7' => 'required|exists:provinces,value',
            '8' => 'required|string',
            '9' => 'required|string',
            '10' => 'required|string',
            '11' => 'required|integer|min:0|max:150',

            // opsi: cek berdasarkan kolom `value` di tabel opsi
            '12' => 'required|exists:jenis_kelamin_options,value',
            '13' => 'required|exists:status_perkawinan_options,value',
            '14' => 'required|exists:pendidikan_formal_options,value',
            '15' => 'required|exists:pendidikan_nonformal_options,value',

            // --- Usaha dan Profit ---
            '16' => 'required|in:Ya,Tidak',
            '17' => 'nullable|integer|min:0',

            // -- Bantuan Ziswaf
            '18' => 'required|in:Ya,Tidak',
            '19' => 'required_if:18,Ya|nullable|date',
            '20' => 'required_if:18,Ya|nullable|string',
            '21' => 'required_if:18,Ya|nullable|string',
            '22' => 'required_if:18,Ya|nullable|integer|min:0',
            '23' => 'required_if:18,Ya|nullable|integer|min:0',
            '24' => 'nullable|integer|min:0',
            '25' => 'nullable|integer|min:0',
            '26' => 'nullable|integer|min:0',
            '27' => 'nullable|integer|min:0',
            '28' => 'nullable|integer|min:0',
            '29' => 'nullable|integer|min:0',
            '30' => 'nullable|integer|min:0',
            '31' => 'required_if:18,Ya|nullable|in:Ya,Tidak',
            '32' => 'required_if:31,Ya|nullable|string',

            // -- Bantuan Syariah
            '33' => 'required|in:Ya,Tidak',
            '34' => 'required_if:33,Ya|nullable|date',
            '35' => 'required_if:33,Ya|nullable|string',
            '36' => 'required_if:33,Ya|nullable|string',
            '37' => 'required_if:33,Ya|nullable|string',
            '38' => 'required_if:33,Ya|nullable|integer|min:0',
            '39' => 'required_if:33,Ya|nullable|integer|min:0',
            '40' => 'required_if:33,Ya|nullable|string',
            '41' => 'required_if:33,Ya|nullable|in:Ya,Tidak',
            '42' => 'required_if:41,Ya|nullable|string',
            '43' => 'nullable|string',
            '44' => 'nullable|string',

            // -- Karakteristik Rumah Tangga
            // Anggota 1
            '45' => 'required|string',
            '46' => 'required|string',
            '47' => 'required|integer|min:0',
            '48' => 'required|exists:jenis_kelamin_options,value',
            '49' => 'required|exists:status_perkawinan_options,value',
            '50' => 'required|exists:pendidikan_formal_options,value',
            '51' => 'required|exists:pendidikan_nonformal_options,value',
            // Anggota 2
            '52' => 'sometimes|nullable|string',
            '53' => 'required_with:52|nullable|string',
            '54' => 'required_with:52|nullable|integer|min:0',
            '55' => 'required_with:52|nullable|exists:jenis_kelamin_options,value',
            '56' => 'required_with:52|nullable|exists:status_perkawinan_options,value',
            '57' => 'required_with:52|nullable|exists:pendidikan_formal_options,value',
            '58' => 'required_with:52|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 3
            '59' => 'sometimes|nullable|string',
            '60' => 'required_with:59|nullable|string',
            '61' => 'required_with:59|nullable|integer|min:0',
            '62' => 'required_with:59|nullable|exists:jenis_kelamin_options,value',
            '63' => 'required_with:59|nullable|exists:status_perkawinan_options,value',
            '64' => 'required_with:59|nullable|exists:pendidikan_formal_options,value',
            '65' => 'required_with:59|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 4
            '66' => 'sometimes|nullable|string',
            '67' => 'required_with:66|nullable|string',
            '68' => 'required_with:66|nullable|integer|min:0',
            '69' => 'required_with:66|nullable|exists:jenis_kelamin_options,value',
            '70' => 'required_with:66|nullable|exists:status_perkawinan_options,value',
            '71' => 'required_with:66|nullable|exists:pendidikan_formal_options,value',
            '72' => 'required_with:66|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 5
            '73' => 'sometimes|nullable|string',
            '74' => 'required_with:73|nullable|string',
            '75' => 'required_with:73|nullable|integer|min:0',
            '76' => 'required_with:73|nullable|exists:jenis_kelamin_options,value',
            '77' => 'required_with:73|nullable|exists:status_perkawinan_options,value',
            '78' => 'required_with:73|nullable|exists:pendidikan_formal_options,value',
            '79' => 'required_with:73|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 6
            '80' => 'sometimes|nullable|string',
            '81' => 'required_with:80|nullable|string',
            '82' => 'required_with:80|nullable|integer|min:0',
            '83' => 'required_with:80|nullable|exists:jenis_kelamin_options,value',
            '84' => 'required_with:80|nullable|exists:status_perkawinan_options,value',
            '85' => 'required_with:80|nullable|exists:pendidikan_formal_options,value',
            '86' => 'required_with:80|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 7
            '87' => 'sometimes|nullable|string',
            '88' => 'required_with:87|nullable|string',
            '89' => 'required_with:87|nullable|integer|min:0',
            '90' => 'required_with:87|nullable|exists:jenis_kelamin_options,value',
            '91' => 'required_with:87|nullable|exists:status_perkawinan_options,value',
            '92' => 'required_with:87|nullable|exists:pendidikan_formal_options,value',
            '93' => 'required_with:87|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 8
            '94' => 'sometimes|nullable|string',
            '95' => 'required_with:94|nullable|string',
            '96' => 'required_with:94|nullable|integer|min:0',
            '97' => 'required_with:94|nullable|exists:jenis_kelamin_options,value',
            '98' => 'required_with:94|nullable|exists:status_perkawinan_options,value',
            '99' => 'required_with:94|nullable|exists:pendidikan_formal_options,value',
            '100'=> 'required_with:94|nullable|exists:pendidikan_nonformal_options,value',
            // Anggota 9
            '101' => 'sometimes|nullable|string',
            '102' => 'required_with:101|nullable|string',
            '103' => 'required_with:101|nullable|integer|min:0',
            '104' => 'required_with:101|nullable|exists:jenis_kelamin_options,value',
            '105' => 'required_with:101|nullable|exists:status_perkawinan_options,value',
            '106' => 'required_with:101|nullable|exists:pendidikan_formal_options,value',
            '107'=> 'required_with:101|nullable|exists:pendidikan_nonformal_options,value',

            // -- Pendapatan Ketenagakerjaan
            // Anggota 1
            '108' => 'required|string',
            '109' => 'required|exists:status_pekerjaan_options,value',
            '110' => 'required|string',
            '111' => 'nullable|integer|min:0',
            '112' => 'nullable|integer|min:0',
            '113' => 'nullable|integer|min:0',
            '114' => 'nullable|integer|min:0',
            // Anggota 2
            '115' => 'sometimes|nullable|string',
            '116' => 'required_with:115|nullable|exists:status_pekerjaan_options,value',
            '117' => 'required_with:115|nullable|string',
            '118' => 'nullable|integer|min:0',
            '119' => 'nullable|integer|min:0',
            '120' => 'nullable|integer|min:0',
            '121' => 'nullable|integer|min:0',
            // Anggota 3
            '122' => 'sometimes|nullable|string',
            '123' => 'required_with:122|nullable|exists:status_pekerjaan_options,value',
            '124' => 'required_with:122|nullable|string',
            '125' => 'nullable|integer|min:0',
            '126' => 'nullable|integer|min:0',
            '127' => 'nullable|integer|min:0',
            '128' => 'nullable|integer|min:0',
            // Anggota 4
            '129' => 'sometimes|nullable|string',
            '130' => 'required_with:129|nullable|exists:status_pekerjaan_options,value',
            '131' => 'required_with:129|nullable|string',
            '132' => 'nullable|integer|min:0',
            '133' => 'nullable|integer|min:0',
            '134' => 'nullable|integer|min:0',
            '135' => 'nullable|integer|min:0',
            // Anggota 5
            '136' => 'sometimes|nullable|string',
            '137' => 'required_with:136|nullable|exists:status_pekerjaan_options,value',
            '138' => 'required_with:136|nullable|string',
            '139' => 'nullable|integer|min:0',
            '140' => 'nullable|integer|min:0',
            '141' => 'nullable|integer|min:0',
            '142' => 'nullable|integer|min:0',
            // Anggota 6
            '143' => 'sometimes|nullable|string',
            '144' => 'required_with:143|nullable|exists:status_pekerjaan_options,value',
            '145' => 'required_with:143|nullable|string',
            '146' => 'nullable|integer|min:0',
            '147' => 'nullable|integer|min:0',
            '148' => 'nullable|integer|min:0',
            '149' => 'nullable|integer|min:0',
            // Anggota 7
            '150' => 'sometimes|nullable|string',
            '151' => 'required_with:150|nullable|exists:status_pekerjaan_options,value',
            '152' => 'required_with:150|nullable|string',
            '153' => 'nullable|integer|min:0',
            '154' => 'nullable|integer|min:0',
            '155' => 'nullable|integer|min:0',
            '156' => 'nullable|integer|min:0',
            // Anggota 8
            '157' => 'sometimes|nullable|string',
            '158' => 'required_with:157|nullable|exists:status_pekerjaan_options,value',
            '159' => 'required_with:157|nullable|string',
            '160' => 'nullable|integer|min:0',
            '161' => 'nullable|integer|min:0',
            '162' => 'nullable|integer|min:0',
            '163' => 'nullable|integer|min:0',
            // Anggota 9
            '164' => 'sometimes|nullable|string',
            '165' => 'required_with:164|nullable|exists:status_pekerjaan_options,value',
            '166' => 'required_with:164|nullable|string',
            '167' => 'nullable|integer|min:0',
            '168' => 'nullable|integer|min:0',
            '169' => 'nullable|integer|min:0',
            '170' => 'nullable|integer|min:0',
            
            // --- Pengeluaran Rumah Tangga
            '171' => 'nullable|integer|min:0',
            '172' => 'nullable|integer|min:0',
            '173' => 'nullable|integer|min:0',
            '174' => 'nullable|integer|min:0',
            '175' => 'nullable|integer|min:0',
            '176' => 'nullable|integer|min:0',
            '177' => 'nullable|integer|min:0',
            '178' => 'nullable|integer|min:0',
            '179' => 'nullable|integer|min:0',
            '180' => 'nullable|integer|min:0',
            '181' => 'nullable|integer|min:0',
            '182' => 'nullable|integer|min:0',
            '183' => 'nullable|integer|min:0',
            '184' => 'nullable|integer|min:0',
            '185' => 'nullable|integer|min:0',
            '186' => 'nullable|integer|min:0',

            // --- Tabungan (Ya/Tidak) — nama heading persis ---
            '187' => 'required|in:Ya,Tidak',
            '188' => 'required|in:Ya,Tidak',
            '189' => 'required|in:Ya,Tidak',
            '190' => 'required|in:Ya,Tidak',
            '191' => 'required|in:Ya,Tidak',
            '192' => 'required|in:Ya,Tidak',
            '193' => 'required|in:Ya,Tidak',

            // --- Spiritual Sebelum (menggunakan teks pilihan) ---
            '194' => 'required|exists:keterangan_shalat_likerts,value',
            '195' => 'required|exists:keterangan_puasa_likerts,value',
            '196' => 'required|exists:keterangan_zakat_infak_likerts,value',
            '197' => 'required|exists:keterangan_lingkungan_keluarga_likerts,value',
            '198' => 'required|exists:keterangan_kebijakan_pemerintah_likerts,value',

            // --- Spiritual Setelah ---
            '199' => 'required|exists:keterangan_shalat_likerts,value',
            '200' => 'required|exists:keterangan_puasa_likerts,value',
            '201' => 'required|exists:keterangan_zakat_infak_likerts,value',
            '202' => 'required|exists:keterangan_lingkungan_keluarga_likerts,value',
            '203' => 'required|exists:keterangan_kebijakan_pemerintah_likerts,value',

            // --- Pembinaan & Pendampingan ---
            '204' => 'required|in:Ya,Tidak',
            '205' => 'required_if:204,Ya|nullable|exists:frekuensi_pendampingan_options,value',
            '206' => 'required_if:204,Ya|nullable|string',
            '207' => 'required_if:204,Ya|nullable|string',
            '208' => 'nullable|in:Ya,Tidak',
            '209' => 'nullable|in:Ya,Tidak',
            '210' => 'nullable|in:Ya,Tidak',
        ];
    }
}

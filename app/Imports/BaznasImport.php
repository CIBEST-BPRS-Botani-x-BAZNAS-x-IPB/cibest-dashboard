<?php

namespace App\Imports;

use App\Models\AkadPembiayaanCheckbox;
use App\Models\JangkaWaktuOption;
use App\Models\JenisKelaminOption;
use App\Models\JenisPekerjaanOption;
use App\Models\KeteranganKebijakanPemerintahLikert;
use App\Models\KeteranganLingkunganKeluargaLikert;
use App\Models\KeteranganPuasaLikert;
use App\Models\KeteranganShalatLikert;
use App\Models\KeteranganZakatInfakLikert;
use App\Models\LembagaZiswafCheckbox;
use App\Models\PembiayaanLainCheckbox;
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

class BaznasImport extends BaseImport
{
    public function startRow(): int
    {
        return 2;
    }

    public function mapping(int $index): array|string|null
    {
        $map = [
            0 => 'No',
            1 => 'Tanggal Pengisian',
            2 => 'OPZ Surveyor',
            3 => 'OPZ Type',
            4 => 'OPZ Level',
            5 => 'Asnaf',
            6 => 'Nama Lengkap',
            7 => 'NIK',
            8 => 'Tahun Lahir',
            9 => 'Jenis Kelamin',
            10 => 'Nomor HP',
            11 => 'Email',
            12 => 'Apakah Anda Sebagai Keluarga',
            13 => 'Nama Kepala Keluarga',
            14 => 'Jenis Kelamin Kepala Keluarga',
            15 => 'Tahun Lahir Kepala Keluarga',
            16 => 'Jumlah Anggota Keluarga',
            17 => 'Apakah Anda Mempunyai Tabungan',
            18 => 'Penghasilan Sebelum Menerima Zakat',
            19 => 'Total Penghasilan Saat Ini',
            20 => 'Terkahir Meneriman Bantuan Zakat',
            21 => 'Nilai Bantuan yang Diterima Baznas',
            22 => 'Alamat Sesuai KTP',
            23 => 'Kode pos',
            24 => 'Provinsi',
            25 => 'Kab/Kota',
            26 => 'Kecamatan',
            27 => 'Desa/Kelurahan',
            28 => 'Pinpoint',
            29 => '101. Bidang Program',
            30 => '102. Nama Program yang diterima (misalnya : Zmart/Lumbung Pangan)',
            31 => '103. Apakah sebelum menerima zakat mustahik sudah memiliki usaha ?',
            32 => '104. Apakah usaha ada masih berjalan hingga hari ini (jika program yang diberikan dalan bentuk bantuan modal usaha)',
            33 => '105. Berapakah Keuntungan/Profit usaha per bulan?',
            34 => '106. Kapan pertama kali keluarga Ibu/Bapak/Sdr menerima bantuan zakat (Bulan dan Tahun) ?',
            35 => '107. Selama ini, sudah berapa kali Ibu/Bapak/Sdr menerima bantuan zakat dalam satu tahun?',
            36 => '108. Siapakah Anggota Keluarga yang menerima bantuan Zakat ?',
            37 => '201. Pembinaan Spiritual  (pengajian/pertemuan rutin) sekurang-kurangnya 1x dalam satu (1) bulan',
            38 => '202. Pembinaan dan peningkatan kapasitas usaha sekurang-kurangnya 1x dalam enam (6) bulan',
            39 => '203. Pendampingan rutin (monitoring program) sekurang-kurangnya 1x dalam 1 bulan',
            40 => '301. Kegiatan Ibadah Shalat Keluarga >> sebelum',
            41 => '301. Kegiatan Ibadah Shalat Keluarga >> sesudah',
            42 => '302. Kegiatan Ibadah Puasa Keluarga >> sebelum',
            43 => '302. Kegiatan Ibadah Puasa Keluarga >> sesudah',
            44 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sebelum',
            45 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sesudah',
            46 => '304. Lingkungan Keluarga >> sebelum',
            47 => '304. Lingkungan Keluarga >> sesudah',
            48 => '305. Kebijakan Pemerintah Setempat >> sebelum',
            49 => '305. Kebijakan Pemerintah Setempat >> sesudah',
            50 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sebelum',
            51 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sesudah',
            52 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sebelum',
            53 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sesudah',
            54 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sebelum',
            55 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sesudah',
            56 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sebelum',
            57 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sesudah',
            58 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sebelum',
            59 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sesudah',
            60 => '501. Akses permodalan terhadap lembaga keuangan >> sebelum',
            61 => '501. Akses permodalan terhadap lembaga keuangan >> sesudah',
            62 => '502. Akses terhadap pasar >> sebelum',
            63 => '502. Akses terhadap pasar >> sesudah',
            64 => '503. Tingkat pendapatan >> sebelum',
            65 => '503. Tingkat pendapatan >> sesudah',
            66 => '504. Kepemilikan tabungan >> sebelum',
            67 => '504. Kepemilikan tabungan >> sesudah',
            68 => '505. Pertambahan Aset >> sebelum',
            69 => '505. Pertambahan Aset >> sesudah',
            70 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sebelum',
            71 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sesudah',
            72 => '602.  Memiliki tempat pembuangan dan pengolahan limbah >> sebelum',
            73 => '602.  Memiliki tempat pembuangan dan pengolahan limbah >> sesudah',
            74 => '603. Memiliki sumber air bersih dan layak konsumsi >> sebelum',
            75 => '603. Memiliki sumber air bersih dan layak konsumsi >> sesudah',
            76 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sebelum',
            77 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sesudah',
            78 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sebelum',
            79 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sesudah',
            80 => '702. Mengikuti pelatihan terkait usaha >> sebelum',
            81 => '702. Mengikuti pelatihan terkait usaha >> sesudah',
            82 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sebelum',
            83 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sesudah',
            84 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sebelum',
            85 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sesudah',
            86 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sebelum',
            87 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sesudah',
            88 => 'Konsumsi Pangan (Makanan Pokok, Sayur Mayur, Makanan Kering, Daging/ikan, Susu/Telur. Dll) *satu keluarga, akumulasi selama 1 Minggu terakhir',
            89 => 'Rokok,Tembakau *satu keluarga, akumulasi selama 1 Minggu terakhir',
            90 => 'Listrik *satu keluarga, akumulasi selama 1 Bulan terakhir',
            91 => 'Air *satu keluarga, akumulasi selama 1 Bulan terakhir',
            92 => 'Gas/Bahan Bakar Lainnya *satu keluarga, akumulasi selama 1 Bulan terakhir',
            93 => 'Komunikasi (Pembayaran rekening telepon dan pembelian voucher/isi pulsa, Kartu Perdana, Paket data internet) *satu keluarga, akumulasi selama 1 bulan terakhir',
            94 => 'Kebutuhan Perawatan Badan dan Muka (Mencakup sabun mandi, perlengkapan cukur, kosmetik dll) *satu keluarga, akumulasi selama 1 bulan terakhir',
            95 => 'Rekreasi dan Hiburan (Mencakup nonton, teater/bioskop, jalan-jalan,peralatan olah raga,Koran, majalah, dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir',
            96 => 'Transportasi (Mencakup biaya bis, ojek, angkot, perahu, dan biaya perbaikan kendaraan, bahan bakar kendaraan dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir',
            97 => 'Biaya Sewa Rumah/Kontrakan *satu keluarga, akumulasi selama 1 bulan terakhir',
            98 => 'Angsuran Kredit/Cicilan *satu keluarga, akumulasi selama 1 bulan terakhir',
            99 => 'Biaya Sekolah (SPP, Uang Saku, Buku, Seragam) *satu keluarga, akumulasi selama 1 bulan terakhir',
            100 => 'Pakaian untuk anak-anak dan orang dewasa (Mencakup sepatu, topi, kemeja, celana, pakaian anak-anak, pria dan wanita, baju lebaran, dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir',
            101 => 'Biaya kesehatan (Mencakup biaya rumah sakit, Puskesmas, konsultasi dokter praktek, bidan, dukun, mantri, obat-obatan dan lainnya) *satu keluarga, selama 1 Tahun terakhir',
            102 => 'Sumbangan dan hadiah (Mencakup pernikahan, sunatan,sedekah, kado dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir',
            103 => '101. Bidang Program >> bobot',
            104 => '102. Nama Program yang diterima (misalnya : Zmart/Lumbung Pangan) >> bobot',
            105 => '103. Apakah sebelum menerima zakat mustahik sudah memiliki usaha ? >> bobot',
            106 => '104. Apakah usaha ada masih berjalan hingga hari ini (jika program yang diberikan dalan bentuk bantuan modal usaha) >> bobot',
            107 => '105. Berapakah Keuntungan/Profit usaha per bulan? >> bobot',
            108 => '106. Kapan pertama kali keluarga Ibu/Bapak/Sdr menerima bantuan zakat (Bulan dan Tahun) ? >> bobot',
            109 => '107. Selama ini, sudah berapa kali Ibu/Bapak/Sdr menerima bantuan zakat dalam satu tahun? >> bobot',
            110 => '108. Siapakah Anggota Keluarga yang menerima bantuan Zakat ? >> bobot',
            111 => '201. Pembinaan Spiritual  (pengajian/pertemuan rutin) sekurang-kurangnya 1x dalam satu (1) bulan >> bobot',
            112 => '202. Pembinaan dan peningkatan kapasitas usaha sekurang-kurangnya 1x dalam enam (6) bulan >> bobot',
            113 => '203. Pendampingan rutin (monitoring program) sekurang-kurangnya 1x dalam 1 bulan >> bobot',
            114 => '301. Kegiatan Ibadah Shalat Keluarga >> sebelum >> bobot',
            115 => '301. Kegiatan Ibadah Shalat Keluarga >> sesudah >> bobot',
            116 => '302. Kegiatan Ibadah Puasa Keluarga >> sebelum >> bobot',
            117 => '302. Kegiatan Ibadah Puasa Keluarga >> sesudah >> bobot',
            118 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sebelum >> bobot',
            119 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sesudah >> bobot',
            120 => '304. Lingkungan Keluarga >> sebelum >> bobot',
            121 => '304. Lingkungan Keluarga >> sesudah >> bobot',
            122 => '305. Kebijakan Pemerintah Setempat >> sebelum >> bobot',
            123 => '305. Kebijakan Pemerintah Setempat >> sesudah >> bobot',
            124 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sebelum >> bobot',
            125 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sesudah >> bobot',
            126 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sebelum >> bobot',
            127 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sesudah >> bobot',
            128 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sebelum >> bobot',
            129 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sesudah >> bobot',
            130 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sebelum >> bobot',
            131 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sesudah >> bobot',
            132 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sebelum >> bobot',
            133 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sesudah >> bobot',
            134 => '501. Akses permodalan terhadap lembaga keuangan >> sebelum >> bobot',
            135 => '501. Akses permodalan terhadap lembaga keuangan >> sesudah >> bobot',
            136 => '502. Akses terhadap pasar >> sebelum >> bobot',
            137 => '502. Akses terhadap pasar >> sesudah >> bobot',
            138 => '503. Tingkat pendapatan >> sebelum >> bobot',
            139 => '503. Tingkat pendapatan >> sesudah >> bobot',
            140 => '504. Kepemilikan tabungan >> sebelum >> bobot',
            141 => '504. Kepemilikan tabungan >> sesudah >> bobot',
            142 => '505. Pertambahan Aset >> sebelum >> bobot',
            143 => '505. Pertambahan Aset >> sesudah >> bobot',
            144 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sebelum >> bobot',
            145 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sesudah >> bobot',
            146 => '602. Memiliki tempat pembuangan dan pengolahan limbah >> sebelum >> bobot',
            147 => '602. Memiliki tempat pembuangan dan pengolahan limbah >> sesudah >> bobot',
            148 => '603. Memiliki sumber air bersih dan layak konsumsi >> sebelum >> bobot',
            149 => '603. Memiliki sumber air bersih dan layak konsumsi >> sesudah >> bobot',
            150 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sebelum >> bobot',
            151 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sesudah >> bobot',
            152 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sebelum >> bobot',
            153 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sesudah >> bobot',
            154 => '702. Mengikuti pelatihan terkait usaha >> sebelum >> bobot',
            155 => '702. Mengikuti pelatihan terkait usaha >> sesudah >> bobot',
            156 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sebelum >> bobot',
            157 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sesudah >> bobot',
            158 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sebelum >> bobot',
            159 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sesudah >> bobot',
            160 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sebelum >> bobot',
            161 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sesudah >> bobot',
            162 => 'Konsumsi Pangan (Makanan Pokok, Sayur Mayur, Makanan Kering, Daging/ikan, Susu/Telur. Dll) *satu keluarga, akumulasi selama 1 Minggu terakhir >> bobot',
            163 => 'Rokok,Tembakau *satu keluarga, akumulasi selama 1 Minggu terakhir >> bobot',
            164 => 'Listrik *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            165 => 'Air *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            166 => 'Gas/Bahan Bakar Lainnya *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            167 => 'Komunikasi (Pembayaran rekening telepon dan pembelian voucher/isi pulsa, Kartu Perdana, Paket data internet) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            168 => 'Kebutuhan Perawatan Badan dan Muka (Mencakup sabun mandi, perlengkapan cukur, kosmetik dll) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            169 => 'Rekreasi dan Hiburan (Mencakup nonton, teater/bioskop, jalan-jalan,peralatan olah raga,Koran, majalah, dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            170 => 'Transportasi (Mencakup biaya bis, ojek, angkot, perahu, dan biaya perbaikan kendaraan, bahan bakar kendaraan dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            171 => 'Biaya Sewa Rumah/Kontrakan *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            172 => 'Angsuran Kredit/Cicilan *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            173 => 'Biaya Sekolah (SPP, Uang Saku, Buku, Seragam) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            174 => 'Pakaian untuk anak-anak dan orang dewasa (Mencakup sepatu, topi, kemeja, celana, pakaian anak-anak, pria dan wanita, baju lebaran, dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
            175 => 'Biaya kesehatan (Mencakup biaya rumah sakit, Puskesmas, konsultasi dokter praktek, bidan, dukun, mantri, obat-obatan dan lainnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
            176 => 'Sumbangan dan hadiah (Mencakup pernikahan, sunatan,sedekah, kado dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
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
                'jenis_id' => $this->getOptionId(JenisPekerjaanOption::class, $row[$base + 2], $base + 2, true),
                'rata_rata_pendapatan' => $row[$base + 3] ?? 0,
                'pendapatan_tidak_tetap' => $row[$base + 4] ?? 0,
                'pendapatan_aset' => $row[$base + 5] ?? 0,
                'total_pendapatan' => $row[$base + 6] ?? 0,
            ];
        }

        return $data;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $this->data[] = [
                // --- Enumerator ---
                'nama_enumerator'           => $row[2],
                'waktu_pengambilan_data'    => Carbon::parse($row[1])->format('Y-m-d'),
    
                // --- Karakteristik Responden ---
                'nama_responden'    => $row[4],
                'nomor_kontak'      => $row[5] ?? null,
                'alamat'            => $row[6],
                'province_id'       => $this->getOptionId(Province::class, Str::headline($row[7]), 7),
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

                // // --- Pembinaan & Pendampingan ---
                // 'pembinaan_pendampingan_section_id' => $row['1.  Apakah Anda pernah mendapatkan pendampingan, pelatihan, atau bimbingan selama menerima bantuan?'] ?? null,
            ];
        }
    }

    public function rules(): array
    {
        return [
            // --- Enumerator ---
            '2' => 'required|string',
            '1' => 'required|date',
        ];
    }
}

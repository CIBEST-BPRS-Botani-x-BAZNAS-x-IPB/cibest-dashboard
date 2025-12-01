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
            // 103 => '101. Bidang Program >> bobot',
            // 104 => '102. Nama Program yang diterima (misalnya : Zmart/Lumbung Pangan) >> bobot',
            // 105 => '103. Apakah sebelum menerima zakat mustahik sudah memiliki usaha ? >> bobot',
            // 106 => '104. Apakah usaha ada masih berjalan hingga hari ini (jika program yang diberikan dalan bentuk bantuan modal usaha) >> bobot',
            // 107 => '105. Berapakah Keuntungan/Profit usaha per bulan? >> bobot',
            // 108 => '106. Kapan pertama kali keluarga Ibu/Bapak/Sdr menerima bantuan zakat (Bulan dan Tahun) ? >> bobot',
            // 109 => '107. Selama ini, sudah berapa kali Ibu/Bapak/Sdr menerima bantuan zakat dalam satu tahun? >> bobot',
            // 110 => '108. Siapakah Anggota Keluarga yang menerima bantuan Zakat ? >> bobot',
            // 111 => '201. Pembinaan Spiritual  (pengajian/pertemuan rutin) sekurang-kurangnya 1x dalam satu (1) bulan >> bobot',
            // 112 => '202. Pembinaan dan peningkatan kapasitas usaha sekurang-kurangnya 1x dalam enam (6) bulan >> bobot',
            // 113 => '203. Pendampingan rutin (monitoring program) sekurang-kurangnya 1x dalam 1 bulan >> bobot',
            // 114 => '301. Kegiatan Ibadah Shalat Keluarga >> sebelum >> bobot',
            // 115 => '301. Kegiatan Ibadah Shalat Keluarga >> sesudah >> bobot',
            // 116 => '302. Kegiatan Ibadah Puasa Keluarga >> sebelum >> bobot',
            // 117 => '302. Kegiatan Ibadah Puasa Keluarga >> sesudah >> bobot',
            // 118 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sebelum >> bobot',
            // 119 => '303. Kegiatan Ibadah Zakat&Infak Keluarga >> sesudah >> bobot',
            // 120 => '304. Lingkungan Keluarga >> sebelum >> bobot',
            // 121 => '304. Lingkungan Keluarga >> sesudah >> bobot',
            // 122 => '305. Kebijakan Pemerintah Setempat >> sebelum >> bobot',
            // 123 => '305. Kebijakan Pemerintah Setempat >> sesudah >> bobot',
            // 124 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sebelum >> bobot',
            // 125 => '401. Kepercayaan terhadap fasilitator (pendamping program, menyangkut kapabilitas dan kemampuan pendamping). >> sesudah >> bobot',
            // 126 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sebelum >> bobot',
            // 127 => '402. Memiliki jaringan informasi pasar (permintaan pasar, kebutuhan konsumen, persaingan harga, dan sistem distribusi) >> sesudah >> bobot',
            // 128 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sebelum >> bobot',
            // 129 => '403. Partisipasi untuk masyarakat yang mengalami musibah (membantu orang sakit, meninggal) /bencana (sosial/alam) >> sesudah >> bobot',
            // 130 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sebelum >> bobot',
            // 131 => '404. Berpartisipasi dalam kegiatan kemasyarakatan (gotong royong, kerja bakti, dsb) >> sesudah >> bobot',
            // 132 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sebelum >> bobot',
            // 133 => '405. Mengikuti kegiatan kemasyarakatan berbasis kelembagaan sosial dan/atau tujuan tertentu (Posyandu, Tagana, DKM, PKK, Karang Taruna) >> sesudah >> bobot',
            // 134 => '501. Akses permodalan terhadap lembaga keuangan >> sebelum >> bobot',
            // 135 => '501. Akses permodalan terhadap lembaga keuangan >> sesudah >> bobot',
            // 136 => '502. Akses terhadap pasar >> sebelum >> bobot',
            // 137 => '502. Akses terhadap pasar >> sesudah >> bobot',
            // 138 => '503. Tingkat pendapatan >> sebelum >> bobot',
            // 139 => '503. Tingkat pendapatan >> sesudah >> bobot',
            // 140 => '504. Kepemilikan tabungan >> sebelum >> bobot',
            // 141 => '504. Kepemilikan tabungan >> sesudah >> bobot',
            // 142 => '505. Pertambahan Aset >> sebelum >> bobot',
            // 143 => '505. Pertambahan Aset >> sesudah >> bobot',
            // 144 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sebelum >> bobot',
            // 145 => '601. Memiliki tempat pembuangan dan pengolahan sampah >> sesudah >> bobot',
            // 146 => '602. Memiliki tempat pembuangan dan pengolahan limbah >> sebelum >> bobot',
            // 147 => '602. Memiliki tempat pembuangan dan pengolahan limbah >> sesudah >> bobot',
            // 148 => '603. Memiliki sumber air bersih dan layak konsumsi >> sebelum >> bobot',
            // 149 => '603. Memiliki sumber air bersih dan layak konsumsi >> sesudah >> bobot',
            // 150 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sebelum >> bobot',
            // 151 => '604. Mengetahui risiko bencana di lingkungan tempat melakukan proses usaha >> sesudah >> bobot',
            // 152 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sebelum >> bobot',
            // 153 => '701. Menggali informasi-informasi terbaru terkait pengembangan usaha >> sesudah >> bobot',
            // 154 => '702. Mengikuti pelatihan terkait usaha >> sebelum >> bobot',
            // 155 => '702. Mengikuti pelatihan terkait usaha >> sesudah >> bobot',
            // 156 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sebelum >> bobot',
            // 157 => '703. Mengembangkan keahlian baru terkait diversifikasi usaha. >> sesudah >> bobot',
            // 158 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sebelum >> bobot',
            // 159 => '704. Berbagi pengalaman terkait usaha (sekedar diskusi informal dan/atau studi banding) >> sesudah >> bobot',
            // 160 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sebelum >> bobot',
            // 161 => '705. Komitmen Untuk Menjaga Kuantitas dan Kontinuitas Usaha >> sesudah >> bobot',
            // 162 => 'Konsumsi Pangan (Makanan Pokok, Sayur Mayur, Makanan Kering, Daging/ikan, Susu/Telur. Dll) *satu keluarga, akumulasi selama 1 Minggu terakhir >> bobot',
            // 163 => 'Rokok,Tembakau *satu keluarga, akumulasi selama 1 Minggu terakhir >> bobot',
            // 164 => 'Listrik *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            // 165 => 'Air *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            // 166 => 'Gas/Bahan Bakar Lainnya *satu keluarga, akumulasi selama 1 Bulan terakhir >> bobot',
            // 167 => 'Komunikasi (Pembayaran rekening telepon dan pembelian voucher/isi pulsa, Kartu Perdana, Paket data internet) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 168 => 'Kebutuhan Perawatan Badan dan Muka (Mencakup sabun mandi, perlengkapan cukur, kosmetik dll) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 169 => 'Rekreasi dan Hiburan (Mencakup nonton, teater/bioskop, jalan-jalan,peralatan olah raga,Koran, majalah, dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 170 => 'Transportasi (Mencakup biaya bis, ojek, angkot, perahu, dan biaya perbaikan kendaraan, bahan bakar kendaraan dan sejenisnya) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 171 => 'Biaya Sewa Rumah/Kontrakan *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 172 => 'Angsuran Kredit/Cicilan *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 173 => 'Biaya Sekolah (SPP, Uang Saku, Buku, Seragam) *satu keluarga, akumulasi selama 1 bulan terakhir >> bobot',
            // 174 => 'Pakaian untuk anak-anak dan orang dewasa (Mencakup sepatu, topi, kemeja, celana, pakaian anak-anak, pria dan wanita, baju lebaran, dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
            // 175 => 'Biaya kesehatan (Mencakup biaya rumah sakit, Puskesmas, konsultasi dokter praktek, bidan, dukun, mantri, obat-obatan dan lainnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
            // 176 => 'Sumbangan dan hadiah (Mencakup pernikahan, sunatan,sedekah, kado dan sejenisnya) *satu keluarga, selama 1 Tahun terakhir >> bobot',
        ];

        return $map[$index] ?? null;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $this->data[] = [
                // --- Enumerator/OPZ Surveyor ---
                'nama_enumerator'           => $row[2],
                'waktu_pengambilan_data'    => Carbon::parse($row[1])->format('Y-m-d'),

                // --- Karakteristik Responden ---
                'nama_responden'    => $row[6],
                'nomor_kontak'      => $row[10] ?? null,
                'alamat'            => $row[22],
                'province_id'       => $this->getOptionId(Province::class, Str::headline($row[24]), 24),
                'kabupaten_kota'    => $row[25],
                'kecamatan'         => $row[26],
                'desa_kelurahan'    => $row[27],
                'usia'              => Carbon::now()->year - ($row[8] ?? Carbon::now()->year),
                'jenis_kelamin_option_id'         => $this->getOptionId(JenisKelaminOption::class, $row[9] === 'P' ? 'Laki-laki' : 'Perempuan', 9),
                'status_perkawinan_option_id'     => null,
                'pendidikan_formal_option_id'     => null,
                'pendidikan_nonformal_option_id'  => null,

                // --- Usaha dan Profit ---
                'memiliki_usaha_sendiri' => ($row[31] ?? null) === "Ya",
                'rata_rata_profit'       => $row[33] ?? 0,

                // --- Bantuan ZISWAF ---
                'bantuan_ziswaf_section' => !empty($row[34]) ?
                    [
                        'bulan_tahun_menerima' => Carbon::parse($row[34])->format('Y-m-d'),
                        'lembaga_ziswaf_checkbox' => $this->getCheckboxId(LembagaZiswafCheckbox::class, $this->detectLembagaZiswaf($row[3], $row[4])),
                        'program_bantuan_checkbox' => $this->getCheckboxId(ProgramBantuanCheckbox::class, $row[30]),
                        'frekuensi_penerimaan' => $row[35],
                        'total_nilai_bantuan' => $row[21],
                        'bantuan_konsumtif_section' => [
                            'pangan' => 0,
                            'kesehatan' => 0,
                            'pendidikan' => 0,
                            'lainnya' => 0,
                        ],
                        'bantuan_produktif_section' => [
                            'modal_usaha' => 0,
                            'peralatan_usaha' => 0,
                            'lainnya' => 0,
                        ],
                    ]
                    : null,

                // --- Pembiayaan Syariah --- (belum)
                'pembiayaan_syariah_section' => null, // No equivalent fields in Baznas mapping

                // --- Karakteristik Rumah Tangga --- (belum)
                'karakteristik_rumah_tangga_section' => [
                    'nama_anggota' => $row[13],
                    'hubungan_kepala_keluarga' => 'Kepala Keluarga',
                    'usia' => Carbon::now()->year - ($row[15] ?? Carbon::now()->year),
                    'jenis_kelamin_id' => $this->getOptionId(JenisKelaminOption::class, $row[14] === 'P' ? 'Laki-laki' : 'Perempuan', 9),
                ],

                // --- Pendapatan Ketenagakerjaan ---
                'pendapatan_ketenagakerjaan_section' => [], // No equivalent structure in Baznas mapping

                // --- Pengeluaran Rumah Tangga ---
                'pangan'            => $row[88] ?? 0,
                'rokok_tembakau'    => $row[89] ?? 0,
                'sewa_rumah'        => $row[97] ?? 0,
                'listrik'           => $row[90] ?? 0,
                'air'               => $row[91] ?? 0,
                'bahan_bakar'       => $row[92] ?? 0,
                'sandang'           => $row[100] ?? 0,
                'pendidikan'        => $row[99] ?? 0,
                'kesehatan'         => $row[101] ?? 0,
                'transportasi'      => $row[96] ?? 0,
                'komunikasi'        => $row[93] ?? 0,
                'rekreasi_hiburan'  => $row[95] ?? 0,
                'perawatan_badan'   => $row[94] ?? 0,
                'sosial_keagamaan'  => $row[102] ?? 0,
                'angsuran_kredit'   => $row[98] ?? 0,
                'lain_lain'         => 0,

                // --- Tabungan ---
                'memiliki_tabungan_bank_konvensional'     => ($row[17] ?? null) === "Ya",
                'memiliki_tabungan_bank_syariah'          => null,
                'memiliki_tabungan_koperasi_konvensional' => null,
                'memiliki_tabungan_koperasi_syariah'      => null,
                'memiliki_tabungan_lembaga_zakat'         => null,
                'mengikuti_arisan_rutin'                  => null,
                'memiliki_simpanan_rumah'                 => null,

                // --- Spiritual Sebelum ---
                'shalat_sebelum'               => $this->getOptionId(KeteranganShalatLikert::class, $row[40], 40),
                'puasa_sebelum'                => $this->getOptionId(KeteranganPuasaLikert::class, $row[42], 42),
                'zakat_infak_sebelum'          => $this->getOptionId(KeteranganZakatInfakLikert::class, $row[44], 44),
                'lingkungan_keluarga_sebelum'  => $this->getOptionId(KeteranganLingkunganKeluargaLikert::class, $row[46], 46),
                'kebijakan_pemerintah_sebelum' => $this->getOptionId(KeteranganKebijakanPemerintahLikert::class, $row[48], 48),

                // --- Spiritual Setelah ---
                'shalat_setelah'               => $this->getOptionId(KeteranganShalatLikert::class, $row[41], 41),
                'puasa_setelah'                => $this->getOptionId(KeteranganPuasaLikert::class, $row[43], 43),
                'zakat_infak_setelah'          => $this->getOptionId(KeteranganZakatInfakLikert::class, $row[45], 45),
                'lingkungan_keluarga_setelah'  => $this->getOptionId(KeteranganLingkunganKeluargaLikert::class, $row[47], 47),
                'kebijakan_pemerintah_setelah' => $this->getOptionId(KeteranganKebijakanPemerintahLikert::class, $row[49], 49),

                // --- Pembinaan & Pendampingan ---
                'pembinaan_pendampingan_section' => $row[204] === 'Ya' ? 
                    [
                        'pembinaan_spiritual' => ($row[37] ?? null) === "Ya",
                        'pembinaan_usaha' => ($row[38] ?? null) === "Ya",
                        'pendampingan_rutin' => ($row[39] ?? null) === "Ya",
                    ]
                    : null,
            ];
        }
    }

    public function rules(): array
    {
        return [
            // --- Enumerator ---
            '2' => 'required|string',
            '1' => 'required|date',

            // --- Karakteristik Responden ---
            '6' => 'required|string', 
            '10' => 'nullable|string', 
            '22' => 'required|string',
            '24' => 'required|exists:provinces,value',
            '25' => 'required|string',
            '26' => 'required|string',
            '27' => 'required|string',
            '8' => 'required|integer|min:0',
            '9' => 'required|in:P,L',

            // --- Usaha dan Profit --- 
            '31' => 'required|in:Ya,Tidak',
            '33' => 'nullable|integer|min:0',

            // --- Bantuan ZISWAF ---
            '34' => 'nullable|date',
            '3'  => 'required_with:34|nullable|in:baz,laz',
            '30' => 'required_with:34|nullable|string',
            '35' => 'required_with:34|nullable|integer|min:0',
            '21' => 'required_with:34|nullable|integer|min:0',

            // --- Pengeluaran Rumah Tangga ---
            '88' => 'nullable|integer|min:0',
            '89' => 'nullable|integer|min:0',
            '90' => 'nullable|integer|min:0',
            '91' => 'nullable|integer|min:0',
            '92' => 'nullable|integer|min:0',
            '93' => 'nullable|integer|min:0',
            '94' => 'nullable|integer|min:0',
            '95' => 'nullable|integer|min:0',
            '96' => 'nullable|integer|min:0',
            '97' => 'nullable|integer|min:0',
            '98' => 'nullable|integer|min:0',
            '99' => 'nullable|integer|min:0',
            '100' => 'nullable|integer|min:0',
            '101' => 'nullable|integer|min:0',
            '102' => 'nullable|integer|min:0',

            // --- Tabungan ---
            '17' => 'required|in:Ya,Tidak',

            // --- Spiritual Sebelum ---
            '40' => 'required|exists:keterangan_shalat_likerts,value', 
            '42' => 'required|exists:keterangan_puasa_likerts,value', 
            '44' => 'required|exists:keterangan_zakat_infak_likerts,value', 
            '46' => 'required|exists:keterangan_lingkungan_keluarga_likerts,value',
            '48' => 'required|exists:keterangan_kebijakan_pemerintah_likerts,value',

            // --- Spiritual Setelah ---
            '41' => 'required|exists:keterangan_shalat_likerts,value',
            '43' => 'required|exists:keterangan_puasa_likerts,value',
            '45' => 'required|exists:keterangan_zakat_infak_likerts,value',
            '47' => 'required|exists:keterangan_lingkungan_keluarga_likerts,value',
            '49' => 'required|exists:keterangan_kebijakan_pemerintah_likerts,value',

            // --- Pembinaan & Pendampingan ---
            '37' => 'required|in:Ya,Tidak',
            '38' => 'required|in:Ya,Tidak',
            '39' => 'required|in:Ya,Tidak',
        ];
    }
}

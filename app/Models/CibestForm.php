<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CibestForm extends Model
{
    /** @use HasFactory<\Database\Factories\CibestFormFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_enumerator',
        'waktu_pengambilan_data',

        // KARAKTERISTIK RESPONDEN
        'nama_responden',
        'nomor_kontak',
        'alamat',
        'province_id',
        'kabupaten_kota',
        'kecamatan',
        'desa_kelurahan',
        'usia',
        'jenis_kelamin_option_id',
        'status_perkawinan_option_id',
        'pendidikan_formal_option_id',
        'pendidikan_nonformal_option_id',
        'memiliki_usaha_sendiri',
        'rata_rata_profit',

        // II
        'bantuan_ziswaf_section_id',

        // III
        'pembiayaan_syariah_section_id',

        // VI. Pengeluaran Rumah Tangga
        'pangan',
        'rokok_tembakau',
        'sewa_rumah',
        'listrik',
        'air',
        'bahan_bakar',
        'sandang',
        'pendidikan',
        'kesehatan',
        'transportasi',
        'komunikasi',
        'rekreasi_hiburan',
        'perawatan_badan',
        'sosial_keagamaan',
        'angsuran_kredit',
        'lain_lain',

        // VII. Tabungan & Simpanan
        'memiliki_tabungan_bank_konvensional',
        'memiliki_tabungan_bank_syariah',
        'memiliki_tabungan_koperasi_konvensional',
        'memiliki_tabungan_koperasi_syariah',
        'memiliki_tabungan_lembaga_zakat',
        'mengikuti_arisan_rutin',
        'memiliki_simpanan_rumah',

        // VIII. Spiritual Sebelum
        'shalat_sebelum',
        'puasa_sebelum',
        'zakat_infak_sebelum',
        'lingkungan_keluarga_sebelum',
        'kebijakan_pemerintah_sebelum',

        // VIII. Spiritual Setelah
        'shalat_setelah',
        'puasa_setelah',
        'zakat_infak_setelah',
        'lingkungan_keluarga_setelah',
        'kebijakan_pemerintah_setelah',

        // IX
        'pembinaan_pendampingan_section_id',
    ];

    // --- User (enumerator)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- Karakteristik (belongsTo karena each is option)
    public function jenisKelamin()
    {
        return $this->belongsTo(JenisKelaminOption::class);
    }

    public function statusPerkawinan()
    {
        return $this->belongsTo(StatusPerkawinanOption::class);
    }

    public function pendidikanFormal()
    {
        return $this->belongsTo(PendidikanFormalOption::class);
    }

    public function pendidikanNonformal()
    {
        return $this->belongsTo(PendidikanNonformalOption::class);
    }

    // --- II. Bantuan ZISWAF (one to one)
    public function bantuanZiswafSection()
    {
        return $this->belongsTo(BantuanZiswafSection::class);
    }

    // --- III. Pembiayaan Syariah
    public function pembiayaanSyariahSection()
    {
        return $this->belongsTo(PembiayaanSyariahSection::class);
    }

    // --- IV. Karakteristik Rumah Tangga (one-to-many)
    public function karakteristikRumahTanggaSections()
    {
        return $this->hasMany(KarakteristikRumahTanggaSection::class);
    }

    // --- V. Pendapatan & Ketenagakerjaan (one-to-many)
    public function pendapatanKetenagakerjaanSections()
    {
        return $this->hasMany(PendapatanKetenagakerjaanSection::class);
    }

    // --- IX. Pembinaan & Pendampingan
    public function pembinaanPendampinganSection()
    {
        return $this->hasOne(PembinaanPendampinganSection::class);
    }

    /*
    |--------------------------------------------------------------------------
    | LIKERT (semua belongsTo)
    |--------------------------------------------------------------------------
    */

    public function shalatSebelum()
    {
        return $this->belongsTo(KeteranganShalatLikert::class, 'shalat_sebelum');
    }

    public function puasaSebelum()
    {
        return $this->belongsTo(KeteranganPuasaLikert::class, 'puasa_sebelum');
    }

    public function zakatInfakSebelum()
    {
        return $this->belongsTo(KeteranganZakatInfakLikert::class, 'zakat_infak_sebelum');
    }

    public function lingkunganKeluargaSebelum()
    {
        return $this->belongsTo(KeteranganLingkunganKeluargaLikert::class, 'lingkungan_keluarga_sebelum');
    }

    public function kebijakanPemerintahSebelum()
    {
        return $this->belongsTo(KeteranganKebijakanPemerintahLikert::class, 'kebijakan_pemerintah_sebelum');
    }

    public function shalatSetelah()
    {
        return $this->belongsTo(KeteranganShalatLikert::class, 'shalat_setelah');
    }

    public function puasaSetelah()
    {
        return $this->belongsTo(KeteranganPuasaLikert::class, 'puasa_setelah');
    }

    public function zakatInfakSetelah()
    {
        return $this->belongsTo(KeteranganZakatInfakLikert::class, 'zakat_infak_setelah');
    }

    public function lingkunganKeluargaSetelah()
    {
        return $this->belongsTo(KeteranganLingkunganKeluargaLikert::class, 'lingkungan_keluarga_setelah');
    }

    public function kebijakanPemerintahSetelah()
    {
        return $this->belongsTo(KeteranganKebijakanPemerintahLikert::class, 'kebijakan_pemerintah_setelah');
    }

    public function cibestQuadrants()
    {
        return $this->hasMany(CibestQuadrant::class, 'form_id');
    }
}

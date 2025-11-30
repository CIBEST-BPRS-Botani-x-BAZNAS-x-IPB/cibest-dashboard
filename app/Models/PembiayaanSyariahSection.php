<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembiayaanSyariahSection extends Model
{
    /** @use HasFactory<\Database\Factories\PembiayaanSyariahSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'bulan_tahun_menerima',
        'lembaga_keuangan_syariah',
        'jangka_waktu_option_id',
        'frekuensi_penerimaan',
        'total_nilai_pembiayaan',
        'lembaga_syariah_lain',
        'lembaga_konvensional',
    ];

    // Kembali ke CibestForm (karena CibestForm punya pembiayaan_syariah_section_id)
    public function cibestForm()
    {
        return $this->belongsTo(CibestForm::class);
    }

    // Pilihan jangka waktu (single select)
    public function jangkaWaktuOption()
    {
        return $this->belongsTo(JangkaWaktuOption::class);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKBOX RELATIONSHIPS (Many-to-Many)
    |--------------------------------------------------------------------------
    */

    // --- Akad Pembiayaan (checkbox)
    public function akadPembiayaanCheckboxes()
    {
        return $this->belongsToMany(AkadPembiayaanCheckbox::class, 'akad_pembiayaan', 'pembiayaan_syariah_section_id', 'akad_pembiayaan_option_id');
    }

    // --- Penggunaan Pembiayaan (checkbox)
    public function penggunaanPembiayaanCheckboxes()
    {
        return $this->belongsToMany(PenggunaanPembiayaanCheckbox::class, 'pembiayaan_penggunaan', 'pembiayaan_id', 'penggunaan_id');
    }

    // --- Pembiayaan Lain (checkbox)
    public function pembiayaanLainCheckboxes()
    {
        return $this->belongsToMany(PembiayaanLainCheckbox::class, 'lain_syariah', 'syariah_id', 'lain_id');
    }
}

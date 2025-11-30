<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BantuanZiswafSection extends Model
{
    /** @use HasFactory<\Database\Factories\BantuanZiswafSectionFactory> */
    use HasFactory;

    protected $fillable = [
        'bulan_tahun_menerima',
        'frekuensi_penerimaan',
        'total_nilai_bantuan',
        'bantuan_konsumtif_section_id',
        'bantuan_produktif_section_id',
    ];

    // --- Kembali ke CibestForm
    // (Karena CibestForm punya bantuan_ziswaf_section_id)
    public function cibestForm()
    {
        return $this->belongsTo(CibestForm::class);
    }

    // --- Konsumtif (one-to-one)
    public function ziswafBantuanKonsumtifSection()
    {
        return $this->hasOne(BantuanKonsumtifSection::class);
    }

    // --- Produktif (one-to-one)
    public function ziswafBantuanProduktifSection()
    {
        return $this->hasOne(BantuanProduktifSection::class);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKBOX RELATIONSHIPS (Many-to-Many)
    |--------------------------------------------------------------------------
    */

    // --- Lembaga ZISWAF (checkbox)
    public function lembagaZiswafCheckboxes()
    {
        return $this->belongsToMany(LembagaZiswafCheckbox::class, 'bantuan_lembaga', 'bantuan_id', 'lembaga_id');
    }

    // --- Program Bantuan (checkbox)
    public function programBantuanCheckboxes()
    {
        return $this->belongsToMany(ProgramBantuanCheckbox::class, 'bantuan_program', 'bantuan_id', 'program_id');
    }

    // --- Pembiayaan Lain (checkbox)
    public function pembiayaanLainCheckboxes()
    {
        return $this->belongsToMany(PembiayaanLainCheckbox::class, 'bantuan_pembiayaan', 'bantuan_id', 'lain_id');
    }
}

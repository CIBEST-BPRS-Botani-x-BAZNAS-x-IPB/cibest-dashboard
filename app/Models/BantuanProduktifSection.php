<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BantuanProduktifSection extends Model
{
    /** @use HasFactory<\Database\Factories\BantuanProduktifSectionFactory> */
    use HasFactory;

    protected $fillable = ['modal_usaha', 'peralatan_usaha', 'lainnya'];
}

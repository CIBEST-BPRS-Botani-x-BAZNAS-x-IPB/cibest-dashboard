<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BantuanKonsumtifSection extends Model
{
    /** @use HasFactory<\Database\Factories\BantuanKonsumtifSectionFactory> */
    use HasFactory;

    protected $fillable = ['pangan', 'kesehatan', 'pendidikan', 'lainnya'];
}

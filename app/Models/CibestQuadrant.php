<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CibestQuadrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'poverty_id',
        'form_id',
        'kuadran_sebelum',
        'kuadran_setelah'
    ];

    public $timestamps = false;

    public function povertyStandard()
    {
        return $this->belongsTo(PovertyStandard::class, 'poverty_id');
    }

    public function cibestForm()
    {
        return $this->belongsTo(CibestForm::class, 'form_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramBantuanCheckbox extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramBantuanCheckboxFactory> */
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['value', 'is_other'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PovertyStandard extends Model
{
    /** @use HasFactory<\Database\Factories\PovertyStandardFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'nilai_keluarga', 'nilai_per_tahun', 'log_natural'
    ];

    // Accessor to calculate index_kesejahteraan_cibest based on related data (this is just for reference - calculation will be done in controller)
    public function getIndexKesejahteraanCibestAttribute()
    {
        // Note: This is illustrative. The actual calculation is done in the DashboardController
        return 0; // Placeholder since this is calculated dynamically
    }

    public function cibestForms()
    {
        return $this->belongsToMany(CibestForm::class, 'cibest_quadrants', 'poverty_id', 'form_id');
    }
}

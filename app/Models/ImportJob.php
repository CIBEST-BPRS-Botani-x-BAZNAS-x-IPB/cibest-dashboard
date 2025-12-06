<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'type',
        'filename',
        'user_id',
        'status',
        'errors',
        'total_rows',
        'processed_rows',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'errors' => 'array', // Cast errors to array since it will be JSON
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
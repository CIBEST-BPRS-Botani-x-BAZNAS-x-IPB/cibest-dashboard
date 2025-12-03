<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'admin_verification_status',
        'admin_verified_at',
        'admin_verified_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'admin_verified_at' => 'datetime',
            'admin_verification_status' => 'string',
        ];
    }

    /**
     * Relationship to the admin who verified this user
     */
    public function verifiedByAdmin()
    {
        return $this->belongsTo(self::class, 'admin_verified_by');
    }

    /**
     * Scope to get pending verification users
     */
    public function scopePendingVerification($query)
    {
        return $query->where('admin_verification_status', 'pending');
    }

    /**
     * Scope to get verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('admin_verification_status', 'verified');
    }

    /**
     * Scope to get rejected users
     */
    public function scopeRejected($query)
    {
        return $query->where('admin_verification_status', 'rejected');
    }

    /**
     * Check if user is pending admin verification
     */
    public function isPendingVerification(): bool
    {
        return $this->admin_verification_status === 'pending';
    }

    /**
     * Check if user is verified by admin
     */
    public function isAdminVerified(): bool
    {
        return $this->admin_verification_status === 'verified';
    }

    /**
     * Check if user is rejected by admin
     */
    public function isRejected(): bool
    {
        return $this->admin_verification_status === 'rejected';
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use \Illuminate\Auth\MustVerifyEmail;

    protected $fillable = [
        'name',
        'nric',
        'date_of_birth',
        'gender',
        'email',
        'password',
        'contact_phone',
        'address',
        'role',
        'status',
        'is_active',
        'telegram_chat_id',
        'telegram_username',
        'telegram_session',
        'telegram_link_requested_at',
        'telegram_linked_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'telegram_link_requested_at' => 'datetime',
        'telegram_linked_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_active;
    }

    public function getFormattedNricAttribute(): string
    {
        if (empty($this->nric)) return '';
        $clean = preg_replace('/[^0-9]/', '', $this->nric);
        if (strlen($clean) === 12) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 4);
        }
        return $this->nric;
    }

    public function getFormattedContactPhoneAttribute(): string
    {
        if (empty($this->contact_phone)) return '';
        $clean = preg_replace('/[^0-9]/', '', $this->contact_phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && substr($clean, 0, 2) === '01') {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $this->contact_phone;
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) return null;
        return $this->date_of_birth->age;
    }

    public function estatePreRegistrations()
    {
        return $this->hasMany(EstatePreRegistration::class);
    }
}
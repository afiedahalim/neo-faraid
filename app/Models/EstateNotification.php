<?php
// app/Models/EstateNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstateNotification extends Model
{
    protected $table = 'estate_notifications';
    
    protected $fillable = [
        'notification_token',
        'instant_estate_session_id',
        'estate_pre_registration_id',
        'beneficiary_email',
        'beneficiary_name',
        'beneficiary_type',
        'beneficiary_relationship',
        'access_token',
        'status',
        'expires_at',
        'access_count',
        'last_accessed_at',
        'last_accessed_ip',
        'last_accessed_user_agent',
        'accessed_ips',
        'user_agents',
        'first_viewed_at',
        'requires_email_verification',
        'verification_code',
        'max_access_count',
        'notification_sent_at',
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'first_viewed_at' => 'datetime',
        'notification_sent_at' => 'datetime',
        'access_count' => 'integer',
        'max_access_count' => 'integer',
        'requires_email_verification' => 'boolean',
        'accessed_ips' => 'array',
        'user_agents' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected $hidden = [
        'verification_code',
    ];
    
    public function session(): BelongsTo
    {
        return $this->belongsTo(InstantEstateSession::class, 'instant_estate_session_id');
    }
    
    public function estate(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class, 'estate_pre_registration_id');
    }
    
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function isValid(): bool
    {
        return !$this->isExpired() && $this->status !== 'revoked' && $this->status !== 'expired';
    }
    
    public function recordAccess(string $ip = null, string $userAgent = null): void
    {
        $accessedIps = $this->accessed_ips ?? [];
        if ($ip && !in_array($ip, $accessedIps)) {
            $accessedIps[] = $ip;
            $this->accessed_ips = array_slice($accessedIps, -10);
        }
        
        $userAgents = $this->user_agents ?? [];
        if ($userAgent && !in_array($userAgent, $userAgents)) {
            $userAgents[] = $userAgent;
            $this->user_agents = array_slice($userAgents, -5);
        }
        
        $this->increment('access_count');
        $this->last_accessed_at = now();
        $this->last_accessed_ip = $ip;
        $this->last_accessed_user_agent = $userAgent;
        
        if ($this->status === 'pending') {
            $this->status = 'viewed';
            $this->first_viewed_at = $this->first_viewed_at ?? now();
        }
        
        $this->save();
    }
    
    public function getAccessUrl(): string
    {
        return route('beneficiary.access', ['token' => $this->notification_token ?? $this->access_token]);
    }
}
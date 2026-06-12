<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    
    protected $fillable = [
        'estate_pre_registration_id',
        'session_id',
        'action',
        'performed_by',
        'performed_by_name',
        'performed_by_role',
        'details',
        'ip_address',
        'user_agent',
    ];
    
    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function estate(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class, 'estate_pre_registration_id');
    }
    
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    
    public function scopeByEstate($query, int $estateId)
    {
        return $query->where('estate_pre_registration_id', $estateId);
    }
    
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
    
    public function scopeByUser($query, int $userId)
    {
        return $query->where('performed_by', $userId);
    }
    
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rating',
        'message',
        'status',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for approved feedback
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for public feedback
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Get the initials of the user for avatar display
     */
    public function getAvatarInitialsAttribute()
    {
        if ($this->user) {
            $nameParts = explode(' ', $this->user->name);
            $initials = '';
            
            foreach ($nameParts as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
                if (strlen($initials) >= 2) break;
            }
            
            return $initials ?: '??';
        }
        
        return '??';
    }

    /**
     * Get the name for display (user name or anonymous)
     */
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : 'Anonymous User';
    }

    /**
     * Get the email for display
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'status-pending',
            'approved' => 'status-approved',
            'rejected' => 'status-rejected'
        ];
        
        return $classes[$this->status] ?? 'status-pending';
    }

    /**
     * Check if feedback is visible to public
     */
    public function getIsVisibleAttribute()
    {
        return $this->status === 'approved' && $this->is_public;
    }
}
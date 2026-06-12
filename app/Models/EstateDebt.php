<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstateDebt extends Model
{
    protected $table = 'estate_debts';

    protected $fillable = [
        'instant_estate_session_id',
        'creditor_name',
        'amount',
        'amount_paid',
        'description',
        'status',
        'due_date',
        'paid_at',
        'reference_number',
        'payment_method',
        'payment_history',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'payment_history' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'remaining',
        'formatted_amount',
        'formatted_amount_paid',
        'formatted_remaining',
        'is_fully_paid',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(InstantEstateSession::class, 'instant_estate_session_id');
    }

    public function getRemainingAttribute(): float
    {
        return max(0, $this->amount - $this->amount_paid);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'RM ' . number_format($this->amount, 2);
    }

    public function getFormattedAmountPaidAttribute(): string
    {
        return 'RM ' . number_format($this->amount_paid, 2);
    }

    public function getFormattedRemainingAttribute(): string
    {
        return 'RM ' . number_format($this->remaining, 2);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->amount_paid >= $this->amount;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
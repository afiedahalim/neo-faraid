<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeneficiaryAccessLog extends Model
{
    protected $table = 'beneficiary_access_logs';

    protected $fillable = [
        'beneficiary_access_link_id',
        'accessed_at',
        'accessed_ip',
        'accessed_user_agent',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function accessLink(): BelongsTo
    {
        return $this->belongsTo(BeneficiaryAccessLink::class, 'beneficiary_access_link_id');
    }
}
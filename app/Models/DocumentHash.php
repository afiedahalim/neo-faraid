<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentHash extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_hashes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_hash',
        'deceased_nric',
        'death_date',
        'registration_number',
        'first_session_id',
        'last_session_id',
        'upload_count',
        'admin_action',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'death_date' => 'date',
        'upload_count' => 'integer',
    ];

    /**
     * Get the first session that uploaded this document.
     */
    public function firstSession()
    {
        return $this->belongsTo(InstantEstateSession::class, 'first_session_id', 'session_id');
    }

    /**
     * Get the last session that uploaded this document.
     */
    public function lastSession()
    {
        return $this->belongsTo(InstantEstateSession::class, 'last_session_id', 'session_id');
    }

    /**
     * Check if this document was previously approved by admin.
     *
     * @return bool
     */
    public function wasApproved(): bool
    {
        return $this->admin_action === 'approved';
    }

    /**
     * Check if this document was previously rejected by admin.
     *
     * @return bool
     */
    public function wasRejected(): bool
    {
        return $this->admin_action === 'rejected';
    }

    /**
     * Increment the upload count and update the last session ID.
     *
     * @param string $sessionId
     * @return void
     */
    public function incrementUpload(string $sessionId): void
    {
        $this->increment('upload_count');
        $this->last_session_id = $sessionId;
        $this->saveQuietly();
    }

    /**
     * Scope: Find by file hash.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $hash
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByHash($query, string $hash)
    {
        return $query->where('file_hash', $hash);
    }

    /**
     * Scope: Previously rejected documents.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('admin_action', 'rejected');
    }
}
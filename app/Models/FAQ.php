<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'is_published',
        'order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Status check
    public function isPublished()
    {
        return $this->is_published;
    }

    // Get status badge
    public function getStatusBadgeAttribute()
    {
        return $this->is_published ? 
            '<span class="badge bg-success">Published</span>' :
            '<span class="badge bg-warning">Draft</span>';
    }
}
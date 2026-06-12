<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DigitalCredential extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'digital_credentials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'estate_pre_registration_id',
        'platform',
        'username',
        'encrypted_password',
        'security_questions',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'encrypted_password',
        'security_questions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'decrypted_password',
        'masked_password',
        'platform_label',
        'platform_category',
        'platform_icon',
        'platform_category_icon',
        'has_security_questions',
        'has_notes',
        'security_questions_count',
        'credential_summary',
        'is_banking',
        'is_social_media',
        'is_email',
        'is_government',
        'is_investment',
        'is_ewallet',
        'is_cloud_storage',
    ];

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Get the estate pre-registration that owns this credential.
     *
     * @return BelongsTo
     */
    public function estatePreRegistration(): BelongsTo
    {
        return $this->belongsTo(EstatePreRegistration::class);
    }

    // =========================================================================
    // PASSWORD ACCESSORS
    // =========================================================================

    /**
     * Get the decrypted password.
     *
     * @return string
     */
    public function getDecryptedPasswordAttribute(): string
    {
        try {
            if (empty($this->encrypted_password)) {
                return '';
            }
            return Crypt::decryptString($this->encrypted_password);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt credential password', [
                'credential_id' => $this->id,
                'platform' => $this->platform,
                'error' => $e->getMessage(),
            ]);
            return '[Decryption Failed]';
        }
    }

    /**
     * Get the masked password for display.
     *
     * @return string
     */
    public function getMaskedPasswordAttribute(): string
    {
        if (empty($this->encrypted_password)) {
            return '';
        }
        
        try {
            $decrypted = Crypt::decryptString($this->encrypted_password);
            $length = strlen($decrypted);
            
            if ($length <= 2) {
                return '••';
            }
            
            if ($length <= 4) {
                return str_repeat('•', $length);
            }
            
            // Show first and last character, mask the rest
            $first = substr($decrypted, 0, 1);
            $last = substr($decrypted, -1);
            $masked = str_repeat('•', min($length - 2, 8));
            
            return $first . $masked . $last;
        } catch (\Exception $e) {
            return '••••••••';
        }
    }

    /**
     * Get password strength indicator.
     *
     * @return string
     */
    public function getPasswordStrengthAttribute(): string
    {
        try {
            $password = Crypt::decryptString($this->encrypted_password);
            
            if (empty($password)) return 'none';
            
            $length = strlen($password);
            $hasUpper = preg_match('/[A-Z]/', $password);
            $hasLower = preg_match('/[a-z]/', $password);
            $hasNumber = preg_match('/[0-9]/', $password);
            $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);
            
            $score = 0;
            if ($length >= 8) $score++;
            if ($length >= 12) $score++;
            if ($hasUpper) $score++;
            if ($hasLower) $score++;
            if ($hasNumber) $score++;
            if ($hasSpecial) $score++;
            
            if ($score >= 5) return 'strong';
            if ($score >= 3) return 'medium';
            if ($score >= 1) return 'weak';
            return 'very_weak';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }

    /**
     * Get password strength label.
     *
     * @return string
     */
    public function getPasswordStrengthLabelAttribute(): string
    {
        $labels = [
            'strong' => 'Strong',
            'medium' => 'Medium',
            'weak' => 'Weak',
            'very_weak' => 'Very Weak',
            'none' => 'No Password',
            'unknown' => 'Unknown',
        ];
        
        return $labels[$this->password_strength] ?? 'Unknown';
    }

    /**
     * Get password strength color.
     *
     * @return string
     */
    public function getPasswordStrengthColorAttribute(): string
    {
        $colors = [
            'strong' => 'success',
            'medium' => 'warning',
            'weak' => 'danger',
            'very_weak' => 'danger',
            'none' => 'secondary',
            'unknown' => 'secondary',
        ];
        
        return $colors[$this->password_strength] ?? 'secondary';
    }

    /**
     * Get password strength badge HTML.
     *
     * @return string
     */
    public function getPasswordStrengthBadgeAttribute(): string
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $this->password_strength_color,
            $this->password_strength_label
        );
    }

    // =========================================================================
    // PLATFORM ACCESSORS
    // =========================================================================

    /**
     * Get the platform label with proper formatting.
     *
     * @return string
     */
    public function getPlatformLabelAttribute(): string
    {
        $labels = [
            // Social Media
            'Facebook' => 'Facebook',
            'Instagram' => 'Instagram',
            'Twitter/X' => 'Twitter / X',
            'LinkedIn' => 'LinkedIn',
            'TikTok' => 'TikTok',
            'WhatsApp' => 'WhatsApp',
            'Telegram' => 'Telegram',
            'Other Social Media' => 'Other Social Media',
            
            // Banking & Finance
            'Maybank2u' => 'Maybank2u',
            'CIMB Clicks' => 'CIMB Clicks',
            'Public Bank' => 'Public Bank Online',
            'RHB Bank' => 'RHB Now',
            'Hong Leong Connect' => 'Hong Leong Connect',
            'AmBank' => 'AmOnline',
            'Bank Islam' => 'Bank Islam Online',
            'Bank Rakyat' => 'iRakyat',
            'BSN' => 'myBSN',
            'Other Bank' => 'Other Bank',
            
            // E-Wallets
            'Touch \'n Go eWallet' => 'Touch \'n Go eWallet',
            'Boost' => 'Boost',
            'GrabPay' => 'GrabPay',
            'BigPay' => 'BigPay',
            
            // Email & Cloud
            'Gmail' => 'Gmail',
            'Outlook/Hotmail' => 'Outlook / Hotmail',
            'Yahoo Mail' => 'Yahoo Mail',
            'Google Drive' => 'Google Drive',
            'Dropbox' => 'Dropbox',
            'iCloud' => 'iCloud',
            'OneDrive' => 'OneDrive',
            
            // Government & EPF
            'EPF i-Akaun' => 'EPF i-Akaun',
            'LHDN e-Filing' => 'LHDN e-Filing',
            'MyEG' => 'MyEG',
            'JPJ e-Services' => 'JPJ e-Services',
            'MyGov Portal' => 'MyGov Portal',
            
            // Investment & Trading
            'Rakuten Trade' => 'Rakuten Trade',
            'Mplus' => 'Mplus',
            'FSMOne' => 'FSMOne',
            'Binance' => 'Binance',
            'Luno' => 'Luno',
            'Hata' => 'Hata',
            
            // Other
            'Other Service' => 'Other Service',
        ];
        
        return $labels[$this->platform] ?? $this->platform ?? 'Unknown Platform';
    }

    /**
     * Get the platform category.
     *
     * @return string
     */
    public function getPlatformCategoryAttribute(): string
    {
        $categories = [
            'Social Media' => [
                'Facebook', 'Instagram', 'Twitter/X', 'LinkedIn', 'TikTok', 
                'WhatsApp', 'Telegram', 'Other Social Media',
            ],
            'Banking' => [
                'Maybank2u', 'CIMB Clicks', 'Public Bank', 'RHB Bank', 
                'Hong Leong Connect', 'AmBank', 'Bank Islam', 'Bank Rakyat', 
                'BSN', 'Other Bank',
            ],
            'E-Wallet' => [
                'Touch \'n Go eWallet', 'Boost', 'GrabPay', 'BigPay',
            ],
            'Email' => [
                'Gmail', 'Outlook/Hotmail', 'Yahoo Mail',
            ],
            'Cloud Storage' => [
                'Google Drive', 'Dropbox', 'iCloud', 'OneDrive',
            ],
            'Government' => [
                'EPF i-Akaun', 'LHDN e-Filing', 'MyEG', 'JPJ e-Services', 'MyGov Portal',
            ],
            'Investment' => [
                'Rakuten Trade', 'Mplus', 'FSMOne', 'Binance', 'Luno', 'Hata',
            ],
        ];
        
        foreach ($categories as $category => $platforms) {
            if (in_array($this->platform, $platforms)) {
                return $category;
            }
        }
        
        return 'Other';
    }

    /**
     * Get the platform icon (emoji).
     *
     * @return string
     */
    public function getPlatformIconAttribute(): string
    {
        $icons = [
            // Social Media
            'Facebook' => '📘',
            'Instagram' => '📷',
            'Twitter/X' => '🐦',
            'LinkedIn' => '💼',
            'TikTok' => '🎵',
            'WhatsApp' => '💬',
            'Telegram' => '✈️',
            
            // Banking
            'Maybank2u' => '🏦',
            'CIMB Clicks' => '🏦',
            'Public Bank' => '🏦',
            'RHB Bank' => '🏦',
            'Hong Leong Connect' => '🏦',
            'AmBank' => '🏦',
            'Bank Islam' => '🏦',
            'Bank Rakyat' => '🏦',
            'BSN' => '🏦',
            
            // E-Wallets
            'Touch \'n Go eWallet' => '💰',
            'Boost' => '💰',
            'GrabPay' => '💰',
            'BigPay' => '💳',
            
            // Email
            'Gmail' => '📧',
            'Outlook/Hotmail' => '📧',
            'Yahoo Mail' => '📧',
            
            // Cloud
            'Google Drive' => '☁️',
            'Dropbox' => '📦',
            'iCloud' => '☁️',
            'OneDrive' => '☁️',
            
            // Government
            'EPF i-Akaun' => '🏛️',
            'LHDN e-Filing' => '📄',
            'MyEG' => '🏛️',
            'JPJ e-Services' => '🚗',
            'MyGov Portal' => '🏛️',
            
            // Investment
            'Rakuten Trade' => '📈',
            'Mplus' => '📈',
            'FSMOne' => '📈',
            'Binance' => '₿',
            'Luno' => '₿',
            'Hata' => '₿',
        ];
        
        return $icons[$this->platform] ?? '🔐';
    }

    /**
     * Get the platform category icon.
     *
     * @return string
     */
    public function getPlatformCategoryIconAttribute(): string
    {
        $icons = [
            'Social Media' => '📱',
            'Banking' => '🏦',
            'E-Wallet' => '💳',
            'Email' => '📧',
            'Cloud Storage' => '☁️',
            'Government' => '🏛️',
            'Investment' => '📈',
            'Other' => '🔐',
        ];
        
        return $icons[$this->platform_category] ?? '🔐';
    }

    /**
     * Get the platform category color for badges.
     *
     * @return string
     */
    public function getPlatformCategoryColorAttribute(): string
    {
        $colors = [
            'Social Media' => 'info',
            'Banking' => 'primary',
            'E-Wallet' => 'success',
            'Email' => 'warning',
            'Cloud Storage' => 'secondary',
            'Government' => 'dark',
            'Investment' => 'success',
            'Other' => 'secondary',
        ];
        
        return $colors[$this->platform_category] ?? 'secondary';
    }

    // =========================================================================
    // CATEGORY BOOLEAN ACCESSORS
    // =========================================================================

    /**
     * Check if this is a banking credential.
     *
     * @return bool
     */
    public function getIsBankingAttribute(): bool
    {
        return $this->platform_category === 'Banking';
    }

    /**
     * Check if this is a social media credential.
     *
     * @return bool
     */
    public function getIsSocialMediaAttribute(): bool
    {
        return $this->platform_category === 'Social Media';
    }

    /**
     * Check if this is an email credential.
     *
     * @return bool
     */
    public function getIsEmailAttribute(): bool
    {
        return $this->platform_category === 'Email';
    }

    /**
     * Check if this is a government credential.
     *
     * @return bool
     */
    public function getIsGovernmentAttribute(): bool
    {
        return $this->platform_category === 'Government';
    }

    /**
     * Check if this is an investment credential.
     *
     * @return bool
     */
    public function getIsInvestmentAttribute(): bool
    {
        return $this->platform_category === 'Investment';
    }

    /**
     * Check if this is an e-wallet credential.
     *
     * @return bool
     */
    public function getIsEwalletAttribute(): bool
    {
        return $this->platform_category === 'E-Wallet';
    }

    /**
     * Check if this is a cloud storage credential.
     *
     * @return bool
     */
    public function getIsCloudStorageAttribute(): bool
    {
        return $this->platform_category === 'Cloud Storage';
    }

    /**
     * Check if this is a sensitive credential.
     *
     * @return bool
     */
    public function getIsSensitiveAttribute(): bool
    {
        return $this->is_banking || $this->is_government || $this->is_investment;
    }

    // =========================================================================
    // SECURITY QUESTIONS ACCESSORS
    // =========================================================================

    /**
     * Check if security questions are set.
     *
     * @return bool
     */
    public function getHasSecurityQuestionsAttribute(): bool
    {
        return !empty($this->security_questions) && $this->security_questions !== 'null';
    }

    /**
     * Get the number of security questions.
     *
     * @return int
     */
    public function getSecurityQuestionsCountAttribute(): int
    {
        if (!$this->has_security_questions) {
            return 0;
        }
        
        try {
            $questions = json_decode($this->security_questions, true);
            if (is_array($questions)) {
                return count($questions);
            }
        } catch (\Exception $e) {
            // Not valid JSON
        }
        
        // Count lines if not JSON
        $lines = explode("\n", trim($this->security_questions));
        return count(array_filter($lines, function ($line) {
            return !empty(trim($line));
        }));
    }

    /**
     * Get security questions as array.
     *
     * @return array
     */
    public function getSecurityQuestionsArrayAttribute(): array
    {
        if (!$this->has_security_questions) {
            return [];
        }
        
        try {
            $decoded = json_decode($this->security_questions, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        } catch (\Exception $e) {
            // Not valid JSON
        }
        
        // Return as single item if not JSON
        return [$this->security_questions];
    }

    // =========================================================================
    // NOTES ACCESSORS
    // =========================================================================

    /**
     * Check if notes are set.
     *
     * @return bool
     */
    public function getHasNotesAttribute(): bool
    {
        return !empty($this->notes) && !empty(trim($this->notes));
    }

    /**
     * Get truncated notes for preview.
     *
     * @return string
     */
    public function getNotesPreviewAttribute(): string
    {
        if (!$this->has_notes) {
            return '';
        }
        
        $notes = trim($this->notes);
        if (strlen($notes) <= 50) {
            return $notes;
        }
        
        return substr($notes, 0, 50) . '...';
    }

    // =========================================================================
    // SUMMARY ACCESSOR
    // =========================================================================

    /**
     * Get credential summary.
     *
     * @return array
     */
    public function getCredentialSummaryAttribute(): array
    {
        return [
            'id' => $this->id,
            'platform' => $this->platform,
            'platform_label' => $this->platform_label,
            'platform_category' => $this->platform_category,
            'platform_icon' => $this->platform_icon,
            'username' => $this->username,
            'masked_password' => $this->masked_password,
            'has_security_questions' => $this->has_security_questions,
            'has_notes' => $this->has_notes,
            'is_sensitive' => $this->is_sensitive,
            'created_at' => $this->created_at?->format('d M Y'),
            'updated_at' => $this->updated_at?->format('d M Y'),
        ];
    }

    // =========================================================================
    // MUTATORS
    // =========================================================================

    /**
     * Set the encrypted password attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setEncryptedPasswordAttribute(string $value): void
    {
        if (empty($value)) {
            $this->attributes['encrypted_password'] = '';
            return;
        }
        
        try {
            $this->attributes['encrypted_password'] = Crypt::encryptString($value);
        } catch (\Exception $e) {
            Log::error('Failed to encrypt credential password', [
                'platform' => $this->platform ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            $this->attributes['encrypted_password'] = '';
        }
    }

    /**
     * Set the password directly (for user convenience).
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->setEncryptedPasswordAttribute($value);
    }

    /**
     * Set the username attribute (trim whitespace).
     *
     * @param  string  $value
     * @return void
     */
    public function setUsernameAttribute(string $value): void
    {
        $this->attributes['username'] = trim($value);
    }

    /**
     * Set the platform attribute (trim whitespace).
     *
     * @param  string  $value
     * @return void
     */
    public function setPlatformAttribute(string $value): void
    {
        $this->attributes['platform'] = trim($value);
    }

    // =========================================================================
    // BUSINESS METHODS
    // =========================================================================

    /**
     * Update the password for this credential.
     *
     * @param  string  $newPassword
     * @return bool
     */
    public function updatePassword(string $newPassword): bool
    {
        try {
            $result = $this->update([
                'encrypted_password' => $newPassword,
            ]);

            Log::info('Credential password updated', [
                'credential_id' => $this->id,
                'platform' => $this->platform,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update credential password', [
                'credential_id' => $this->id,
                'platform' => $this->platform,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update security questions.
     *
     * @param  string|array  $questions
     * @return bool
     */
    public function updateSecurityQuestions($questions): bool
    {
        try {
            $data = is_array($questions) ? json_encode($questions) : $questions;

            $result = $this->update([
                'security_questions' => $data,
            ]);

            Log::info('Credential security questions updated', [
                'credential_id' => $this->id,
                'platform' => $this->platform,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update security questions', [
                'credential_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update notes for this credential.
     *
     * @param  string  $notes
     * @return bool
     */
    public function updateNotes(string $notes): bool
    {
        try {
            $result = $this->update([
                'notes' => $notes,
            ]);

            Log::info('Credential notes updated', [
                'credential_id' => $this->id,
                'platform' => $this->platform,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to update credential notes', [
                'credential_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get the password securely (for verified access only).
     *
     * @param  string  $verificationToken
     * @return string|null
     */
    public function getPasswordSecurely(string $verificationToken): ?string
    {
        // This would typically verify against a secure token
        // For now, just return decrypted password
        try {
            return $this->decrypted_password;
        } catch (\Exception $e) {
            Log::error('Secure password access failed', [
                'credential_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Copy credential details to array for export.
     *
     * @return array
     */
    public function toExportArray(): array
    {
        return [
            'platform' => $this->platform,
            'platform_label' => $this->platform_label,
            'platform_category' => $this->platform_category,
            'username' => $this->username,
            'has_security_questions' => $this->has_security_questions,
            'has_notes' => $this->has_notes,
        ];
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope for credentials by platform.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $platform
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope for credentials by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, string $category)
    {
        $categories = [
            'Social Media' => [
                'Facebook', 'Instagram', 'Twitter/X', 'LinkedIn', 'TikTok',
                'WhatsApp', 'Telegram', 'Other Social Media',
            ],
            'Banking' => [
                'Maybank2u', 'CIMB Clicks', 'Public Bank', 'RHB Bank',
                'Hong Leong Connect', 'AmBank', 'Bank Islam', 'Bank Rakyat',
                'BSN', 'Other Bank',
            ],
            'E-Wallet' => [
                'Touch \'n Go eWallet', 'Boost', 'GrabPay', 'BigPay',
            ],
            'Email' => [
                'Gmail', 'Outlook/Hotmail', 'Yahoo Mail',
            ],
            'Cloud Storage' => [
                'Google Drive', 'Dropbox', 'iCloud', 'OneDrive',
            ],
            'Government' => [
                'EPF i-Akaun', 'LHDN e-Filing', 'MyEG', 'JPJ e-Services', 'MyGov Portal',
            ],
            'Investment' => [
                'Rakuten Trade', 'Mplus', 'FSMOne', 'Binance', 'Luno', 'Hata',
            ],
        ];
        
        if (isset($categories[$category])) {
            return $query->whereIn('platform', $categories[$category]);
        }
        
        if ($category === 'Other') {
            $allKnown = array_merge(...array_values($categories));
            return $query->whereNotIn('platform', $allKnown);
        }
        
        return $query;
    }

    /**
     * Scope for banking credentials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBanking($query)
    {
        return $query->byCategory('Banking');
    }

    /**
     * Scope for social media credentials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSocialMedia($query)
    {
        return $query->byCategory('Social Media');
    }

    /**
     * Scope for email credentials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmail($query)
    {
        return $query->byCategory('Email');
    }

    /**
     * Scope for sensitive credentials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSensitive($query)
    {
        $sensitiveCategories = ['Banking', 'Government', 'Investment'];
        $sensitivePlatforms = [];
        
        $allCategories = [
            'Banking' => ['Maybank2u', 'CIMB Clicks', 'Public Bank', 'RHB Bank', 'Hong Leong Connect', 'AmBank', 'Bank Islam', 'Bank Rakyat', 'BSN', 'Other Bank'],
            'Government' => ['EPF i-Akaun', 'LHDN e-Filing', 'MyEG', 'JPJ e-Services', 'MyGov Portal'],
            'Investment' => ['Rakuten Trade', 'Mplus', 'FSMOne', 'Binance', 'Luno', 'Hata'],
        ];
        
        foreach ($sensitiveCategories as $category) {
            if (isset($allCategories[$category])) {
                $sensitivePlatforms = array_merge($sensitivePlatforms, $allCategories[$category]);
            }
        }
        
        return $query->whereIn('platform', $sensitivePlatforms);
    }

    /**
     * Scope for credentials with security questions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasSecurityQuestions($query)
    {
        return $query->whereNotNull('security_questions')
            ->where('security_questions', '!=', '')
            ->where('security_questions', '!=', 'null');
    }

    /**
     * Scope for credentials with notes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasNotes($query)
    {
        return $query->whereNotNull('notes')
            ->where('notes', '!=', '');
    }

    /**
     * Scope for credentials search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('platform', 'LIKE', "%{$search}%")
              ->orWhere('username', 'LIKE', "%{$search}%")
              ->orWhere('notes', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope for recently updated credentials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecentlyUpdated($query, int $days = 30)
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
    }

    // =========================================================================
    // STATIC METHODS
    // =========================================================================

    /**
     * Get all available platforms grouped by category.
     *
     * @return array
     */
    public static function getAvailablePlatforms(): array
    {
        return [
            'Social Media' => [
                'Facebook', 'Instagram', 'Twitter/X', 'LinkedIn', 'TikTok',
                'WhatsApp', 'Telegram', 'Other Social Media',
            ],
            'Banking & Finance' => [
                'Maybank2u', 'CIMB Clicks', 'Public Bank', 'RHB Bank',
                'Hong Leong Connect', 'AmBank', 'Bank Islam', 'Bank Rakyat',
                'BSN', 'Other Bank',
            ],
            'E-Wallets' => [
                'Touch \'n Go eWallet', 'Boost', 'GrabPay', 'BigPay',
            ],
            'Email & Cloud' => [
                'Gmail', 'Outlook/Hotmail', 'Yahoo Mail',
                'Google Drive', 'Dropbox', 'iCloud', 'OneDrive',
            ],
            'Government & EPF' => [
                'EPF i-Akaun', 'LHDN e-Filing', 'MyEG', 'JPJ e-Services', 'MyGov Portal',
            ],
            'Investment & Trading' => [
                'Rakuten Trade', 'Mplus', 'FSMOne', 'Binance', 'Luno', 'Hata',
            ],
            'Other' => [
                'Other Service',
            ],
        ];
    }

    /**
     * Get platform categories.
     *
     * @return array
     */
    public static function getPlatformCategories(): array
    {
        return [
            'Social Media' => 'Social Media',
            'Banking' => 'Banking & Finance',
            'E-Wallet' => 'E-Wallets',
            'Email' => 'Email',
            'Cloud Storage' => 'Cloud Storage',
            'Government' => 'Government',
            'Investment' => 'Investment & Trading',
            'Other' => 'Other',
        ];
    }

    /**
     * Get the count of credentials for a specific estate.
     *
     * @param  int  $estateId
     * @return int
     */
    public static function getCountForEstate(int $estateId): int
    {
        return self::where('estate_pre_registration_id', $estateId)->count();
    }

    /**
     * Get credentials grouped by category for a specific estate.
     *
     * @param  int  $estateId
     * @return array
     */
    public static function getGroupedForEstate(int $estateId): array
    {
        $credentials = self::where('estate_pre_registration_id', $estateId)->get();
        
        $grouped = [];
        foreach ($credentials as $credential) {
            $category = $credential->platform_category;
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $credential;
        }
        
        return $grouped;
    }

    // =========================================================================
    // EVENT HANDLERS
    // =========================================================================

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($credential) {
            // Validate required fields
            if (empty($credential->platform)) {
                Log::warning('Digital credential created without platform name');
            }
            
            if (empty($credential->username)) {
                Log::warning('Digital credential created without username');
            }

            // Log creation
            Log::info('Digital credential being created', [
                'platform' => $credential->platform,
                'estate_id' => $credential->estate_pre_registration_id,
            ]);
        });

        static::updating(function ($credential) {
            // Log password changes
            if ($credential->isDirty('encrypted_password')) {
                Log::info('Digital credential password updated', [
                    'credential_id' => $credential->id,
                    'platform' => $credential->platform,
                ]);
            }

            // Log platform changes
            if ($credential->isDirty('platform')) {
                Log::info('Digital credential platform changed', [
                    'credential_id' => $credential->id,
                    'old_platform' => $credential->getOriginal('platform'),
                    'new_platform' => $credential->platform,
                ]);
            }
        });

        static::deleting(function ($credential) {
            // Log deletion
            Log::info('Digital credential being deleted', [
                'credential_id' => $credential->id,
                'platform' => $credential->platform,
                'estate_id' => $credential->estate_pre_registration_id,
            ]);
        });
    }
}
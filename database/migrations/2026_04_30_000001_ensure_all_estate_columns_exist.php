<?php
// File: database/migrations/2026_04_30_000001_ensure_all_estate_columns_exist.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    protected $table = 'estate_pre_registrations';

    public function up(): void
    {
        if (!Schema::hasTable($this->table)) {
            Log::warning("Table '{$this->table}' does not exist. Skipping.");
            return;
        }

        // Get existing columns
        $existingColumns = $this->getExistingColumns();

        // Define ALL required columns that might be missing
        $requiredColumns = [
            // Admin Approval
            'admin_approved' => function (Blueprint $table) {
                $table->boolean('admin_approved')->default(false)
                    ->comment('Whether the estate has been approved by admin');
            },
            'admin_approved_at' => function (Blueprint $table) {
                $table->timestamp('admin_approved_at')->nullable()
                    ->comment('Timestamp when admin approved the estate');
            },
            'admin_approved_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('admin_approved_by')->nullable()
                    ->comment('User ID of admin who approved');
            },
            'admin_notes' => function (Blueprint $table) {
                $table->text('admin_notes')->nullable()
                    ->comment('Notes from admin regarding this estate');
            },

            // Rejection Fields
            'rejection_reason' => function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()
                    ->comment('Reason for rejection');
            },
            'rejected_at' => function (Blueprint $table) {
                $table->timestamp('rejected_at')->nullable()
                    ->comment('Timestamp when estate was rejected');
            },
            'rejected_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('rejected_by')->nullable()
                    ->comment('User ID of admin who rejected');
            },

            // Notification Request
            'notification_requested' => function (Blueprint $table) {
                $table->boolean('notification_requested')->default(false)
                    ->comment('Whether notification has been requested');
            },
            'notification_requested_at' => function (Blueprint $table) {
                $table->timestamp('notification_requested_at')->nullable()
                    ->comment('Timestamp when notification was requested');
            },
            'notification_sent' => function (Blueprint $table) {
                $table->boolean('notification_sent')->default(false)
                    ->comment('Whether notification emails have been sent');
            },
            'notification_sent_at' => function (Blueprint $table) {
                $table->timestamp('notification_sent_at')->nullable()
                    ->comment('Timestamp when notification emails were sent');
            },

            // Data Locking
            'data_locked' => function (Blueprint $table) {
                $table->boolean('data_locked')->default(false)
                    ->comment('Whether estate data is locked after admin approval');
            },
            'data_locked_at' => function (Blueprint $table) {
                $table->timestamp('data_locked_at')->nullable()
                    ->comment('Timestamp when data was locked');
            },
            'data_locked_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('data_locked_by')->nullable()
                    ->comment('User ID who locked the data');
            },
            'data_version' => function (Blueprint $table) {
                $table->integer('data_version')->default(1)
                    ->comment('Version number for data integrity tracking');
            },
            'data_hash' => function (Blueprint $table) {
                $table->string('data_hash')->nullable()
                    ->comment('SHA-256 hash of estate data for integrity verification');
            },

            // PDF Distribution
            'distribution_pdf_path' => function (Blueprint $table) {
                $table->string('distribution_pdf_path')->nullable()
                    ->comment('Path to pre-generated distribution PDF');
            },
            'distribution_pdf_generated_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_generated_at')->nullable()
                    ->comment('Timestamp when PDF was generated');
            },
            'distribution_pdf_hash' => function (Blueprint $table) {
                $table->string('distribution_pdf_hash')->nullable()
                    ->comment('Hash of the generated PDF');
            },
            'distribution_pdf_status' => function (Blueprint $table) {
                $table->string('distribution_pdf_status')->default('pending')
                    ->comment('Status: pending, locked, released');
            },
            'distribution_pdf_version' => function (Blueprint $table) {
                $table->integer('distribution_pdf_version')->default(1)
                    ->comment('Version of the generated PDF');
            },
            'distribution_pdf_locked_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_locked_at')->nullable()
                    ->comment('Timestamp when PDF was locked');
            },
            'distribution_pdf_locked_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('distribution_pdf_locked_by')->nullable()
                    ->comment('User ID who locked the PDF');
            },
            'distribution_pdf_released_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_released_at')->nullable()
                    ->comment('Timestamp when PDF was released');
            },
            'distribution_pdf_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('distribution_pdf_released_by')->nullable()
                    ->comment('User ID who released the PDF');
            },

            // Video Processing
            'will_video_processed_path' => function (Blueprint $table) {
                $table->string('will_video_processed_path')->nullable()
                    ->comment('Path to processed video file');
            },
            'will_video_processed_at' => function (Blueprint $table) {
                $table->timestamp('will_video_processed_at')->nullable()
                    ->comment('Timestamp when video was processed');
            },
            'will_video_processed_status' => function (Blueprint $table) {
                $table->string('will_video_processed_status')->default('pending')
                    ->comment('Status: pending, locked, released');
            },
            'will_video_processed_hash' => function (Blueprint $table) {
                $table->string('will_video_processed_hash')->nullable()
                    ->comment('Hash of processed video');
            },
            'will_video_released_at' => function (Blueprint $table) {
                $table->timestamp('will_video_released_at')->nullable()
                    ->comment('Timestamp when video was released');
            },
            'will_video_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('will_video_released_by')->nullable()
                    ->comment('User ID who released the video');
            },

            // Document Release
            'documents_released' => function (Blueprint $table) {
                $table->boolean('documents_released')->default(false)
                    ->comment('Whether documents have been released to beneficiaries');
            },
            'documents_released_at' => function (Blueprint $table) {
                $table->timestamp('documents_released_at')->nullable()
                    ->comment('Timestamp when documents were released');
            },
            'documents_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('documents_released_by')->nullable()
                    ->comment('User ID who released documents');
            },

            // Access Tokens for Beneficiaries
            'pdf_access_token' => function (Blueprint $table) {
                $table->string('pdf_access_token')->nullable()
                    ->comment('Access token for PDF viewing');
            },
            'pdf_access_expires_at' => function (Blueprint $table) {
                $table->timestamp('pdf_access_expires_at')->nullable()
                    ->comment('Expiration timestamp for PDF access');
            },
            'video_access_token' => function (Blueprint $table) {
                $table->string('video_access_token')->nullable()
                    ->comment('Access token for video viewing');
            },
            'video_access_expires_at' => function (Blueprint $table) {
                $table->timestamp('video_access_expires_at')->nullable()
                    ->comment('Expiration timestamp for video access');
            },

            // Trustee Fields (if missing)
            'trustee_name' => function (Blueprint $table) {
                $table->string('trustee_name')->nullable()->after('address')
                    ->comment('Full name of the appointed trustee/wasi');
            },
            'trustee_nric' => function (Blueprint $table) {
                $table->string('trustee_nric', 20)->nullable()->after('trustee_name')
                    ->comment('NRIC/Passport of the trustee');
            },
            'trustee_phone' => function (Blueprint $table) {
                $table->string('trustee_phone', 20)->nullable()->after('trustee_nric')
                    ->comment('Contact phone of the trustee');
            },
            'trustee_email' => function (Blueprint $table) {
                $table->string('trustee_email')->nullable()->after('trustee_phone')
                    ->comment('Email of the trustee for notifications');
            },
            'trustee_relationship' => function (Blueprint $table) {
                $table->string('trustee_relationship')->nullable()->after('trustee_email')
                    ->comment('Relationship of trustee to deceased');
            },
            'trustee_address' => function (Blueprint $table) {
                $table->text('trustee_address')->nullable()->after('trustee_relationship')
                    ->comment('Full residential address of the trustee');
            },

            // Will Video Fields (if missing)
            'will_video_url' => function (Blueprint $table) {
                $table->string('will_video_url')->nullable()->after('executed_at')
                    ->comment('URL to the will video');
            },
            'will_video_type' => function (Blueprint $table) {
                $table->string('will_video_type')->nullable()->after('will_video_url')
                    ->comment('Type of will video: upload or youtube');
            },
            'will_video_thumbnail' => function (Blueprint $table) {
                $table->string('will_video_thumbnail')->nullable()->after('will_video_type')
                    ->comment('Thumbnail for the will video');
            },
            'will_text_content' => function (Blueprint $table) {
                $table->text('will_text_content')->nullable()->after('will_video_thumbnail')
                    ->comment('Written will content');
            },

            // Instructions (if missing)
            'wasiyyah_instructions' => function (Blueprint $table) {
                $table->text('wasiyyah_instructions')->nullable()->after('will_text_content')
                    ->comment('General instructions for wasiyyah/will execution');
            },
            'special_instructions' => function (Blueprint $table) {
                $table->text('special_instructions')->nullable()->after('wasiyyah_instructions')
                    ->comment('Special instructions or conditions');
            },
            'funeral_instructions' => function (Blueprint $table) {
                $table->text('funeral_instructions')->nullable()->after('special_instructions')
                    ->comment('Instructions for funeral arrangements');
            },

            // Emergency Contact (if missing)
            'emergency_contact_name' => function (Blueprint $table) {
                $table->string('emergency_contact_name')->nullable()->after('funeral_instructions')
                    ->comment('Name of emergency contact person');
            },
            'emergency_contact_phone' => function (Blueprint $table) {
                $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name')
                    ->comment('Phone number of emergency contact');
            },
            'emergency_contact_relationship' => function (Blueprint $table) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone')
                    ->comment('Relationship of emergency contact to deceased');
            },

            // Estate Valuation (if missing)
            'estate_valuation_date' => function (Blueprint $table) {
                $table->date('estate_valuation_date')->nullable()->after('emergency_contact_relationship')
                    ->comment('Date when estate assets were last valued');
            },
            'estate_valuation_notes' => function (Blueprint $table) {
                $table->text('estate_valuation_notes')->nullable()->after('estate_valuation_date')
                    ->comment('Notes about estate valuation');
            },

            // Lawyer Information (if missing)
            'lawyer_name' => function (Blueprint $table) {
                $table->string('lawyer_name')->nullable()->after('estate_valuation_notes')
                    ->comment('Name of the lawyer handling the estate');
            },
            'lawyer_contact' => function (Blueprint $table) {
                $table->string('lawyer_contact')->nullable()->after('lawyer_name')
                    ->comment('Contact information for the lawyer');
            },
            'lawyer_firm' => function (Blueprint $table) {
                $table->string('lawyer_firm')->nullable()->after('lawyer_contact')
                    ->comment('Law firm name');
            },

            // Witness Information (if missing)
            'witness_name' => function (Blueprint $table) {
                $table->string('witness_name')->nullable()->after('lawyer_firm')
                    ->comment('Name of the will witness');
            },
            'witness_nric' => function (Blueprint $table) {
                $table->string('witness_nric', 20)->nullable()->after('witness_name')
                    ->comment('NRIC/Passport of the will witness');
            },
            'witness_phone' => function (Blueprint $table) {
                $table->string('witness_phone', 20)->nullable()->after('witness_nric')
                    ->comment('Phone number of the will witness');
            },

            // Alternate Trustee (if missing)
            'alternate_trustee_name' => function (Blueprint $table) {
                $table->string('alternate_trustee_name')->nullable()->after('witness_phone')
                    ->comment('Name of alternate trustee');
            },
            'alternate_trustee_nric' => function (Blueprint $table) {
                $table->string('alternate_trustee_nric', 20)->nullable()->after('alternate_trustee_name')
                    ->comment('NRIC/Passport of alternate trustee');
            },
            'alternate_trustee_phone' => function (Blueprint $table) {
                $table->string('alternate_trustee_phone', 20)->nullable()->after('alternate_trustee_nric')
                    ->comment('Phone of alternate trustee');
            },
            'alternate_trustee_email' => function (Blueprint $table) {
                $table->string('alternate_trustee_email')->nullable()->after('alternate_trustee_phone')
                    ->comment('Email of alternate trustee');
            },
            'alternate_trustee_relationship' => function (Blueprint $table) {
                $table->string('alternate_trustee_relationship')->nullable()->after('alternate_trustee_email')
                    ->comment('Relationship of alternate trustee to deceased');
            },

            // Shariah Compliance (if missing)
            'is_shariah_compliant' => function (Blueprint $table) {
                $table->boolean('is_shariah_compliant')->default(true)->after('alternate_trustee_relationship')
                    ->comment('Flag indicating if estate follows Shariah compliance');
            },
            'shariah_advisor_name' => function (Blueprint $table) {
                $table->string('shariah_advisor_name')->nullable()->after('is_shariah_compliant')
                    ->comment('Name of Shariah advisor consulted');
            },
            'shariah_advisor_contact' => function (Blueprint $table) {
                $table->string('shariah_advisor_contact')->nullable()->after('shariah_advisor_name')
                    ->comment('Contact for Shariah advisor');
            },

            // Notes and Review (if missing)
            'estate_notes' => function (Blueprint $table) {
                $table->text('estate_notes')->nullable()->after('shariah_advisor_contact')
                    ->comment('General notes about the estate');
            },
            'reviewed_at' => function (Blueprint $table) {
                $table->timestamp('reviewed_at')->nullable()->after('estate_notes')
                    ->comment('Timestamp when estate was last reviewed');
            },
            'reviewed_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at')
                    ->comment('User ID of person who last reviewed the estate');
            },

            // Death Certificate (if missing)
            'death_certificate_url' => function (Blueprint $table) {
                $table->string('death_certificate_url')->nullable()->after('reviewed_by')
                    ->comment('URL to uploaded death certificate');
            },
            'ocr_extracted_data' => function (Blueprint $table) {
                $table->text('ocr_extracted_data')->nullable()->after('death_certificate_url')
                    ->comment('OCR extracted data from death certificate');
            },
            'death_certificate_uploaded_at' => function (Blueprint $table) {
                $table->timestamp('death_certificate_uploaded_at')->nullable()->after('ocr_extracted_data')
                    ->comment('Timestamp when death certificate was uploaded');
            },

            // Metadata
            'metadata' => function (Blueprint $table) {
                $table->json('metadata')->nullable()->after('death_certificate_uploaded_at')
                    ->comment('Additional metadata for extensibility');
            },
        ];

        // Find missing columns
        $missingColumns = [];
        foreach ($requiredColumns as $columnName => $callback) {
            if (!in_array($columnName, $existingColumns)) {
                $missingColumns[$columnName] = $callback;
            }
        }

        // Add missing columns
        if (!empty($missingColumns)) {
            Schema::table($this->table, function (Blueprint $table) use ($missingColumns) {
                foreach ($missingColumns as $columnName => $callback) {
                    $callback($table);
                }
            });

            Log::info("Added " . count($missingColumns) . " missing columns to '{$this->table}' table.", [
                'columns' => array_keys($missingColumns),
            ]);

            // Add indexes for newly added columns
            $this->addIndexesForNewColumns(array_keys($missingColumns));
        } else {
            Log::info("Table '{$this->table}' already has all required columns.");
        }

        // Ensure status enum is correct
        $this->fixStatusEnum();
    }

    /**
     * Add indexes for newly added columns.
     */
    private function addIndexesForNewColumns(array $newColumns): void
    {
        $indexMap = [
            'admin_approved' => ['admin_approved'],
            'data_locked' => ['data_locked'],
            'documents_released' => ['documents_released'],
            'notification_requested' => ['notification_requested'],
            'notification_sent' => ['notification_sent'],
            'distribution_pdf_status' => ['distribution_pdf_status'],
            'will_video_processed_status' => ['will_video_processed_status'],
            'trustee_email' => ['trustee_email'],
            'trustee_nric' => ['trustee_nric'],
            'deceased_nric' => ['deceased_nric'],
            'status' => ['status'],
            'unique_id' => ['unique_id'],
            'is_shariah_compliant' => ['is_shariah_compliant'],
            'reviewed_at' => ['reviewed_at'],
            'lawyer_name' => ['lawyer_name'],
        ];

        Schema::table($this->table, function (Blueprint $table) use ($newColumns, $indexMap) {
            foreach ($newColumns as $column) {
                if (isset($indexMap[$column]) && Schema::hasColumn($this->table, $column)) {
                    $indexName = 'estate_' . $column . '_idx';
                    if (!$this->hasIndex($indexName)) {
                        $table->index($column, $indexName);
                    }
                }
            }
        });
    }

    /**
     * Fix the status column to support all required values.
     */
    private function fixStatusEnum(): void
    {
        if (Schema::hasColumn($this->table, 'status')) {
            try {
                $connection = Schema::getConnection();
                $driver = $connection->getDriverName();

                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$this->table} MODIFY COLUMN status VARCHAR(50) DEFAULT 'draft'");
                    Log::info("Modified status column in '{$this->table}'.");
                }
            } catch (\Exception $e) {
                Log::warning("Failed to modify status column: " . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructive down migration - we don't want to accidentally drop columns
        Log::info("Migration rollback skipped for safety.");
    }

    /**
     * Get existing columns.
     */
    private function getExistingColumns(): array
    {
        try {
            return Schema::getColumnListing($this->table);
        } catch (\Exception $e) {
            Log::warning("Failed to get columns: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if an index exists.
     */
    private function hasIndex(string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $driver = $connection->getDriverName();

            if ($driver === 'mysql') {
                $databaseName = $connection->getDatabaseName();
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM INFORMATION_SCHEMA.STATISTICS 
                     WHERE TABLE_SCHEMA = ? 
                     AND TABLE_NAME = ? 
                     AND INDEX_NAME = ?",
                    [$databaseName, $this->table, $indexName]
                );
                return !empty($result) && $result[0]->count > 0;
            }

            if ($driver === 'sqlite') {
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM sqlite_master 
                     WHERE type = 'index' 
                     AND tbl_name = ? 
                     AND name = ?",
                    [$this->table, $indexName]
                );
                return !empty($result) && $result[0]->count > 0;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
};
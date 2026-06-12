<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'instant_estate_sessions';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if the table exists
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration: Table '{$this->table}' does not exist. Creating it with all columns...");
            $this->createFullTable();
            return;
        }

        Log::info("Migration: Adding missing columns to '{$this->table}' table...");

        // Get existing columns
        $existingColumns = $this->getExistingColumns();

        // Define all columns that need to be added (with their callbacks)
        $columnsToAdd = $this->getColumnsToAdd($existingColumns);

        // Add missing columns
        if (!empty($columnsToAdd)) {
            Schema::table($this->table, function (Blueprint $table) use ($columnsToAdd) {
                foreach ($columnsToAdd as $columnName => $callback) {
                    $callback($table);
                    Log::info("Migration: Added column '{$columnName}' to '{$this->table}'.");
                }
            });
        } else {
            Log::info("Migration: Table '{$this->table}' already has all required columns.");
        }

        // Add missing indexes
        $this->addMissingIndexes();

        // Modify existing columns if needed
        $this->modifyExistingColumns();

        Log::info("Migration: Successfully updated '{$this->table}' table with all required columns and indexes.");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the columns we added (to maintain data integrity)
        if (Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {
                $columnsToDrop = [
                    'matched_record_id',
                    'matched_record_type',
                    'report_data',
                    'report_generated_at',
                    'recipient_email',
                    'email_sent_at',
                    'quality_issues',
                    'processing_attempts',
                    'last_processing_attempt_at',
                ];

                foreach ($columnsToDrop as $column) {
                    if (Schema::hasColumn($this->table, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    // =========================================================================
    // CREATE FULL TABLE IF NOT EXISTS
    // =========================================================================

    /**
     * Create the full table with all columns.
     */
    private function createFullTable(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            // Primary key
            $table->id();
            $table->string('session_id', 36)->unique()->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // File information
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_mime')->nullable();
            
            // Status tracking
            $table->string('status')->default('uploaded');
            
            // Quality check results
            $table->boolean('quality_check_passed')->default(false);
            $table->json('quality_check_details')->nullable();
            $table->json('quality_issues')->nullable();
            
            // OCR extracted data
            $table->json('extracted_data')->nullable();
            $table->integer('ocr_confidence')->default(0);
            $table->json('missing_fields')->nullable();
            
            // Deceased information (denormalized for quick access)
            $table->string('deceased_name')->nullable();
            $table->string('deceased_nric')->nullable();
            $table->date('death_date')->nullable();
            $table->string('death_place')->nullable();
            
            // Data confirmation
            $table->timestamp('data_confirmed_at')->nullable();
            $table->foreignId('data_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // CAPTCHA verification
            $table->timestamp('captcha_verified_at')->nullable();
            $table->foreignId('captcha_verified_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Notification request
            $table->boolean('notification_requested')->default(false);
            $table->timestamp('notification_requested_at')->nullable();
            $table->foreignId('notification_request_id')->nullable();
            
            // ===== NEW COLUMNS FOR MATCHING AND REPORTING =====
            // Database matching results
            $table->string('matched_record_id')->nullable();
            $table->string('matched_record_type')->nullable();
            
            // Report data
            $table->json('report_data')->nullable();
            $table->timestamp('report_generated_at')->nullable();
            
            // Email recipient
            $table->string('recipient_email')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            
            // Processing tracking
            $table->integer('processing_attempts')->default(0);
            $table->timestamp('last_processing_attempt_at')->nullable();
            
            // Associated calculation
            $table->foreignId('calculation_id')->nullable()->constrained()->onDelete('set null');
            
            // Error tracking
            $table->text('error_message')->nullable();
            $table->integer('processing_time_ms')->nullable();
            
            // Session expiry
            $table->timestamp('expires_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // ===== INDEXES =====
            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index('created_at', 'idx_created_at');
            $table->index('deceased_nric', 'idx_deceased_nric');
            $table->index('deceased_name', 'idx_deceased_name');
            $table->index('notification_requested', 'idx_notification_requested');
            $table->index('status', 'idx_status');
            $table->index('expires_at', 'idx_expires_at');
            $table->index('matched_record_id', 'idx_matched_record_id');
            $table->index('matched_record_type', 'idx_matched_record_type');
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index(['user_id', 'created_at'], 'idx_user_created');
        });

        Log::info("Migration: Successfully created '{$this->table}' table with all columns.");
    }

    // =========================================================================
    // GET COLUMNS TO ADD
    // =========================================================================

    /**
     * Get the list of columns that need to be added to the existing table.
     *
     * @param array $existingColumns
     * @return array
     */
    private function getColumnsToAdd(array $existingColumns): array
    {
        $columnsToAdd = [];

        // Define all required columns with their callbacks (in order of addition)
        $allColumns = [
            // Matching columns
            'matched_record_id' => function (Blueprint $table) {
                $table->string('matched_record_id')->nullable()->after('notification_request_id')
                    ->comment('ID of the matched record from estate_plan or calculation table');
            },
            'matched_record_type' => function (Blueprint $table) {
                $table->string('matched_record_type')->nullable()->after('matched_record_id')
                    ->comment('Type of matched record: estate_plan, calculation');
            },

            // Report columns
            'report_data' => function (Blueprint $table) {
                $table->json('report_data')->nullable()->after('matched_record_type')
                    ->comment('Generated report data for inheritance distribution');
            },
            'report_generated_at' => function (Blueprint $table) {
                $table->timestamp('report_generated_at')->nullable()->after('report_data')
                    ->comment('Timestamp when the report was generated');
            },

            // Email recipient
            'recipient_email' => function (Blueprint $table) {
                $table->string('recipient_email')->nullable()->after('report_generated_at')
                    ->comment('Email address of the report recipient');
            },
            'email_sent_at' => function (Blueprint $table) {
                $table->timestamp('email_sent_at')->nullable()->after('recipient_email')
                    ->comment('Timestamp when the email was sent');
            },

            // Quality issues
            'quality_issues' => function (Blueprint $table) {
                $table->json('quality_issues')->nullable()->after('quality_check_details')
                    ->comment('List of quality issues detected during upload');
            },

            // Processing tracking
            'processing_attempts' => function (Blueprint $table) {
                $table->integer('processing_attempts')->default(0)->after('processing_time_ms')
                    ->comment('Number of processing attempts made');
            },
            'last_processing_attempt_at' => function (Blueprint $table) {
                $table->timestamp('last_processing_attempt_at')->nullable()->after('processing_attempts')
                    ->comment('Timestamp of the last processing attempt');
            },
        ];

        // Find which columns are missing
        foreach ($allColumns as $columnName => $callback) {
            if (!in_array($columnName, $existingColumns)) {
                $columnsToAdd[$columnName] = $callback;
            }
        }

        return $columnsToAdd;
    }

    // =========================================================================
    // ADD MISSING INDEXES
    // =========================================================================

    /**
     * Add missing indexes to the table.
     */
    private function addMissingIndexes(): void
    {
        $requiredIndexes = [
            'idx_matched_record_id' => ['matched_record_id'],
            'idx_matched_record_type' => ['matched_record_type'],
            'idx_status_created' => ['status', 'created_at'],
            'idx_user_created' => ['user_id', 'created_at'],
        ];

        $addedCount = 0;

        foreach ($requiredIndexes as $indexName => $columns) {
            if (!$this->hasIndex($indexName)) {
                // Check if all columns exist before creating index
                $allColumnsExist = true;
                foreach ($columns as $column) {
                    if (!Schema::hasColumn($this->table, $column)) {
                        $allColumnsExist = false;
                        Log::warning("Migration: Cannot create index '{$indexName}' - column '{$column}' does not exist.");
                        break;
                    }
                }

                if ($allColumnsExist) {
                    try {
                        Schema::table($this->table, function (Blueprint $table) use ($columns, $indexName) {
                            if (count($columns) === 1) {
                                $table->index($columns[0], $indexName);
                            } else {
                                $table->index($columns, $indexName);
                            }
                        });
                        $addedCount++;
                        Log::info("Migration: Added index '{$indexName}' to '{$this->table}'.");
                    } catch (\Exception $e) {
                        Log::warning("Migration: Failed to add index '{$indexName}': " . $e->getMessage());
                    }
                }
            }
        }

        // Also add indexes from original migration if missing
        $originalIndexes = [
            'instant_estate_sessions_user_id_status_index' => ['user_id', 'status'],
            'instant_estate_sessions_notification_requested_index' => ['notification_requested'],
        ];

        foreach ($originalIndexes as $indexName => $columns) {
            if (!$this->hasIndex($indexName)) {
                $allColumnsExist = true;
                foreach ($columns as $column) {
                    if (!Schema::hasColumn($this->table, $column)) {
                        $allColumnsExist = false;
                        break;
                    }
                }

                if ($allColumnsExist) {
                    try {
                        Schema::table($this->table, function (Blueprint $table) use ($columns, $indexName) {
                            if (count($columns) === 1) {
                                $table->index($columns[0], $indexName);
                            } else {
                                $table->index($columns, $indexName);
                            }
                        });
                        $addedCount++;
                        Log::info("Migration: Added index '{$indexName}' to '{$this->table}'.");
                    } catch (\Exception $e) {
                        Log::warning("Migration: Failed to add index '{$indexName}': " . $e->getMessage());
                    }
                }
            }
        }

        if ($addedCount === 0) {
            Log::info("Migration: All required indexes already exist on '{$this->table}'.");
        } else {
            Log::info("Migration: Added {$addedCount} new indexes to '{$this->table}'.");
        }
    }

    // =========================================================================
    // MODIFY EXISTING COLUMNS
    // =========================================================================

    /**
     * Modify existing columns to ensure proper data types and defaults.
     */
    private function modifyExistingColumns(): void
    {
        $driver = DB::connection()->getDriverName();

        // Modify status column to have proper values
        if (Schema::hasColumn($this->table, 'status')) {
            try {
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$this->table} MODIFY status VARCHAR(50) DEFAULT 'uploaded'");
                } elseif ($driver === 'pgsql') {
                    DB::statement("ALTER TABLE {$this->table} ALTER COLUMN status TYPE VARCHAR(50), ALTER COLUMN status SET DEFAULT 'uploaded'");
                } elseif ($driver === 'sqlite') {
                    // SQLite has limited ALTER TABLE support
                    DB::statement("UPDATE {$this->table} SET status = 'uploaded' WHERE status IS NULL");
                }
                
                // Update any old status values to new ones
                DB::table($this->table)
                    ->where('status', 'processing')
                    ->update(['status' => 'processing_ocr']);
                
                DB::table($this->table)
                    ->where('status', 'complete')
                    ->update(['status' => 'completed']);
                
                DB::table($this->table)
                    ->where('status', 'error')
                    ->update(['status' => 'failed']);
                
                Log::info("Migration: Modified 'status' column in '{$this->table}'.");
            } catch (\Exception $e) {
                Log::warning("Migration: Failed to modify 'status' column: " . $e->getMessage());
            }
        }

        // Ensure quality_check_details is JSON
        if (Schema::hasColumn($this->table, 'quality_check_details')) {
            try {
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$this->table} MODIFY quality_check_details JSON");
                }
                Log::info("Migration: Modified 'quality_check_details' column to JSON.");
            } catch (\Exception $e) {
                Log::warning("Migration: Failed to modify 'quality_check_details' column: " . $e->getMessage());
            }
        }

        // Ensure extracted_data is JSON
        if (Schema::hasColumn($this->table, 'extracted_data')) {
            try {
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$this->table} MODIFY extracted_data JSON");
                }
                Log::info("Migration: Modified 'extracted_data' column to JSON.");
            } catch (\Exception $e) {
                Log::warning("Migration: Failed to modify 'extracted_data' column: " . $e->getMessage());
            }
        }

        // Ensure missing_fields is JSON
        if (Schema::hasColumn($this->table, 'missing_fields')) {
            try {
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$this->table} MODIFY missing_fields JSON");
                }
                Log::info("Migration: Modified 'missing_fields' column to JSON.");
            } catch (\Exception $e) {
                Log::warning("Migration: Failed to modify 'missing_fields' column: " . $e->getMessage());
            }
        }

        // Set default values for new columns if they exist
        if (Schema::hasColumn($this->table, 'processing_attempts')) {
            DB::table($this->table)
                ->whereNull('processing_attempts')
                ->update(['processing_attempts' => 0]);
        }
    }

    // =========================================================================
    // UTILITY METHODS
    // =========================================================================

    /**
     * Get a list of all column names currently in the table.
     *
     * @return array
     */
    private function getExistingColumns(): array
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();

            if ($driver === 'mysql') {
                $databaseName = $connection->getDatabaseName();
                $results = DB::select(
                    "SELECT COLUMN_NAME 
                     FROM INFORMATION_SCHEMA.COLUMNS 
                     WHERE TABLE_SCHEMA = ? 
                     AND TABLE_NAME = ?",
                    [$databaseName, $this->table]
                );

                return array_map(function ($row) {
                    return $row->COLUMN_NAME;
                }, $results);
            } elseif ($driver === 'pgsql') {
                $results = DB::select(
                    "SELECT column_name 
                     FROM information_schema.columns 
                     WHERE table_name = ?",
                    [$this->table]
                );

                return array_map(function ($row) {
                    return $row->column_name;
                }, $results);
            } elseif ($driver === 'sqlite') {
                $results = DB::select("PRAGMA table_info({$this->table})");
                return array_map(function ($row) {
                    return $row->name;
                }, $results);
            }

            // Fallback: Get columns from Schema builder
            return Schema::getColumnListing($this->table);

        } catch (\Exception $e) {
            Log::warning("Migration: Failed to get existing columns for table '{$this->table}'.", [
                'error' => $e->getMessage(),
            ]);

            try {
                return Schema::getColumnListing($this->table);
            } catch (\Exception $e2) {
                return [];
            }
        }
    }

    /**
     * Check if an index exists on the specified table.
     *
     * @param string $indexName
     * @return bool
     */
    private function hasIndex(string $indexName): bool
    {
        try {
            $connection = DB::connection();
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
            } elseif ($driver === 'pgsql') {
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM pg_indexes 
                     WHERE tablename = ? 
                     AND indexname = ?",
                    [$this->table, $indexName]
                );

                return !empty($result) && $result[0]->count > 0;
            } elseif ($driver === 'sqlite') {
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
            Log::warning("Migration: Failed to check index existence: {$indexName}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
};
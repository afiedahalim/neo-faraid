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
    protected $table = 'notification_requests';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if table already exists
        if (Schema::hasTable($this->table)) {
            Log::info("Migration: Table '{$this->table}' already exists. Adding any missing columns...");

            // Add any missing columns
            $this->addAllMissingColumns();

            // Add any missing indexes
            $this->addAllMissingIndexes();

            // Modify status column if needed
            $this->modifyStatusColumn();

            Log::info("Migration: All missing columns and indexes added to '{$this->table}' table.");

            return;
        }

        Log::info("Migration: Creating '{$this->table}' table with ALL columns.");

        Schema::create($this->table, function (Blueprint $table) {
            // =================================================================
            // PRIMARY KEY
            // =================================================================
            $table->id();

            // =================================================================
            // SESSION IDENTIFIERS
            // =================================================================
            $table->string('session_id')
                ->nullable()
                ->comment('Session identifier from instant estate');

            // =================================================================
            // FOREIGN KEYS
            // =================================================================
            $table->foreignId('instant_estate_session_id')
                ->nullable()
                ->constrained('instant_estate_sessions')
                ->onDelete('cascade')
                ->comment('Reference to the instant estate session');

            $table->foreignId('estate_pre_registration_id')
                ->nullable()
                ->constrained('estate_pre_registrations')
                ->onDelete('cascade')
                ->comment('Reference to the estate pre-registration');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade')
                ->comment('User who requested the notification');

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('User who made the request (may differ from user_id)');

            // =================================================================
            // RECIPIENT INFORMATION
            // =================================================================
            $table->string('recipient_email')
                ->nullable()
                ->comment('Email address of the notification recipient');

            $table->string('recipient_name')
                ->nullable()
                ->comment('Name of the notification recipient');

            // =================================================================
            // REQUEST STATUS
            // =================================================================
            $table->string('status')
                ->default('pending_admin_approval')
                ->comment('Status: pending_admin_approval, pending, approved, rejected, sent');

            // =================================================================
            // SECURITY TOKEN
            // =================================================================
            $table->string('access_token', 64)
                ->nullable()
                ->comment('Secure access token for beneficiary viewing');

            // =================================================================
            // DECEASED INFORMATION
            // =================================================================
            $table->string('deceased_name')
                ->nullable()
                ->comment('Full name of the deceased person');

            $table->string('deceased_nric')
                ->nullable()
                ->comment('NRIC/Passport number of the deceased');

            $table->date('death_date')
                ->nullable()
                ->comment('Date of death from death certificate');

            $table->string('death_place')
                ->nullable()
                ->comment('Place of death from death certificate');

            // =================================================================
            // TIMELINE
            // =================================================================
            $table->timestamp('requested_at')
                ->nullable()
                ->comment('Timestamp when notification was requested');

            $table->timestamp('approved_at')
                ->nullable()
                ->comment('Timestamp when admin approved the request');

            $table->timestamp('rejected_at')
                ->nullable()
                ->comment('Timestamp when admin rejected the request');

            $table->timestamp('sent_at')
                ->nullable()
                ->comment('Timestamp when notification was sent');

            // =================================================================
            // APPROVAL/REJECTION TRACKING
            // =================================================================
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Admin who approved the request');

            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Admin who rejected the request');

            // =================================================================
            // ADMIN NOTES
            // =================================================================
            $table->text('admin_notes')
                ->nullable()
                ->comment('Notes from admin regarding this request');

            // =================================================================
            // NOTIFICATION SENDING STATUS
            // =================================================================
            $table->boolean('notification_sent')
                ->default(false)
                ->comment('Whether notification emails have been sent');

            $table->timestamp('notification_sent_at')
                ->nullable()
                ->comment('Timestamp when notification emails were sent');

            // =================================================================
            // EMAIL TRACKING
            // =================================================================
            $table->boolean('emails_sent')
                ->default(false)
                ->comment('Whether emails were actually dispatched');

            $table->string('email_status')
                ->nullable()
                ->comment('Status of email delivery: sent, partial, failed');

            $table->json('email_results')
                ->nullable()
                ->comment('Detailed results of email sending');

            $table->integer('recipients_count')
                ->default(0)
                ->comment('Number of recipients notified');

            $table->json('recipients_list')
                ->nullable()
                ->comment('List of recipients with their details');

            // =================================================================
            // VERIFICATION & REJECTION DETAILS
            // =================================================================
            $table->json('verification_result')
                ->nullable()
                ->comment('Result of death certificate verification');

            $table->text('rejection_reason')
                ->nullable()
                ->comment('Reason for rejection if applicable');

            // =================================================================
            // METADATA
            // =================================================================
            $table->json('request_metadata')
                ->nullable()
                ->comment('Metadata about the notification request');

            $table->json('metadata')
                ->nullable()
                ->comment('Additional metadata for extensibility');

            // =================================================================
            // TIMESTAMPS
            // =================================================================
            $table->timestamps();

            // =================================================================
            // INDEXES
            // =================================================================
            $table->index('session_id', 'nr_session_id_idx');
            $table->index('status', 'nr_status_idx');
            $table->index('deceased_nric', 'nr_deceased_nric_idx');
            $table->index('estate_pre_registration_id', 'nr_estate_id_idx');
            $table->index('instant_estate_session_id', 'nr_session_fk_idx');
            $table->index('user_id', 'nr_user_id_idx');
            $table->index('requested_by', 'nr_requested_by_idx');
            $table->index(['status', 'created_at'], 'nr_status_created_idx');
        });

        Log::info("Migration: Successfully created '{$this->table}' table with ALL columns and indexes.");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Check if table exists before dropping
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration rollback: Table '{$this->table}' does not exist. Skipping.");
            return;
        }

        Log::info("Migration rollback: Dropping '{$this->table}' table.");

        Schema::dropIfExists($this->table);

        Log::info("Migration rollback: Successfully dropped '{$this->table}' table.");
    }

    // =========================================================================
    // ADD ALL MISSING COLUMNS TO EXISTING TABLE
    // =========================================================================

    /**
     * Add ALL possible missing columns to an existing table.
     *
     * @return void
     */
    private function addAllMissingColumns(): void
    {
        $existingColumns = $this->getExistingColumns();
        $missingColumns = [];

        // Define ALL columns with their callbacks (in order)
        $allColumns = [
            // Session identifiers
            'session_id' => function (Blueprint $table) {
                $table->string('session_id')->nullable()->after('id')
                    ->comment('Session identifier from instant estate');
            },
            'instant_estate_session_id' => function (Blueprint $table) {
                $table->foreignId('instant_estate_session_id')
                    ->nullable()
                    ->after('session_id')
                    ->constrained('instant_estate_sessions')
                    ->onDelete('cascade')
                    ->comment('Reference to the instant estate session');
            },

            // Foreign keys
            'estate_pre_registration_id' => function (Blueprint $table) {
                $table->foreignId('estate_pre_registration_id')
                    ->nullable()
                    ->after('instant_estate_session_id')
                    ->constrained('estate_pre_registrations')
                    ->onDelete('cascade')
                    ->comment('Reference to the estate pre-registration');
            },
            'user_id' => function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('estate_pre_registration_id')
                    ->constrained()
                    ->onDelete('cascade')
                    ->comment('User who requested the notification');
            },
            'requested_by' => function (Blueprint $table) {
                $table->foreignId('requested_by')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->onDelete('cascade')
                    ->comment('User who made the request');
            },

            // Recipient information
            'recipient_email' => function (Blueprint $table) {
                $table->string('recipient_email')->nullable()->after('requested_by')
                    ->comment('Email address of the notification recipient');
            },
            'recipient_name' => function (Blueprint $table) {
                $table->string('recipient_name')->nullable()->after('recipient_email')
                    ->comment('Name of the notification recipient');
            },

            // Request status
            'status' => function (Blueprint $table) {
                $table->string('status')->default('pending_admin_approval')->after('recipient_name')
                    ->comment('Status: pending_admin_approval, pending, approved, rejected, sent');
            },

            // Security token
            'access_token' => function (Blueprint $table) {
                $table->string('access_token', 64)->nullable()->after('status')
                    ->comment('Secure access token for beneficiary viewing');
            },

            // Deceased information
            'deceased_name' => function (Blueprint $table) {
                $table->string('deceased_name')->nullable()->after('access_token')
                    ->comment('Full name of the deceased person');
            },
            'deceased_nric' => function (Blueprint $table) {
                $table->string('deceased_nric')->nullable()->after('deceased_name')
                    ->comment('NRIC/Passport number of the deceased');
            },
            'death_date' => function (Blueprint $table) {
                $table->date('death_date')->nullable()->after('deceased_nric')
                    ->comment('Date of death from death certificate');
            },
            'death_place' => function (Blueprint $table) {
                $table->string('death_place')->nullable()->after('death_date')
                    ->comment('Place of death from death certificate');
            },

            // Timeline
            'requested_at' => function (Blueprint $table) {
                $table->timestamp('requested_at')->nullable()->after('death_place')
                    ->comment('Timestamp when notification was requested');
            },
            'approved_at' => function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('requested_at')
                    ->comment('Timestamp when admin approved the request');
            },
            'rejected_at' => function (Blueprint $table) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at')
                    ->comment('Timestamp when admin rejected the request');
            },
            'sent_at' => function (Blueprint $table) {
                $table->timestamp('sent_at')->nullable()->after('rejected_at')
                    ->comment('Timestamp when notification was sent');
            },

            // Approval/rejection tracking
            'approved_by' => function (Blueprint $table) {
                $table->foreignId('approved_by')
                    ->nullable()
                    ->after('sent_at')
                    ->constrained('users')
                    ->onDelete('set null')
                    ->comment('Admin who approved the request');
            },
            'rejected_by' => function (Blueprint $table) {
                $table->foreignId('rejected_by')
                    ->nullable()
                    ->after('approved_by')
                    ->constrained('users')
                    ->onDelete('set null')
                    ->comment('Admin who rejected the request');
            },

            // Admin notes
            'admin_notes' => function (Blueprint $table) {
                $table->text('admin_notes')->nullable()->after('rejected_by')
                    ->comment('Notes from admin regarding this request');
            },

            // Notification sending status
            'notification_sent' => function (Blueprint $table) {
                $table->boolean('notification_sent')->default(false)->after('admin_notes')
                    ->comment('Whether notification emails have been sent');
            },
            'notification_sent_at' => function (Blueprint $table) {
                $table->timestamp('notification_sent_at')->nullable()->after('notification_sent')
                    ->comment('Timestamp when notification emails were sent');
            },

            // Email tracking
            'emails_sent' => function (Blueprint $table) {
                $table->boolean('emails_sent')->default(false)->after('notification_sent_at')
                    ->comment('Whether emails were actually dispatched');
            },
            'email_status' => function (Blueprint $table) {
                $table->string('email_status')->nullable()->after('emails_sent')
                    ->comment('Status of email delivery: sent, partial, failed');
            },
            'email_results' => function (Blueprint $table) {
                $table->json('email_results')->nullable()->after('email_status')
                    ->comment('Detailed results of email sending');
            },
            'recipients_count' => function (Blueprint $table) {
                $table->integer('recipients_count')->default(0)->after('email_results')
                    ->comment('Number of recipients notified');
            },
            'recipients_list' => function (Blueprint $table) {
                $table->json('recipients_list')->nullable()->after('recipients_count')
                    ->comment('List of recipients with their details');
            },

            // Verification & rejection details
            'verification_result' => function (Blueprint $table) {
                $table->json('verification_result')->nullable()->after('recipients_list')
                    ->comment('Result of death certificate verification');
            },
            'rejection_reason' => function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('verification_result')
                    ->comment('Reason for rejection if applicable');
            },

            // Metadata
            'request_metadata' => function (Blueprint $table) {
                $table->json('request_metadata')->nullable()->after('rejection_reason')
                    ->comment('Metadata about the notification request');
            },
            'metadata' => function (Blueprint $table) {
                $table->json('metadata')->nullable()->after('request_metadata')
                    ->comment('Additional metadata for extensibility');
            },
        ];

        // Find which columns are missing
        foreach ($allColumns as $columnName => $callback) {
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

            Log::info("Migration: Added " . count($missingColumns) . " missing columns to '{$this->table}' table.", [
                'columns' => array_keys($missingColumns),
            ]);
        } else {
            Log::info("Migration: Table '{$this->table}' already has all expected columns.");
        }
    }

    // =========================================================================
    // ADD ALL MISSING INDEXES
    // =========================================================================

    /**
     * Add ALL possible missing indexes to an existing table.
     *
     * @return void
     */
    private function addAllMissingIndexes(): void
    {
        $requiredIndexes = [
            'nr_session_id_idx' => ['session_id'],
            'nr_status_idx' => ['status'],
            'nr_deceased_nric_idx' => ['deceased_nric'],
            'nr_estate_id_idx' => ['estate_pre_registration_id'],
            'nr_session_fk_idx' => ['instant_estate_session_id'],
            'nr_user_id_idx' => ['user_id'],
            'nr_requested_by_idx' => ['requested_by'],
            'nr_status_created_idx' => ['status', 'created_at'],
        ];

        $addedCount = 0;
        foreach ($requiredIndexes as $indexName => $columns) {
            if (!$this->hasIndex($indexName)) {
                // Check all columns exist before creating index
                $allColumnsExist = true;
                foreach ($columns as $column) {
                    if (!Schema::hasColumn($this->table, $column)) {
                        $allColumnsExist = false;
                        break;
                    }
                }

                if ($allColumnsExist) {
                    Schema::table($this->table, function (Blueprint $table) use ($columns, $indexName) {
                        if (count($columns) === 1) {
                            $table->index($columns[0], $indexName);
                        } else {
                            $table->index($columns, $indexName);
                        }
                    });
                    $addedCount++;
                    Log::info("Migration: Added index '{$indexName}' to '{$this->table}'.");
                }
            }
        }

        if ($addedCount > 0) {
            Log::info("Migration: Added {$addedCount} missing indexes to '{$this->table}' table.");
        } else {
            Log::info("Migration: Table '{$this->table}' already has all expected indexes.");
        }
    }

    // =========================================================================
    // MODIFY STATUS COLUMN
    // =========================================================================

    /**
     * Modify the status column to support all required statuses.
     *
     * @return void
     */
    private function modifyStatusColumn(): void
    {
        if (Schema::hasColumn($this->table, 'status')) {
            try {
                DB::statement("ALTER TABLE {$this->table} MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending_admin_approval'");
                Log::info("Migration: Modified status column in '{$this->table}'.");
            } catch (\Exception $e) {
                Log::warning("Migration: Failed to modify status column in '{$this->table}': " . $e->getMessage());
            }
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
            $connection = Schema::getConnection();
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
     * @param  string  $indexName
     * @return bool
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
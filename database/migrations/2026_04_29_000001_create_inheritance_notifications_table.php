<?php
// File: database/migrations/2026_04_30_000001_create_inheritance_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * The table name.
     *
     * @var string
     */
    protected string $table = 'inheritance_notifications';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if table already exists
        if (Schema::hasTable($this->table)) {
            Log::info("Table '{$this->table}' already exists. Adding missing columns...");
            
            // Add any missing columns to existing table
            $this->addMissingColumns();
            
            // Add any missing indexes
            $this->addMissingIndexes();
            
            return;
        }

        // Create table if it doesn't exist
        Log::info("Creating '{$this->table}' table...");

        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->foreignId('estate_id')
                ->constrained('estate_pre_registrations')
                ->onDelete('cascade')
                ->comment('Foreign key to estate pre-registration');
            $table->foreignId('estate_pre_registration_id')
                ->nullable()
                ->constrained('estate_pre_registrations')
                ->onDelete('set null')
                ->comment('Alternative foreign key to estate pre-registration');
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade')
                ->comment('User who requested the notification');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('access_token', 64)->unique()->nullable();
            $table->text('admin_notes')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('notification_sent_at')->nullable();
            $table->json('recipients')->nullable();
            $table->json('email_results')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('estate_id');
            $table->index('estate_pre_registration_id');
            $table->index('access_token');
            $table->index('user_id');
            $table->index(['estate_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['notification_sent', 'status']);
        });
    }

    /**
     * Add missing columns to existing table.
     *
     * @return void
     */
    private function addMissingColumns(): void
    {
        $existingColumns = $this->getExistingColumns();

        $columnsToAdd = [
            'estate_id' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'estate_id')) {
                    $table->foreignId('estate_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('estate_pre_registrations')
                        ->onDelete('cascade')
                        ->comment('Foreign key to estate pre-registration');
                }
            },
            'estate_pre_registration_id' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'estate_pre_registration_id')) {
                    $table->foreignId('estate_pre_registration_id')
                        ->nullable()
                        ->after('estate_id')
                        ->constrained('estate_pre_registrations')
                        ->onDelete('set null')
                        ->comment('Alternative foreign key to estate pre-registration');
                }
            },
            'user_id' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'user_id')) {
                    $table->foreignId('user_id')
                        ->nullable()
                        ->after('estate_pre_registration_id')
                        ->constrained()
                        ->onDelete('cascade')
                        ->comment('User who requested the notification');
                }
            },
            'requested_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'requested_at')) {
                    $table->timestamp('requested_at')->nullable()->after('user_id');
                }
            },
            'approved_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('requested_at');
                }
            },
            'rejected_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('approved_at');
                }
            },
            'status' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'status')) {
                    $table->string('status')->default('pending')->after('rejected_at');
                }
            },
            'access_token' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'access_token')) {
                    $table->string('access_token', 64)->unique()->nullable()->after('status');
                }
            },
            'admin_notes' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('access_token');
                }
            },
            'notification_sent' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'notification_sent')) {
                    $table->boolean('notification_sent')->default(false)->after('admin_notes');
                }
            },
            'notification_sent_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'notification_sent_at')) {
                    $table->timestamp('notification_sent_at')->nullable()->after('notification_sent');
                }
            },
            'recipients' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'recipients')) {
                    $table->json('recipients')->nullable()->after('notification_sent_at');
                }
            },
            'email_results' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'email_results')) {
                    $table->json('email_results')->nullable()->after('recipients');
                }
            },
            'created_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'created_at')) {
                    $table->timestamp('created_at')->nullable()->after('email_results');
                }
            },
            'updated_at' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                }
            },
        ];

        // Add missing columns
        Schema::table($this->table, function (Blueprint $table) use ($columnsToAdd) {
            foreach ($columnsToAdd as $callback) {
                $callback($table);
            }
        });
    }

    /**
     * Add missing indexes.
     *
     * @return void
     */
    private function addMissingIndexes(): void
    {
        $requiredIndexes = [
            'inheritance_notifications_status_index' => ['status'],
            'inheritance_notifications_estate_id_index' => ['estate_id'],
            'inheritance_notifications_estate_pre_registration_id_index' => ['estate_pre_registration_id'],
            'inheritance_notifications_access_token_index' => ['access_token'],
            'inheritance_notifications_user_id_index' => ['user_id'],
            'inheritance_notifications_estate_id_status_index' => ['estate_id', 'status'],
            'inheritance_notifications_status_created_at_index' => ['status', 'created_at'],
            'inheritance_notifications_notification_sent_status_index' => ['notification_sent', 'status'],
        ];

        Schema::table($this->table, function (Blueprint $table) use ($requiredIndexes) {
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
                        if (count($columns) === 1) {
                            $table->index($columns[0], $indexName);
                        } else {
                            $table->index($columns, $indexName);
                        }
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Don't drop the table if it existed before this migration
        // Only remove columns that were added by this migration
        
        $columnsAddedByThisMigration = [
            'estate_id',
            'estate_pre_registration_id',
            'user_id',
            'requested_at',
            'approved_at',
            'rejected_at',
            'status',
            'access_token',
            'admin_notes',
            'notification_sent',
            'notification_sent_at',
            'recipients',
            'email_results',
        ];

        Schema::table($this->table, function (Blueprint $table) use ($columnsAddedByThisMigration) {
            foreach ($columnsAddedByThisMigration as $column) {
                if (Schema::hasColumn($this->table, $column)) {
                    // Drop foreign keys first
                    if (in_array($column, ['estate_id', 'estate_pre_registration_id', 'user_id'])) {
                        $foreignKey = $this->table . '_' . $column . '_foreign';
                        try {
                            $table->dropForeign($foreignKey);
                        } catch (\Exception $e) {
                            // Foreign key might have a different name, try alternative
                            $alternativeForeignKey = $column . '_foreign';
                            try {
                                $table->dropForeign($alternativeForeignKey);
                            } catch (\Exception $e2) {
                                Log::warning("Could not drop foreign key for column '{$column}': " . $e2->getMessage());
                            }
                        }
                    }
                    
                    $table->dropColumn($column);
                }
            }
        });

        // Also remove indexes that were added
        $indexesAddedByThisMigration = [
            'inheritance_notifications_status_index',
            'inheritance_notifications_estate_id_index',
            'inheritance_notifications_estate_pre_registration_id_index',
            'inheritance_notifications_access_token_index',
            'inheritance_notifications_user_id_index',
            'inheritance_notifications_estate_id_status_index',
            'inheritance_notifications_status_created_at_index',
            'inheritance_notifications_notification_sent_status_index',
        ];

        Schema::table($this->table, function (Blueprint $table) use ($indexesAddedByThisMigration) {
            foreach ($indexesAddedByThisMigration as $indexName) {
                if ($this->hasIndex($indexName)) {
                    try {
                        $table->dropIndex($indexName);
                    } catch (\Exception $e) {
                        Log::warning("Could not drop index '{$indexName}': " . $e->getMessage());
                    }
                }
            }
        });

        // Optionally drop the entire table if it was created by this migration
        // Uncomment the line below if you want to drop the table completely
        // Schema::dropIfExists($this->table);
    }

    // =========================================================================
    // UTILITY METHODS
    // =========================================================================

    /**
     * Get existing columns in the table.
     *
     * @return array
     */
    private function getExistingColumns(): array
    {
        try {
            return Schema::getColumnListing($this->table);
        } catch (\Exception $e) {
            Log::warning("Failed to get columns for '{$this->table}': " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if an index exists.
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
            }

            if ($driver === 'pgsql') {
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM pg_indexes 
                     WHERE tablename = ? 
                     AND indexname = ?",
                    [$this->table, $indexName]
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
            Log::warning("Failed to check index '{$indexName}': " . $e->getMessage());
            return false;
        }
    }
};
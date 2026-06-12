<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * The name of the table to be created.
     *
     * @var string
     */
    protected $table = 'pre_registered_heirs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if the table already exists
        if (Schema::hasTable($this->table)) {
            Log::info("Migration: Table '{$this->table}' already exists. Adding missing columns and indexes...");
            
            // Add any missing columns
            $this->addMissingColumns();
            
            // Add any missing indexes
            $this->addMissingIndexes();
            
            return;
        }

        Log::info("Migration: Creating '{$this->table}' table.");

        Schema::create($this->table, function (Blueprint $table) {
            // =================================================================
            // PRIMARY KEY
            // =================================================================
            $table->id();

            // =================================================================
            // FOREIGN KEY - ESTATE RELATIONSHIP
            // =================================================================
            $table->foreignId('estate_pre_registration_id')
                ->constrained('estate_pre_registrations')
                ->onDelete('cascade')
                ->comment('Foreign key to the estate pre-registration');

            // =================================================================
            // PERSONAL INFORMATION
            // =================================================================
            $table->string('name')
                ->comment('Full name of the heir');

            $table->string('nric', 20)
                ->nullable()
                ->comment('NRIC/Passport number of the heir');

            $table->string('email')
                ->nullable()
                ->comment('Email address of the heir (required for notifications)');

            $table->enum('gender', ['male', 'female'])
                ->nullable()
                ->comment('Gender of the heir');

            $table->date('date_of_birth')
                ->nullable()
                ->comment('Date of birth of the heir');

            $table->string('phone', 20)
                ->nullable()
                ->comment('Contact phone number of the heir');

            $table->text('address')
                ->nullable()
                ->comment('Residential address of the heir');

            // =================================================================
            // RELATIONSHIP INFORMATION
            // =================================================================
            $table->string('relationship')
                ->comment('Relationship of heir to deceased (e.g., husband, wife, son, daughter, father, mother, etc.)');

            $table->string('relationship_type')
                ->nullable()
                ->comment('Type of relationship: primary, substitute, secondary, asabah, other');

            // =================================================================
            // INHERITANCE DETAILS
            // =================================================================
            $table->decimal('share_percentage', 5, 2)
                ->nullable()
                ->comment('Manually set share percentage for this heir');

            $table->decimal('calculated_percentage', 5, 2)
                ->nullable()
                ->comment('Auto-calculated percentage by Faraid rules');

            $table->integer('priority')
                ->default(0)
                ->comment('Priority order for distribution (lower number = higher priority)');

            // =================================================================
            // METADATA
            // =================================================================
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
            $table->index('email', 'heirs_email_idx');
            $table->index('nric', 'heirs_nric_idx');
            $table->index('phone', 'heirs_phone_idx');
            $table->index('estate_pre_registration_id', 'heirs_estate_id_idx');
            $table->index('relationship_type', 'heirs_relationship_type_idx');
            $table->index('relationship', 'heirs_relationship_idx');

            // Composite indexes for common queries
            $table->index(
                ['estate_pre_registration_id', 'relationship_type'], 
                'heirs_estate_rel_type_composite_idx'
            );
            $table->index(
                ['estate_pre_registration_id', 'priority'], 
                'heirs_estate_priority_composite_idx'
            );
        });

        Log::info("Migration: Successfully created '{$this->table}' table with all columns, indexes, and foreign keys.");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Check if the table exists before attempting to drop it
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration rollback: Table '{$this->table}' does not exist. Skipping.");
            return;
        }

        Log::info("Migration rollback: Dropping '{$this->table}' table.");

        // Drop the table (foreign keys will be automatically dropped with cascade)
        Schema::dropIfExists($this->table);

        Log::info("Migration rollback: Successfully dropped '{$this->table}' table.");
    }

    // =========================================================================
    // HELPER METHOD: ADD MISSING COLUMNS TO EXISTING TABLE
    // =========================================================================

    /**
     * Add any missing columns to an existing table.
     *
     * @return void
     */
    private function addMissingColumns(): void
    {
        $existingColumns = $this->getExistingColumns();
        
        // Define all expected columns with their callbacks
        $expectedColumns = [
            'gender' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'gender')) {
                    $table->enum('gender', ['male', 'female'])->nullable()->after('relationship_type')
                        ->comment('Gender of the heir');
                }
            },
            'date_of_birth' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable()->after('gender')
                        ->comment('Date of birth of the heir');
                }
            },
            'address' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'address')) {
                    $table->text('address')->nullable()->after('phone')
                        ->comment('Residential address of the heir');
                }
            },
            'calculated_percentage' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'calculated_percentage')) {
                    $table->decimal('calculated_percentage', 5, 2)->nullable()->after('share_percentage')
                        ->comment('Auto-calculated percentage by Faraid rules');
                }
            },
            'priority' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'priority')) {
                    $table->integer('priority')->default(0)->after('calculated_percentage')
                        ->comment('Priority order for distribution');
                }
            },
            'metadata' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'metadata')) {
                    $table->json('metadata')->nullable()->after('priority')
                        ->comment('Additional metadata for extensibility');
                }
            },
        ];

        // Find which columns are missing
        $missingColumns = [];
        foreach ($expectedColumns as $columnName => $callback) {
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
    // HELPER METHOD: ADD MISSING INDEXES
    // =========================================================================

    /**
     * Add any missing indexes to an existing table.
     *
     * @return void
     */
    private function addMissingIndexes(): void
    {
        $requiredIndexes = [
            'heirs_estate_id_idx' => ['estate_pre_registration_id'],
            'heirs_email_idx' => ['email'],
            'heirs_nric_idx' => ['nric'],
            'heirs_phone_idx' => ['phone'],
            'heirs_relationship_type_idx' => ['relationship_type'],
            'heirs_relationship_idx' => ['relationship'],
            'heirs_estate_rel_type_composite_idx' => ['estate_pre_registration_id', 'relationship_type'],
            'heirs_estate_priority_composite_idx' => ['estate_pre_registration_id', 'priority'],
        ];

        $missingIndexes = [];
        foreach ($requiredIndexes as $indexName => $columns) {
            if (!$this->hasIndex($indexName)) {
                // Check if all columns for this index exist
                $allColumnsExist = true;
                foreach ($columns as $column) {
                    if (!Schema::hasColumn($this->table, $column)) {
                        $allColumnsExist = false;
                        break;
                    }
                }
                if ($allColumnsExist) {
                    $missingIndexes[$indexName] = $columns;
                }
            }
        }

        if (!empty($missingIndexes)) {
            Schema::table($this->table, function (Blueprint $table) use ($missingIndexes) {
                foreach ($missingIndexes as $indexName => $columns) {
                    if (count($columns) === 1) {
                        $table->index($columns[0], $indexName);
                    } else {
                        $table->index($columns, $indexName);
                    }
                }
            });

            Log::info("Migration: Added " . count($missingIndexes) . " missing indexes to '{$this->table}' table.", [
                'indexes' => array_keys($missingIndexes),
            ]);
        } else {
            Log::info("Migration: Table '{$this->table}' already has all expected indexes.");
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
            
            // Fallback using Schema builder
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
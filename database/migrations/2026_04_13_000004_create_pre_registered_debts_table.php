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
    protected $table = 'pre_registered_debts';

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
            // CREDITOR INFORMATION
            // =================================================================
            $table->string('creditor_name')
                ->comment('Name of the creditor (bank, person, institution)');

            $table->string('creditor_contact')
                ->nullable()
                ->comment('Contact information for the creditor (phone, email, or address)');

            // =================================================================
            // DEBT DETAILS
            // =================================================================
            $table->string('debt_type', 100)
                ->nullable()
                ->comment('Type/category of debt (e.g., Housing Loan, Credit Card, Personal Loan, etc.)');

            $table->text('description')
                ->nullable()
                ->comment('Description or additional details about this debt');

            $table->decimal('amount', 15, 2)
                ->default(0)
                ->comment('Outstanding amount of the debt in RM');

            $table->string('type', 50)
                ->nullable()
                ->comment('Classification type: secured, unsecured, personal, religious, government, administrative, other');

            $table->string('category', 100)
                ->nullable()
                ->comment('Additional category for the debt');

            // =================================================================
            // DEBT TIMELINE
            // =================================================================
            $table->date('due_date')
                ->nullable()
                ->comment('Due date for the debt payment (if applicable)');

            // =================================================================
            // REFERENCE AND DOCUMENTATION
            // =================================================================
            $table->string('reference_number', 100)
                ->nullable()
                ->comment('Reference number, account number, or loan ID');

            $table->json('documents')
                ->nullable()
                ->comment('JSON array of document paths or URLs related to this debt');

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
            // Foreign key index
            $table->index('estate_pre_registration_id', 'debts_estate_id_idx');

            // Creditor lookup index
            $table->index('creditor_name', 'debts_creditor_name_idx');

            // Type and category indexes
            $table->index('type', 'debts_type_idx');
            $table->index('debt_type', 'debts_debt_type_idx');
            $table->index('category', 'debts_category_idx');

            // Date index
            $table->index('due_date', 'debts_due_date_idx');

            // Composite index for common queries
            $table->index(
                ['estate_pre_registration_id', 'type'], 
                'debts_estate_type_composite_idx'
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
            'creditor_contact' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'creditor_contact')) {
                    $table->string('creditor_contact')->nullable()->after('creditor_name')
                        ->comment('Contact information for the creditor (phone, email, or address)');
                }
            },
            'debt_type' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'debt_type')) {
                    $table->string('debt_type', 100)->nullable()->after('creditor_contact')
                        ->comment('Type/category of debt (e.g., Housing Loan, Credit Card, Personal Loan, etc.)');
                }
            },
            'description' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'description')) {
                    $table->text('description')->nullable()->after('debt_type')
                        ->comment('Description or additional details about this debt');
                }
            },
            'amount' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0)->after('description')
                        ->comment('Outstanding amount of the debt in RM');
                }
            },
            'type' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'type')) {
                    $table->string('type', 50)->nullable()->after('amount')
                        ->comment('Classification type: secured, unsecured, personal, religious, government, administrative, other');
                }
            },
            'category' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'category')) {
                    $table->string('category', 100)->nullable()->after('type')
                        ->comment('Additional category for the debt');
                }
            },
            'due_date' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'due_date')) {
                    $table->date('due_date')->nullable()->after('category')
                        ->comment('Due date for the debt payment (if applicable)');
                }
            },
            'reference_number' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'reference_number')) {
                    $table->string('reference_number', 100)->nullable()->after('due_date')
                        ->comment('Reference number, account number, or loan ID');
                }
            },
            'documents' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'documents')) {
                    $table->json('documents')->nullable()->after('reference_number')
                        ->comment('JSON array of document paths or URLs related to this debt');
                }
            },
            'metadata' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'metadata')) {
                    $table->json('metadata')->nullable()->after('documents')
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
            'debts_estate_id_idx' => ['estate_pre_registration_id'],
            'debts_creditor_name_idx' => ['creditor_name'],
            'debts_type_idx' => ['type'],
            'debts_debt_type_idx' => ['debt_type'],
            'debts_category_idx' => ['category'],
            'debts_due_date_idx' => ['due_date'],
            'debts_estate_type_composite_idx' => ['estate_pre_registration_id', 'type'],
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
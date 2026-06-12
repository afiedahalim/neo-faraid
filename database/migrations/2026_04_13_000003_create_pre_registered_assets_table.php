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
    protected $table = 'pre_registered_assets';

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
            // ASSET DETAILS
            // =================================================================
            $table->string('name')
                ->comment('Name or type of the asset (e.g., Residential House, Bank Savings, etc.)');

            $table->string('type', 100)
                ->nullable()
                ->comment('Type classification of the asset');

            $table->string('category', 100)
                ->nullable()
                ->comment('Category of the asset (e.g., Real Estate, Financial, Investment, Digital, etc.)');

            $table->text('description')
                ->nullable()
                ->comment('Description, location, or additional details about this asset');

            $table->decimal('value', 15, 2)
                ->default(0)
                ->comment('Estimated value of the asset in RM');

            $table->string('location')
                ->nullable()
                ->comment('Physical location of the asset (for properties, addresses, etc.)');

            $table->decimal('ownership_percentage', 5, 2)
                ->default(100)
                ->comment('Ownership percentage of the deceased in this asset (0-100)');

            // =================================================================
            // REFERENCE AND DOCUMENTATION
            // =================================================================
            $table->string('reference_number', 100)
                ->nullable()
                ->comment('Reference number, account number, title deed number, etc.');

            $table->json('documents')
                ->nullable()
                ->comment('JSON array of document paths or URLs related to this asset');

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
            $table->index('estate_pre_registration_id', 'assets_estate_id_idx');

            // Asset lookup indexes
            $table->index('type', 'assets_type_idx');
            $table->index('category', 'assets_category_idx');
            $table->index('name', 'assets_name_idx');

            // Composite index for common queries
            $table->index(
                ['estate_pre_registration_id', 'type'], 
                'assets_estate_type_composite_idx'
            );
            $table->index(
                ['estate_pre_registration_id', 'category'], 
                'assets_estate_category_composite_idx'
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
            'type' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'type')) {
                    $table->string('type', 100)->nullable()->after('name')
                        ->comment('Type classification of the asset');
                }
            },
            'category' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'category')) {
                    $table->string('category', 100)->nullable()->after('type')
                        ->comment('Category of the asset (e.g., Real Estate, Financial, Investment, Digital, etc.)');
                }
            },
            'description' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'description')) {
                    $table->text('description')->nullable()->after('category')
                        ->comment('Description, location, or additional details about this asset');
                }
            },
            'value' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'value')) {
                    $table->decimal('value', 15, 2)->default(0)->after('description')
                        ->comment('Estimated value of the asset in RM');
                }
            },
            'location' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'location')) {
                    $table->string('location')->nullable()->after('value')
                        ->comment('Physical location of the asset (for properties, addresses, etc.)');
                }
            },
            'ownership_percentage' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'ownership_percentage')) {
                    $table->decimal('ownership_percentage', 5, 2)->default(100)->after('location')
                        ->comment('Ownership percentage of the deceased in this asset (0-100)');
                }
            },
            'reference_number' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'reference_number')) {
                    $table->string('reference_number', 100)->nullable()->after('ownership_percentage')
                        ->comment('Reference number, account number, title deed number, etc.');
                }
            },
            'documents' => function (Blueprint $table) {
                if (!Schema::hasColumn($this->table, 'documents')) {
                    $table->json('documents')->nullable()->after('reference_number')
                        ->comment('JSON array of document paths or URLs related to this asset');
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
                foreach ($missingColumns as $callback) {
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
            'assets_estate_id_idx' => ['estate_pre_registration_id'],
            'assets_type_idx' => ['type'],
            'assets_category_idx' => ['category'],
            'assets_name_idx' => ['name'],
            'assets_estate_type_composite_idx' => ['estate_pre_registration_id', 'type'],
            'assets_estate_category_composite_idx' => ['estate_pre_registration_id', 'category'],
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
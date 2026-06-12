<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * The name of the table being modified.
     *
     * @var string
     */
    protected $table = 'estate_pre_registrations';

    /**
     * The fields to be added to the table.
     *
     * @var array
     */
    protected $fieldsToAdd = [
        'trustee_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'address',
            'comment' => 'Full name of the appointed trustee/wasi',
        ],
        'trustee_nric' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'trustee_name',
            'comment' => 'NRIC/Passport number of the trustee',
        ],
        'trustee_phone' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'trustee_nric',
            'comment' => 'Contact phone number of the trustee',
        ],
        'trustee_email' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'trustee_phone',
            'comment' => 'Email address of the trustee for notifications',
        ],
        'trustee_relationship' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'trustee_email',
            'comment' => 'Relationship of trustee to deceased (e.g., spouse, child, sibling, friend)',
        ],
        'trustee_address' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'trustee_relationship',
            'comment' => 'Full residential address of the trustee',
        ],
        'wasiyyah_instructions' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'will_text_content',
            'comment' => 'General instructions for wasiyyah/will execution',
        ],
        'special_instructions' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'wasiyyah_instructions',
            'comment' => 'Any special instructions or conditions for estate distribution',
        ],
        'funeral_instructions' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'special_instructions',
            'comment' => 'Instructions for funeral arrangements',
        ],
        'emergency_contact_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'funeral_instructions',
            'comment' => 'Name of emergency contact person',
        ],
        'emergency_contact_phone' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'emergency_contact_name',
            'comment' => 'Phone number of emergency contact person',
        ],
        'emergency_contact_relationship' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'emergency_contact_phone',
            'comment' => 'Relationship of emergency contact to deceased',
        ],
        'estate_valuation_date' => [
            'type' => 'date',
            'nullable' => true,
            'after' => 'emergency_contact_relationship',
            'comment' => 'Date when the estate assets were last valued',
        ],
        'estate_valuation_notes' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'estate_valuation_date',
            'comment' => 'Notes about estate valuation',
        ],
        'lawyer_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'estate_valuation_notes',
            'comment' => 'Name of the lawyer handling the estate',
        ],
        'lawyer_contact' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'lawyer_name',
            'comment' => 'Contact information for the lawyer',
        ],
        'lawyer_firm' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'lawyer_contact',
            'comment' => 'Law firm name',
        ],
        'witness_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'lawyer_firm',
            'comment' => 'Name of the will witness',
        ],
        'witness_nric' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'witness_name',
            'comment' => 'NRIC/Passport of the will witness',
        ],
        'witness_phone' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'witness_nric',
            'comment' => 'Phone number of the will witness',
        ],
        'alternate_trustee_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'witness_phone',
            'comment' => 'Name of alternate trustee if primary trustee is unable to serve',
        ],
        'alternate_trustee_nric' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'alternate_trustee_name',
            'comment' => 'NRIC/Passport of alternate trustee',
        ],
        'alternate_trustee_phone' => [
            'type' => 'string',
            'length' => 20,
            'nullable' => true,
            'after' => 'alternate_trustee_nric',
            'comment' => 'Phone number of alternate trustee',
        ],
        'alternate_trustee_email' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'alternate_trustee_phone',
            'comment' => 'Email of alternate trustee',
        ],
        'alternate_trustee_relationship' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'alternate_trustee_email',
            'comment' => 'Relationship of alternate trustee to deceased',
        ],
        'is_shariah_compliant' => [
            'type' => 'boolean',
            'nullable' => false,
            'default' => true,
            'after' => 'alternate_trustee_relationship',
            'comment' => 'Flag indicating if the estate follows Shariah compliance',
        ],
        'shariah_advisor_name' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'is_shariah_compliant',
            'comment' => 'Name of Shariah advisor consulted',
        ],
        'shariah_advisor_contact' => [
            'type' => 'string',
            'nullable' => true,
            'after' => 'shariah_advisor_name',
            'comment' => 'Contact information for Shariah advisor',
        ],
        'estate_notes' => [
            'type' => 'text',
            'nullable' => true,
            'after' => 'shariah_advisor_contact',
            'comment' => 'General notes about the estate',
        ],
        'reviewed_at' => [
            'type' => 'timestamp',
            'nullable' => true,
            'after' => 'estate_notes',
            'comment' => 'Timestamp when the estate was last reviewed by user',
        ],
        'reviewed_by' => [
            'type' => 'unsignedBigInteger',
            'nullable' => true,
            'after' => 'reviewed_at',
            'comment' => 'User ID of person who last reviewed the estate',
        ],
    ];

    /**
     * The indexes to be created on the table.
     *
     * @var array
     */
    protected $indexesToAdd = [
        'trustee_email' => ['trustee_email'],
        'trustee_nric' => ['trustee_nric'],
        'is_shariah_compliant' => ['is_shariah_compliant'],
        'reviewed_at' => ['reviewed_at'],
        'lawyer_name' => ['lawyer_name'],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if the table exists before attempting to modify it
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration: Table '{$this->table}' does not exist. Skipping migration.");
            return;
        }

        Schema::table($this->table, function (Blueprint $table) {
            // Add trustee fields
            if (!Schema::hasColumn($this->table, 'trustee_name')) {
                $table->string('trustee_name')->nullable()->after('address')
                    ->comment('Full name of the appointed trustee/wasi');
            }

            if (!Schema::hasColumn($this->table, 'trustee_nric')) {
                $table->string('trustee_nric', 20)->nullable()->after('trustee_name')
                    ->comment('NRIC/Passport number of the trustee');
            }

            if (!Schema::hasColumn($this->table, 'trustee_phone')) {
                $table->string('trustee_phone', 20)->nullable()->after('trustee_nric')
                    ->comment('Contact phone number of the trustee');
            }

            if (!Schema::hasColumn($this->table, 'trustee_email')) {
                $table->string('trustee_email')->nullable()->after('trustee_phone')
                    ->comment('Email address of the trustee for notifications');
            }

            if (!Schema::hasColumn($this->table, 'trustee_relationship')) {
                $table->string('trustee_relationship')->nullable()->after('trustee_email')
                    ->comment('Relationship of trustee to deceased');
            }

            if (!Schema::hasColumn($this->table, 'trustee_address')) {
                $table->text('trustee_address')->nullable()->after('trustee_relationship')
                    ->comment('Full residential address of the trustee');
            }

            // Add wasiyyah and instructions fields
            if (!Schema::hasColumn($this->table, 'wasiyyah_instructions')) {
                $table->text('wasiyyah_instructions')->nullable()->after('will_text_content')
                    ->comment('General instructions for wasiyyah/will execution');
            }

            if (!Schema::hasColumn($this->table, 'special_instructions')) {
                $table->text('special_instructions')->nullable()->after('wasiyyah_instructions')
                    ->comment('Any special instructions or conditions for estate distribution');
            }

            if (!Schema::hasColumn($this->table, 'funeral_instructions')) {
                $table->text('funeral_instructions')->nullable()->after('special_instructions')
                    ->comment('Instructions for funeral arrangements');
            }

            // Add emergency contact fields
            if (!Schema::hasColumn($this->table, 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('funeral_instructions')
                    ->comment('Name of emergency contact person');
            }

            if (!Schema::hasColumn($this->table, 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name')
                    ->comment('Phone number of emergency contact person');
            }

            if (!Schema::hasColumn($this->table, 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone')
                    ->comment('Relationship of emergency contact to deceased');
            }

            // Add estate valuation fields
            if (!Schema::hasColumn($this->table, 'estate_valuation_date')) {
                $table->date('estate_valuation_date')->nullable()->after('emergency_contact_relationship')
                    ->comment('Date when the estate assets were last valued');
            }

            if (!Schema::hasColumn($this->table, 'estate_valuation_notes')) {
                $table->text('estate_valuation_notes')->nullable()->after('estate_valuation_date')
                    ->comment('Notes about estate valuation');
            }

            // Add lawyer information fields
            if (!Schema::hasColumn($this->table, 'lawyer_name')) {
                $table->string('lawyer_name')->nullable()->after('estate_valuation_notes')
                    ->comment('Name of the lawyer handling the estate');
            }

            if (!Schema::hasColumn($this->table, 'lawyer_contact')) {
                $table->string('lawyer_contact')->nullable()->after('lawyer_name')
                    ->comment('Contact information for the lawyer');
            }

            if (!Schema::hasColumn($this->table, 'lawyer_firm')) {
                $table->string('lawyer_firm')->nullable()->after('lawyer_contact')
                    ->comment('Law firm name');
            }

            // Add witness information fields
            if (!Schema::hasColumn($this->table, 'witness_name')) {
                $table->string('witness_name')->nullable()->after('lawyer_firm')
                    ->comment('Name of the will witness');
            }

            if (!Schema::hasColumn($this->table, 'witness_nric')) {
                $table->string('witness_nric', 20)->nullable()->after('witness_name')
                    ->comment('NRIC/Passport of the will witness');
            }

            if (!Schema::hasColumn($this->table, 'witness_phone')) {
                $table->string('witness_phone', 20)->nullable()->after('witness_nric')
                    ->comment('Phone number of the will witness');
            }

            // Add alternate trustee fields
            if (!Schema::hasColumn($this->table, 'alternate_trustee_name')) {
                $table->string('alternate_trustee_name')->nullable()->after('witness_phone')
                    ->comment('Name of alternate trustee if primary trustee is unable to serve');
            }

            if (!Schema::hasColumn($this->table, 'alternate_trustee_nric')) {
                $table->string('alternate_trustee_nric', 20)->nullable()->after('alternate_trustee_name')
                    ->comment('NRIC/Passport of alternate trustee');
            }

            if (!Schema::hasColumn($this->table, 'alternate_trustee_phone')) {
                $table->string('alternate_trustee_phone', 20)->nullable()->after('alternate_trustee_nric')
                    ->comment('Phone number of alternate trustee');
            }

            if (!Schema::hasColumn($this->table, 'alternate_trustee_email')) {
                $table->string('alternate_trustee_email')->nullable()->after('alternate_trustee_phone')
                    ->comment('Email of alternate trustee');
            }

            if (!Schema::hasColumn($this->table, 'alternate_trustee_relationship')) {
                $table->string('alternate_trustee_relationship')->nullable()->after('alternate_trustee_email')
                    ->comment('Relationship of alternate trustee to deceased');
            }

            // Add Shariah compliance fields
            if (!Schema::hasColumn($this->table, 'is_shariah_compliant')) {
                $table->boolean('is_shariah_compliant')->default(true)->after('alternate_trustee_relationship')
                    ->comment('Flag indicating if the estate follows Shariah compliance');
            }

            if (!Schema::hasColumn($this->table, 'shariah_advisor_name')) {
                $table->string('shariah_advisor_name')->nullable()->after('is_shariah_compliant')
                    ->comment('Name of Shariah advisor consulted');
            }

            if (!Schema::hasColumn($this->table, 'shariah_advisor_contact')) {
                $table->string('shariah_advisor_contact')->nullable()->after('shariah_advisor_name')
                    ->comment('Contact information for Shariah advisor');
            }

            // Add estate notes
            if (!Schema::hasColumn($this->table, 'estate_notes')) {
                $table->text('estate_notes')->nullable()->after('shariah_advisor_contact')
                    ->comment('General notes about the estate');
            }

            // Add review tracking fields
            if (!Schema::hasColumn($this->table, 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('estate_notes')
                    ->comment('Timestamp when the estate was last reviewed by user');
            }

            if (!Schema::hasColumn($this->table, 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at')
                    ->comment('User ID of person who last reviewed the estate');
            }
        });

        // Add indexes separately
        Schema::table($this->table, function (Blueprint $table) {
            // Create indexes if they don't exist
            if (!$this->hasIndex('estate_pre_registrations_trustee_email_index')) {
                $table->index('trustee_email');
            }
            
            if (!$this->hasIndex('estate_pre_registrations_trustee_nric_index')) {
                $table->index('trustee_nric');
            }
            
            if (!$this->hasIndex('estate_pre_registrations_is_shariah_compliant_index')) {
                $table->index('is_shariah_compliant');
            }
            
            if (!$this->hasIndex('estate_pre_registrations_reviewed_at_index')) {
                $table->index('reviewed_at');
            }
            
            if (!$this->hasIndex('estate_pre_registrations_lawyer_name_index')) {
                $table->index('lawyer_name');
            }
        });

        // Add foreign key constraint for reviewed_by
        if (Schema::hasColumn($this->table, 'reviewed_by') && Schema::hasTable('users')) {
            Schema::table($this->table, function (Blueprint $table) {
                // Check if foreign key already exists
                $foreignKeys = $this->getForeignKeys($this->table);
                $hasForeignKey = false;
                
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey === 'estate_pre_registrations_reviewed_by_foreign') {
                        $hasForeignKey = true;
                        break;
                    }
                }
                
                if (!$hasForeignKey) {
                    $table->foreign('reviewed_by')
                        ->references('id')
                        ->on('users')
                        ->onDelete('set null');
                }
            });
        }

        // Log successful migration
        Log::info('Migration: Trustee and additional fields added to estate_pre_registrations table', [
            'table' => $this->table,
            'fields_count' => count($this->fieldsToAdd),
            'indexes_created' => count($this->indexesToAdd),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Check if the table exists
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration rollback: Table '{$this->table}' does not exist. Skipping.");
            return;
        }

        Schema::table($this->table, function (Blueprint $table) {
            // Remove foreign key constraint first
            $foreignKeys = $this->getForeignKeys($this->table);
            
            if (in_array('estate_pre_registrations_reviewed_by_foreign', $foreignKeys)) {
                $table->dropForeign('estate_pre_registrations_reviewed_by_foreign');
            }

            // Remove indexes
            $indexesToDrop = [
                'estate_pre_registrations_trustee_email_index',
                'estate_pre_registrations_trustee_nric_index',
                'estate_pre_registrations_is_shariah_compliant_index',
                'estate_pre_registrations_reviewed_at_index',
                'estate_pre_registrations_lawyer_name_index',
            ];

            foreach ($indexesToDrop as $indexName) {
                if ($this->hasIndex($indexName)) {
                    $table->dropIndex($indexName);
                }
            }
        });

        // Drop columns in reverse order
        Schema::table($this->table, function (Blueprint $table) {
            // List of columns to drop (in reverse order of creation)
            $columnsToDrop = [
                'reviewed_by',
                'reviewed_at',
                'estate_notes',
                'shariah_advisor_contact',
                'shariah_advisor_name',
                'is_shariah_compliant',
                'alternate_trustee_relationship',
                'alternate_trustee_email',
                'alternate_trustee_phone',
                'alternate_trustee_nric',
                'alternate_trustee_name',
                'witness_phone',
                'witness_nric',
                'witness_name',
                'lawyer_firm',
                'lawyer_contact',
                'lawyer_name',
                'estate_valuation_notes',
                'estate_valuation_date',
                'emergency_contact_relationship',
                'emergency_contact_phone',
                'emergency_contact_name',
                'funeral_instructions',
                'special_instructions',
                'wasiyyah_instructions',
                'trustee_address',
                'trustee_relationship',
                'trustee_email',
                'trustee_phone',
                'trustee_nric',
                'trustee_name',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn($this->table, $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Log rollback
        Log::info('Migration rollback: Trustee and additional fields removed from estate_pre_registrations table', [
            'table' => $this->table,
        ]);
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
            $databaseName = $connection->getDatabaseName();
            
            // Get the database driver
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
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
                     WHERE schemaname = 'public' 
                     AND tablename = ? 
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
            
            // For other drivers, attempt to check via Schema builder
            $indexes = Schema::getIndexListing($this->table);
            return in_array($indexName, $indexes);
            
        } catch (\Exception $e) {
            Log::warning("Migration: Failed to check index existence: {$indexName}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get all foreign key constraint names for a table.
     *
     * @param  string  $tableName
     * @return array
     */
    private function getForeignKeys(string $tableName): array
    {
        try {
            $connection = Schema::getConnection();
            $databaseName = $connection->getDatabaseName();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                $results = DB::select(
                    "SELECT CONSTRAINT_NAME 
                     FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                     WHERE TABLE_SCHEMA = ? 
                     AND TABLE_NAME = ? 
                     AND REFERENCED_TABLE_NAME IS NOT NULL",
                    [$databaseName, $tableName]
                );
                
                return array_map(function ($row) {
                    return $row->CONSTRAINT_NAME;
                }, $results);
            } elseif ($driver === 'pgsql') {
                $results = DB::select(
                    "SELECT conname 
                     FROM pg_constraint 
                     WHERE conrelid = ?::regclass 
                     AND contype = 'f'",
                    [$tableName]
                );
                
                return array_map(function ($row) {
                    return $row->conname;
                }, $results);
            } elseif ($driver === 'sqlite') {
                $results = DB::select(
                    "PRAGMA foreign_key_list({$tableName})"
                );
                
                return []; // SQLite handles foreign keys differently
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::warning("Migration: Failed to get foreign keys for table: {$tableName}", [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

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
            
            return [];
            
        } catch (\Exception $e) {
            Log::warning("Migration: Failed to get existing columns for table: {$this->table}", [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
};
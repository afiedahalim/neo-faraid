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
    protected $table = 'pre_registered_wasiyyah';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if the table already exists
        if (Schema::hasTable($this->table)) {
            Log::warning("Migration: Table '{$this->table}' already exists. Checking for missing columns...");
            
            // Add any missing columns
            $this->addMissingColumns();
            
            // Add any missing indexes
            $this->addMissingIndexes();
            
            // Add any missing foreign keys
            $this->addMissingForeignKeys();
            
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
            // BENEFICIARY PERSONAL INFORMATION
            // =================================================================
            $table->string('beneficiary_name')
                ->comment('Full name of the wasiyyah beneficiary');

            $table->string('beneficiary_nric', 20)
                ->nullable()
                ->comment('NRIC/Passport number of the beneficiary');

            $table->string('beneficiary_email')
                ->nullable()
                ->comment('Email address of the beneficiary for notifications');

            $table->string('beneficiary_phone', 20)
                ->nullable()
                ->comment('Contact phone number of the wasiyyah beneficiary');

            $table->text('beneficiary_address')
                ->nullable()
                ->comment('Residential address of the wasiyyah beneficiary');

            $table->string('beneficiary_gender', 10)
                ->nullable()
                ->comment('Gender of the beneficiary (male/female)');

            $table->date('beneficiary_date_of_birth')
                ->nullable()
                ->comment('Date of birth of the beneficiary');

            // =================================================================
            // RELATIONSHIP INFORMATION
            // =================================================================
            $table->string('relationship')
                ->comment('Relationship of beneficiary to deceased (e.g., friend, charity, mosque, school, non-heir relative)');

            $table->string('beneficiary_relationship_type', 50)
                ->nullable()
                ->comment('Type of relationship: family, friend, charity, organization, other');

            // =================================================================
            // ORGANIZATION/CHARITY INFORMATION
            // =================================================================
            $table->string('beneficiary_organization_name')
                ->nullable()
                ->comment('Organization name if beneficiary is an organization/charity');

            $table->string('beneficiary_organization_registration_number')
                ->nullable()
                ->comment('Registration number if beneficiary is a registered organization');

            $table->boolean('is_charity')
                ->default(false)
                ->comment('Flag indicating if beneficiary is a charity/organization');

            $table->string('charity_registration_number')
                ->nullable()
                ->comment('Official registration number of the charity organization');

            $table->string('charity_tax_exempt_number')
                ->nullable()
                ->comment('Tax exemption number of the charity (if applicable)');

            // =================================================================
            // BENEFICIARY CLASSIFICATION FLAGS
            // =================================================================
            $table->boolean('is_non_muslim')
                ->default(false)
                ->comment('Flag indicating if beneficiary is non-Muslim (wasiyyah to non-Muslims has specific rules)');

            // =================================================================
            // BANK INFORMATION FOR DIRECT TRANSFERS
            // =================================================================
            $table->string('beneficiary_bank_name')
                ->nullable()
                ->comment('Bank name for direct transfer to beneficiary');

            $table->string('beneficiary_bank_account_number')
                ->nullable()
                ->comment('Bank account number for direct transfer');

            $table->string('beneficiary_bank_account_name')
                ->nullable()
                ->comment('Bank account holder name');

            // =================================================================
            // WASIYYAH ALLOCATION DETAILS
            // =================================================================
            $table->decimal('requested_percentage', 5, 2)
                ->comment('Percentage of net estate requested for this wasiyyah (max 33.33% total)');

            $table->decimal('approved_percentage', 5, 2)
                ->nullable()
                ->comment('Final approved percentage after review');

            $table->string('allocation_type', 50)
                ->nullable()
                ->default('percentage')
                ->comment('Type of allocation: percentage, fixed_amount, specific_asset');

            $table->text('allocated_asset_description')
                ->nullable()
                ->comment('Description of specific asset allocated to this beneficiary');

            $table->decimal('allocated_asset_value', 15, 2)
                ->nullable()
                ->comment('Estimated value of the allocated asset');

            $table->text('description')
                ->nullable()
                ->comment('Additional description or notes about this wasiyyah');

            // =================================================================
            // EXECUTION PLANNING
            // =================================================================
            $table->integer('execution_priority')
                ->default(0)
                ->comment('Priority order for executing this wasiyyah (lower number = higher priority)');

            $table->string('priority_level', 20)
                ->nullable()
                ->default('medium')
                ->comment('Priority level: low, medium, high, critical');

            $table->text('execution_conditions')
                ->nullable()
                ->comment('Any conditions that must be met before this wasiyyah is executed');

            $table->date('execution_deadline')
                ->nullable()
                ->comment('Deadline for executing this wasiyyah after death');

            $table->integer('execution_grace_period_days')
                ->nullable()
                ->default(90)
                ->comment('Grace period in days for executing this wasiyyah');

            // =================================================================
            // REVOCATION TRACKING
            // =================================================================
            $table->boolean('is_revoked')
                ->default(false)
                ->comment('Flag indicating if this wasiyyah has been revoked');

            $table->timestamp('revoked_at')
                ->nullable()
                ->comment('Timestamp when this wasiyyah was revoked');

            $table->text('revoked_reason')
                ->nullable()
                ->comment('Reason for revoking this wasiyyah');

            // =================================================================
            // EXECUTION TRACKING
            // =================================================================
            $table->boolean('is_executed')
                ->default(false)
                ->comment('Flag indicating if this wasiyyah has been executed');

            $table->timestamp('executed_at')
                ->nullable()
                ->comment('Timestamp when this wasiyyah was executed');

            $table->unsignedBigInteger('executed_by')
                ->nullable()
                ->comment('User ID of the person who executed this wasiyyah');

            $table->text('execution_notes')
                ->nullable()
                ->comment('Notes about the execution of this wasiyyah');

            $table->decimal('execution_amount', 15, 2)
                ->nullable()
                ->comment('Actual amount distributed for this wasiyyah');

            $table->string('execution_receipt_number')
                ->nullable()
                ->comment('Receipt or reference number for the wasiyyah execution');

            $table->string('execution_proof_document')
                ->nullable()
                ->comment('Path to proof document for wasiyyah execution');

            // =================================================================
            // WITNESS INFORMATION FOR EXECUTION
            // =================================================================
            $table->string('witness_name')
                ->nullable()
                ->comment('Name of witness for this wasiyyah execution');

            $table->string('witness_nric', 20)
                ->nullable()
                ->comment('NRIC/Passport of witness for this wasiyyah');

            $table->string('witness_phone', 20)
                ->nullable()
                ->comment('Phone number of witness for this wasiyyah');

            // =================================================================
            // REMINDER TRACKING
            // =================================================================
            $table->timestamp('last_reminder_sent_at')
                ->nullable()
                ->comment('Timestamp when last reminder was sent to trustee about this wasiyyah');

            $table->integer('reminder_count')
                ->default(0)
                ->comment('Number of reminders sent for this wasiyyah');

            // =================================================================
            // VERIFICATION TRACKING
            // =================================================================
            $table->string('verification_status', 20)
                ->nullable()
                ->default('pending')
                ->comment('Verification status: pending, verified, disputed, resolved');

            $table->text('verification_notes')
                ->nullable()
                ->comment('Notes about the verification process');

            $table->timestamp('verified_at')
                ->nullable()
                ->comment('Timestamp when this wasiyyah was verified');

            $table->unsignedBigInteger('verified_by')
                ->nullable()
                ->comment('User ID of person who verified this wasiyyah');

            // =================================================================
            // DISPUTE TRACKING
            // =================================================================
            $table->boolean('is_disputed')
                ->default(false)
                ->comment('Flag indicating if this wasiyyah is being disputed');

            $table->text('dispute_reason')
                ->nullable()
                ->comment('Reason for the dispute');

            $table->text('dispute_resolution')
                ->nullable()
                ->comment('Resolution details of the dispute');

            $table->timestamp('dispute_resolved_at')
                ->nullable()
                ->comment('Timestamp when the dispute was resolved');

            // =================================================================
            // METADATA
            // =================================================================
            $table->json('tags')
                ->nullable()
                ->comment('JSON array of tags for categorizing this wasiyyah');

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
            $table->index('estate_pre_registration_id', 'wasiyyah_estate_id_idx');

            // Beneficiary lookup indexes
            $table->index('beneficiary_email', 'wasiyyah_email_idx');
            $table->index('beneficiary_nric', 'wasiyyah_nric_idx');
            $table->index('beneficiary_phone', 'wasiyyah_phone_idx');
            $table->index('beneficiary_relationship_type', 'wasiyyah_rel_type_idx');

            // Status and flag indexes
            $table->index('is_executed', 'wasiyyah_executed_idx');
            $table->index('is_revoked', 'wasiyyah_revoked_idx');
            $table->index('is_disputed', 'wasiyyah_disputed_idx');
            $table->index('is_charity', 'wasiyyah_charity_idx');
            $table->index('is_non_muslim', 'wasiyyah_non_muslim_idx');
            $table->index('verification_status', 'wasiyyah_verify_status_idx');

            // Priority and planning indexes
            $table->index('execution_priority', 'wasiyyah_exec_priority_idx');
            $table->index('priority_level', 'wasiyyah_priority_level_idx');
            $table->index('allocation_type', 'wasiyyah_alloc_type_idx');

            // Date indexes
            $table->index('executed_at', 'wasiyyah_executed_at_idx');
            $table->index('revoked_at', 'wasiyyah_revoked_at_idx');

            // Composite indexes for common queries
            $table->index(
                ['is_executed', 'is_revoked', 'verification_status'], 
                'wasiyyah_status_composite_idx'
            );
            $table->index(
                ['priority_level', 'execution_priority'], 
                'wasiyyah_priority_composite_idx'
            );
            $table->index(
                ['executed_at', 'revoked_at', 'created_at'], 
                'wasiyyah_date_composite_idx'
            );
            $table->index(
                ['estate_pre_registration_id', 'is_executed', 'is_revoked'], 
                'wasiyyah_estate_status_idx'
            );

            // =================================================================
            // FOREIGN KEY CONSTRAINTS
            // =================================================================
            $table->foreign('executed_by', 'wasiyyah_executed_by_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('verified_by', 'wasiyyah_verified_by_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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

        // Drop foreign key constraints first
        Schema::table($this->table, function (Blueprint $table) {
            $foreignKeys = [
                'wasiyyah_executed_by_fk',
                'wasiyyah_verified_by_fk',
            ];

            foreach ($foreignKeys as $foreignKey) {
                if ($this->hasForeignKey($foreignKey)) {
                    $table->dropForeign($foreignKey);
                }
            }
        });

        // Drop the table
        Schema::dropIfExists($this->table);

        Log::info("Migration rollback: Successfully dropped '{$this->table}' table.");
    }

    /**
     * Add any missing columns to an existing table.
     * This is called if the table already exists when migration runs.
     *
     * @return void
     */
    private function addMissingColumns(): void
    {
        $existingColumns = $this->getExistingColumns();
        $missingColumns = [];
        
        // Define all expected columns in order
        $expectedColumns = [
            'beneficiary_phone' => function (Blueprint $table) {
                $table->string('beneficiary_phone', 20)->nullable()->after('beneficiary_email')
                    ->comment('Contact phone number of the wasiyyah beneficiary');
            },
            'beneficiary_address' => function (Blueprint $table) {
                $table->text('beneficiary_address')->nullable()->after('beneficiary_phone')
                    ->comment('Residential address of the wasiyyah beneficiary');
            },
            'beneficiary_gender' => function (Blueprint $table) {
                $table->string('beneficiary_gender', 10)->nullable()->after('beneficiary_address')
                    ->comment('Gender of the beneficiary (male/female)');
            },
            'beneficiary_date_of_birth' => function (Blueprint $table) {
                $table->date('beneficiary_date_of_birth')->nullable()->after('beneficiary_gender')
                    ->comment('Date of birth of the beneficiary');
            },
            'beneficiary_relationship_type' => function (Blueprint $table) {
                $table->string('beneficiary_relationship_type', 50)->nullable()->after('beneficiary_date_of_birth')
                    ->comment('Type of relationship: family, friend, charity, organization, other');
            },
            'beneficiary_organization_name' => function (Blueprint $table) {
                $table->string('beneficiary_organization_name')->nullable()->after('beneficiary_relationship_type')
                    ->comment('Organization name if beneficiary is an organization/charity');
            },
            'beneficiary_organization_registration_number' => function (Blueprint $table) {
                $table->string('beneficiary_organization_registration_number')->nullable()->after('beneficiary_organization_name')
                    ->comment('Registration number if beneficiary is a registered organization');
            },
            'beneficiary_bank_name' => function (Blueprint $table) {
                $table->string('beneficiary_bank_name')->nullable()->after('beneficiary_organization_registration_number')
                    ->comment('Bank name for direct transfer to beneficiary');
            },
            'beneficiary_bank_account_number' => function (Blueprint $table) {
                $table->string('beneficiary_bank_account_number')->nullable()->after('beneficiary_bank_name')
                    ->comment('Bank account number for direct transfer');
            },
            'beneficiary_bank_account_name' => function (Blueprint $table) {
                $table->string('beneficiary_bank_account_name')->nullable()->after('beneficiary_bank_account_number')
                    ->comment('Bank account holder name');
            },
            'is_non_muslim' => function (Blueprint $table) {
                $table->boolean('is_non_muslim')->default(false)->after('beneficiary_bank_account_name')
                    ->comment('Flag indicating if beneficiary is non-Muslim');
            },
            'is_charity' => function (Blueprint $table) {
                $table->boolean('is_charity')->default(false)->after('is_non_muslim')
                    ->comment('Flag indicating if beneficiary is a charity/organization');
            },
            'charity_registration_number' => function (Blueprint $table) {
                $table->string('charity_registration_number')->nullable()->after('is_charity')
                    ->comment('Official registration number of the charity organization');
            },
            'charity_tax_exempt_number' => function (Blueprint $table) {
                $table->string('charity_tax_exempt_number')->nullable()->after('charity_registration_number')
                    ->comment('Tax exemption number of the charity (if applicable)');
            },
            'allocation_type' => function (Blueprint $table) {
                $table->string('allocation_type', 50)->nullable()->default('percentage')->after('approved_percentage')
                    ->comment('Type of allocation: percentage, fixed_amount, specific_asset');
            },
            'allocated_asset_description' => function (Blueprint $table) {
                $table->text('allocated_asset_description')->nullable()->after('allocation_type')
                    ->comment('Description of specific asset allocated to this beneficiary');
            },
            'allocated_asset_value' => function (Blueprint $table) {
                $table->decimal('allocated_asset_value', 15, 2)->nullable()->after('allocated_asset_description')
                    ->comment('Estimated value of the allocated asset');
            },
            'execution_priority' => function (Blueprint $table) {
                $table->integer('execution_priority')->default(0)->after('allocated_asset_value')
                    ->comment('Priority order for executing this wasiyyah');
            },
            'priority_level' => function (Blueprint $table) {
                $table->string('priority_level', 20)->nullable()->default('medium')->after('execution_priority')
                    ->comment('Priority level: low, medium, high, critical');
            },
            'execution_conditions' => function (Blueprint $table) {
                $table->text('execution_conditions')->nullable()->after('priority_level')
                    ->comment('Any conditions that must be met before this wasiyyah is executed');
            },
            'execution_deadline' => function (Blueprint $table) {
                $table->date('execution_deadline')->nullable()->after('execution_conditions')
                    ->comment('Deadline for executing this wasiyyah after death');
            },
            'execution_grace_period_days' => function (Blueprint $table) {
                $table->integer('execution_grace_period_days')->nullable()->default(90)->after('execution_deadline')
                    ->comment('Grace period in days for executing this wasiyyah');
            },
            'is_revoked' => function (Blueprint $table) {
                $table->boolean('is_revoked')->default(false)->after('execution_grace_period_days')
                    ->comment('Flag indicating if this wasiyyah has been revoked');
            },
            'revoked_at' => function (Blueprint $table) {
                $table->timestamp('revoked_at')->nullable()->after('is_revoked')
                    ->comment('Timestamp when this wasiyyah was revoked');
            },
            'revoked_reason' => function (Blueprint $table) {
                $table->text('revoked_reason')->nullable()->after('revoked_at')
                    ->comment('Reason for revoking this wasiyyah');
            },
            'is_executed' => function (Blueprint $table) {
                $table->boolean('is_executed')->default(false)->after('revoked_reason')
                    ->comment('Flag indicating if this wasiyyah has been executed');
            },
            'executed_at' => function (Blueprint $table) {
                $table->timestamp('executed_at')->nullable()->after('is_executed')
                    ->comment('Timestamp when this wasiyyah was executed');
            },
            'executed_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('executed_by')->nullable()->after('executed_at')
                    ->comment('User ID of the person who executed this wasiyyah');
            },
            'execution_notes' => function (Blueprint $table) {
                $table->text('execution_notes')->nullable()->after('executed_by')
                    ->comment('Notes about the execution of this wasiyyah');
            },
            'execution_amount' => function (Blueprint $table) {
                $table->decimal('execution_amount', 15, 2)->nullable()->after('execution_notes')
                    ->comment('Actual amount distributed for this wasiyyah');
            },
            'execution_receipt_number' => function (Blueprint $table) {
                $table->string('execution_receipt_number')->nullable()->after('execution_amount')
                    ->comment('Receipt or reference number for the wasiyyah execution');
            },
            'execution_proof_document' => function (Blueprint $table) {
                $table->string('execution_proof_document')->nullable()->after('execution_receipt_number')
                    ->comment('Path to proof document for wasiyyah execution');
            },
            'witness_name' => function (Blueprint $table) {
                $table->string('witness_name')->nullable()->after('execution_proof_document')
                    ->comment('Name of witness for this wasiyyah execution');
            },
            'witness_nric' => function (Blueprint $table) {
                $table->string('witness_nric', 20)->nullable()->after('witness_name')
                    ->comment('NRIC/Passport of witness for this wasiyyah');
            },
            'witness_phone' => function (Blueprint $table) {
                $table->string('witness_phone', 20)->nullable()->after('witness_nric')
                    ->comment('Phone number of witness for this wasiyyah');
            },
            'last_reminder_sent_at' => function (Blueprint $table) {
                $table->timestamp('last_reminder_sent_at')->nullable()->after('witness_phone')
                    ->comment('Timestamp when last reminder was sent to trustee');
            },
            'reminder_count' => function (Blueprint $table) {
                $table->integer('reminder_count')->default(0)->after('last_reminder_sent_at')
                    ->comment('Number of reminders sent for this wasiyyah');
            },
            'verification_status' => function (Blueprint $table) {
                $table->string('verification_status', 20)->nullable()->default('pending')->after('reminder_count')
                    ->comment('Verification status: pending, verified, disputed, resolved');
            },
            'verification_notes' => function (Blueprint $table) {
                $table->text('verification_notes')->nullable()->after('verification_status')
                    ->comment('Notes about the verification process');
            },
            'verified_at' => function (Blueprint $table) {
                $table->timestamp('verified_at')->nullable()->after('verification_notes')
                    ->comment('Timestamp when this wasiyyah was verified');
            },
            'verified_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at')
                    ->comment('User ID of person who verified this wasiyyah');
            },
            'tags' => function (Blueprint $table) {
                $table->json('tags')->nullable()->after('verified_by')
                    ->comment('JSON array of tags for categorizing this wasiyyah');
            },
            'is_disputed' => function (Blueprint $table) {
                $table->boolean('is_disputed')->default(false)->after('tags')
                    ->comment('Flag indicating if this wasiyyah is being disputed');
            },
            'dispute_reason' => function (Blueprint $table) {
                $table->text('dispute_reason')->nullable()->after('is_disputed')
                    ->comment('Reason for the dispute');
            },
            'dispute_resolution' => function (Blueprint $table) {
                $table->text('dispute_resolution')->nullable()->after('dispute_reason')
                    ->comment('Resolution details of the dispute');
            },
            'dispute_resolved_at' => function (Blueprint $table) {
                $table->timestamp('dispute_resolved_at')->nullable()->after('dispute_resolution')
                    ->comment('Timestamp when the dispute was resolved');
            },
        ];

        // Find which columns are missing
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

    /**
     * Add any missing indexes to an existing table.
     *
     * @return void
     */
    private function addMissingIndexes(): void
    {
        $requiredIndexes = [
            // Single column indexes
            'wasiyyah_estate_id_idx',
            'wasiyyah_email_idx',
            'wasiyyah_nric_idx',
            'wasiyyah_phone_idx',
            'wasiyyah_rel_type_idx',
            'wasiyyah_executed_idx',
            'wasiyyah_revoked_idx',
            'wasiyyah_disputed_idx',
            'wasiyyah_charity_idx',
            'wasiyyah_non_muslim_idx',
            'wasiyyah_verify_status_idx',
            'wasiyyah_exec_priority_idx',
            'wasiyyah_priority_level_idx',
            'wasiyyah_alloc_type_idx',
            'wasiyyah_executed_at_idx',
            'wasiyyah_revoked_at_idx',
            // Composite indexes
            'wasiyyah_status_composite_idx',
            'wasiyyah_priority_composite_idx',
            'wasiyyah_date_composite_idx',
            'wasiyyah_estate_status_idx',
        ];

        $missingIndexes = [];
        foreach ($requiredIndexes as $indexName) {
            if (!$this->hasIndex($indexName)) {
                $missingIndexes[] = $indexName;
            }
        }

        if (!empty($missingIndexes)) {
            $this->createMissingIndexes($missingIndexes);
            Log::info("Migration: Added " . count($missingIndexes) . " missing indexes to '{$this->table}' table.", [
                'indexes' => $missingIndexes,
            ]);
        } else {
            Log::info("Migration: Table '{$this->table}' already has all expected indexes.");
        }
    }

    /**
     * Create specific missing indexes.
     *
     * @param  array  $missingIndexes
     * @return void
     */
    private function createMissingIndexes(array $missingIndexes): void
    {
        Schema::table($this->table, function (Blueprint $table) use ($missingIndexes) {
            foreach ($missingIndexes as $indexName) {
                switch ($indexName) {
                    case 'wasiyyah_estate_id_idx':
                        if (Schema::hasColumn($this->table, 'estate_pre_registration_id')) {
                            $table->index('estate_pre_registration_id', $indexName);
                        }
                        break;

                    case 'wasiyyah_email_idx':
                        if (Schema::hasColumn($this->table, 'beneficiary_email')) {
                            $table->index('beneficiary_email', $indexName);
                        }
                        break;

                    case 'wasiyyah_nric_idx':
                        if (Schema::hasColumn($this->table, 'beneficiary_nric')) {
                            $table->index('beneficiary_nric', $indexName);
                        }
                        break;

                    case 'wasiyyah_phone_idx':
                        if (Schema::hasColumn($this->table, 'beneficiary_phone')) {
                            $table->index('beneficiary_phone', $indexName);
                        }
                        break;

                    case 'wasiyyah_rel_type_idx':
                        if (Schema::hasColumn($this->table, 'beneficiary_relationship_type')) {
                            $table->index('beneficiary_relationship_type', $indexName);
                        }
                        break;

                    case 'wasiyyah_executed_idx':
                        if (Schema::hasColumn($this->table, 'is_executed')) {
                            $table->index('is_executed', $indexName);
                        }
                        break;

                    case 'wasiyyah_revoked_idx':
                        if (Schema::hasColumn($this->table, 'is_revoked')) {
                            $table->index('is_revoked', $indexName);
                        }
                        break;

                    case 'wasiyyah_disputed_idx':
                        if (Schema::hasColumn($this->table, 'is_disputed')) {
                            $table->index('is_disputed', $indexName);
                        }
                        break;

                    case 'wasiyyah_charity_idx':
                        if (Schema::hasColumn($this->table, 'is_charity')) {
                            $table->index('is_charity', $indexName);
                        }
                        break;

                    case 'wasiyyah_non_muslim_idx':
                        if (Schema::hasColumn($this->table, 'is_non_muslim')) {
                            $table->index('is_non_muslim', $indexName);
                        }
                        break;

                    case 'wasiyyah_verify_status_idx':
                        if (Schema::hasColumn($this->table, 'verification_status')) {
                            $table->index('verification_status', $indexName);
                        }
                        break;

                    case 'wasiyyah_exec_priority_idx':
                        if (Schema::hasColumn($this->table, 'execution_priority')) {
                            $table->index('execution_priority', $indexName);
                        }
                        break;

                    case 'wasiyyah_priority_level_idx':
                        if (Schema::hasColumn($this->table, 'priority_level')) {
                            $table->index('priority_level', $indexName);
                        }
                        break;

                    case 'wasiyyah_alloc_type_idx':
                        if (Schema::hasColumn($this->table, 'allocation_type')) {
                            $table->index('allocation_type', $indexName);
                        }
                        break;

                    case 'wasiyyah_executed_at_idx':
                        if (Schema::hasColumn($this->table, 'executed_at')) {
                            $table->index('executed_at', $indexName);
                        }
                        break;

                    case 'wasiyyah_revoked_at_idx':
                        if (Schema::hasColumn($this->table, 'revoked_at')) {
                            $table->index('revoked_at', $indexName);
                        }
                        break;

                    case 'wasiyyah_status_composite_idx':
                        if (Schema::hasColumns($this->table, ['is_executed', 'is_revoked', 'verification_status'])) {
                            $table->index(['is_executed', 'is_revoked', 'verification_status'], $indexName);
                        }
                        break;

                    case 'wasiyyah_priority_composite_idx':
                        if (Schema::hasColumns($this->table, ['priority_level', 'execution_priority'])) {
                            $table->index(['priority_level', 'execution_priority'], $indexName);
                        }
                        break;

                    case 'wasiyyah_date_composite_idx':
                        if (Schema::hasColumns($this->table, ['executed_at', 'revoked_at'])) {
                            $columns = ['executed_at', 'revoked_at'];
                            if (Schema::hasColumn($this->table, 'created_at')) {
                                $columns[] = 'created_at';
                            }
                            $table->index($columns, $indexName);
                        }
                        break;

                    case 'wasiyyah_estate_status_idx':
                        if (Schema::hasColumns($this->table, ['estate_pre_registration_id', 'is_executed', 'is_revoked'])) {
                            $table->index(['estate_pre_registration_id', 'is_executed', 'is_revoked'], $indexName);
                        }
                        break;
                }
            }
        });
    }

    /**
     * Add any missing foreign key constraints.
     *
     * @return void
     */
    private function addMissingForeignKeys(): void
    {
        // Check and add executed_by foreign key
        if (Schema::hasColumn($this->table, 'executed_by') && 
            Schema::hasTable('users') && 
            !$this->hasForeignKey('wasiyyah_executed_by_fk')) {
            
            Schema::table($this->table, function (Blueprint $table) {
                $table->foreign('executed_by', 'wasiyyah_executed_by_fk')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });

            Log::info("Migration: Added foreign key 'wasiyyah_executed_by_fk' to '{$this->table}' table.");
        }

        // Check and add verified_by foreign key
        if (Schema::hasColumn($this->table, 'verified_by') && 
            Schema::hasTable('users') && 
            !$this->hasForeignKey('wasiyyah_verified_by_fk')) {
            
            Schema::table($this->table, function (Blueprint $table) {
                $table->foreign('verified_by', 'wasiyyah_verified_by_fk')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });

            Log::info("Migration: Added foreign key 'wasiyyah_verified_by_fk' to '{$this->table}' table.");
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
            
            return [];
            
        } catch (\Exception $e) {
            Log::warning("Migration: Failed to get existing columns for table '{$this->table}'.", [
                'error' => $e->getMessage(),
            ]);
            return [];
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

    /**
     * Check if a foreign key constraint exists.
     *
     * @param  string  $foreignKeyName
     * @return bool
     */
    private function hasForeignKey(string $foreignKeyName): bool
    {
        try {
            $connection = Schema::getConnection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                $databaseName = $connection->getDatabaseName();
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                     WHERE TABLE_SCHEMA = ? 
                     AND TABLE_NAME = ? 
                     AND CONSTRAINT_NAME = ? 
                     AND REFERENCED_TABLE_NAME IS NOT NULL",
                    [$databaseName, $this->table, $foreignKeyName]
                );
                
                return !empty($result) && $result[0]->count > 0;
            } elseif ($driver === 'pgsql') {
                $result = DB::select(
                    "SELECT COUNT(1) as count 
                     FROM pg_constraint 
                     WHERE conname = ? 
                     AND contype = 'f'",
                    [$foreignKeyName]
                );
                
                return !empty($result) && $result[0]->count > 0;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::warning("Migration: Failed to check foreign key existence: {$foreignKeyName}", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
};
<?php
// database/migrations/2026_05_12_000003_fix_notification_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if table exists
        if (!Schema::hasTable('notification_requests')) {
            // Create the full table if it doesn't exist
            Schema::create('notification_requests', function (Blueprint $table) {
                $table->id();
                $table->string('session_id', 36)->nullable();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('recipient_email');
                $table->string('recipient_name')->nullable();
                $table->string('status')->default('pending_admin_approval');
                $table->string('access_token', 100)->nullable();
                $table->string('deceased_name')->nullable();
                $table->string('deceased_nric', 20)->nullable();
                $table->date('death_date')->nullable();
                $table->string('death_place')->nullable();
                $table->timestamp('requested_at')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('rejection_reason')->nullable();
                $table->json('request_metadata')->nullable();
                $table->timestamps();
                
                // Indexes
                $table->index('session_id');
                $table->index('user_id');
                $table->index('status');
                $table->index('recipient_email');
                $table->index('access_token');
            });
            
            return;
        }
        
        // Get existing columns
        $existingColumns = $this->getExistingColumns('notification_requests');
        
        // Add missing columns one by one with checks
        Schema::table('notification_requests', function (Blueprint $table) use ($existingColumns) {
            // Add user_id if missing
            if (!in_array('user_id', $existingColumns)) {
                $table->foreignId('user_id')->nullable()->after('session_id');
            }
            
            // Add requested_by if missing
            if (!in_array('requested_by', $existingColumns)) {
                $table->foreignId('requested_by')->nullable()->after('user_id');
            }
            
            // Add instant_estate_session_id if missing
            if (!in_array('instant_estate_session_id', $existingColumns)) {
                $table->foreignId('instant_estate_session_id')->nullable()->after('requested_by');
            }
            
            // Add estate_pre_registration_id if missing
            if (!in_array('estate_pre_registration_id', $existingColumns)) {
                $table->foreignId('estate_pre_registration_id')->nullable()->after('instant_estate_session_id');
            }
            
            // Add approved_by if missing
            if (!in_array('approved_by', $existingColumns)) {
                $table->foreignId('approved_by')->nullable()->after('approved_at');
            }
            
            // Add rejected_by if missing
            if (!in_array('rejected_by', $existingColumns)) {
                $table->foreignId('rejected_by')->nullable()->after('rejected_at');
            }
            
            // Add admin_notes if missing
            if (!in_array('admin_notes', $existingColumns)) {
                $table->text('admin_notes')->nullable()->after('rejected_by');
            }
            
            // Add notification_sent if missing
            if (!in_array('notification_sent', $existingColumns)) {
                $table->boolean('notification_sent')->default(false)->after('admin_notes');
            }
            
            // Add notification_sent_at if missing
            if (!in_array('notification_sent_at', $existingColumns)) {
                $table->timestamp('notification_sent_at')->nullable()->after('notification_sent');
            }
            
            // Add emails_sent if missing
            if (!in_array('emails_sent', $existingColumns)) {
                $table->boolean('emails_sent')->default(false)->after('notification_sent_at');
            }
            
            // Add email_status if missing
            if (!in_array('email_status', $existingColumns)) {
                $table->string('email_status')->default('pending')->after('emails_sent');
            }
            
            // Add email_results if missing
            if (!in_array('email_results', $existingColumns)) {
                $table->json('email_results')->nullable()->after('email_status');
            }
            
            // Add recipients_count if missing
            if (!in_array('recipients_count', $existingColumns)) {
                $table->integer('recipients_count')->default(0)->after('email_results');
            }
            
            // Add recipients_list if missing
            if (!in_array('recipients_list', $existingColumns)) {
                $table->json('recipients_list')->nullable()->after('recipients_count');
            }
            
            // Add verification_result if missing
            if (!in_array('verification_result', $existingColumns)) {
                $table->json('verification_result')->nullable()->after('recipients_list');
            }
            
            // Add metadata if missing
            if (!in_array('metadata', $existingColumns)) {
                $table->json('metadata')->nullable()->after('request_metadata');
            }
            
            // Add resend_count if missing
            if (!in_array('resend_count', $existingColumns)) {
                $table->integer('resend_count')->default(0)->after('metadata');
            }
            
            // Add last_resend_at if missing
            if (!in_array('last_resend_at', $existingColumns)) {
                $table->timestamp('last_resend_at')->nullable()->after('resend_count');
            }
            
            // Add error_message if missing
            if (!in_array('error_message', $existingColumns)) {
                $table->text('error_message')->nullable()->after('last_resend_at');
            }
            
            // Add pdf_content if missing
            if (!in_array('pdf_content', $existingColumns)) {
                $table->longText('pdf_content')->nullable()->after('error_message');
            }
        });
        
        // Add foreign keys after columns are added (only if they don't exist)
        $this->addForeignKeysIfNotExist();
        
        // Update existing records to set default values
        if (in_array('requested_at', $this->getExistingColumns('notification_requests'))) {
            DB::table('notification_requests')
                ->whereNull('requested_at')
                ->update(['requested_at' => DB::raw('created_at')]);
        }
        
        if (in_array('email_status', $this->getExistingColumns('notification_requests'))) {
            DB::table('notification_requests')
                ->whereNull('email_status')
                ->update(['email_status' => 'pending']);
        }
    }
    
    /**
     * Add foreign keys if they don't exist
     */
    private function addForeignKeysIfNotExist(): void
    {
        try {
            // Get existing foreign keys
            $foreignKeys = $this->getForeignKeys('notification_requests');
            
            Schema::table('notification_requests', function (Blueprint $table) use ($foreignKeys) {
                // Add user_id foreign key
                if (Schema::hasColumn('notification_requests', 'user_id') && 
                    !in_array('notification_requests_user_id_foreign', $foreignKeys)) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                }
                
                // Add requested_by foreign key
                if (Schema::hasColumn('notification_requests', 'requested_by') && 
                    !in_array('notification_requests_requested_by_foreign', $foreignKeys)) {
                    $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
                }
                
                // Add instant_estate_session_id foreign key
                if (Schema::hasColumn('notification_requests', 'instant_estate_session_id') && 
                    !in_array('notification_requests_instant_estate_session_id_foreign', $foreignKeys)) {
                    $table->foreign('instant_estate_session_id')->references('id')->on('instant_estate_sessions')->onDelete('set null');
                }
                
                // Add estate_pre_registration_id foreign key
                if (Schema::hasColumn('notification_requests', 'estate_pre_registration_id') && 
                    !in_array('notification_requests_estate_pre_registration_id_foreign', $foreignKeys)) {
                    $table->foreign('estate_pre_registration_id')->references('id')->on('estate_pre_registrations')->onDelete('set null');
                }
                
                // Add approved_by foreign key
                if (Schema::hasColumn('notification_requests', 'approved_by') && 
                    !in_array('notification_requests_approved_by_foreign', $foreignKeys)) {
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                }
                
                // Add rejected_by foreign key
                if (Schema::hasColumn('notification_requests', 'rejected_by') && 
                    !in_array('notification_requests_rejected_by_foreign', $foreignKeys)) {
                    $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
                }
            });
        } catch (\Exception $e) {
            Log::warning('Could not add foreign keys: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notification_requests')) {
            // Drop foreign keys first
            try {
                Schema::table('notification_requests', function (Blueprint $table) {
                    $foreignKeys = [
                        'notification_requests_user_id_foreign',
                        'notification_requests_requested_by_foreign',
                        'notification_requests_instant_estate_session_id_foreign',
                        'notification_requests_estate_pre_registration_id_foreign',
                        'notification_requests_approved_by_foreign',
                        'notification_requests_rejected_by_foreign',
                    ];
                    
                    foreach ($foreignKeys as $foreignKey) {
                        try {
                            $table->dropForeign($foreignKey);
                        } catch (\Exception $e) {
                            // Foreign key might not exist
                        }
                    }
                });
            } catch (\Exception $e) {
                // Ignore errors when dropping foreign keys
            }
            
            // Drop columns
            Schema::table('notification_requests', function (Blueprint $table) {
                $columns = [
                    'user_id', 'requested_by', 'instant_estate_session_id', 'estate_pre_registration_id',
                    'approved_by', 'rejected_by', 'admin_notes', 'notification_sent', 'notification_sent_at',
                    'emails_sent', 'email_status', 'email_results', 'recipients_count', 'recipients_list',
                    'verification_result', 'metadata', 'resend_count', 'last_resend_at', 'error_message', 'pdf_content'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('notification_requests', $column)) {
                        try {
                            $table->dropColumn($column);
                        } catch (\Exception $e) {
                            // Column might not exist
                        }
                    }
                }
            });
        }
    }
    
    /**
     * Get existing columns of a table
     */
    private function getExistingColumns(string $table): array
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                $databaseName = $connection->getDatabaseName();
                $results = DB::select(
                    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
                    [$databaseName, $table]
                );
                return array_map(fn($row) => $row->COLUMN_NAME, $results);
            } elseif ($driver === 'pgsql') {
                $results = DB::select(
                    "SELECT column_name FROM information_schema.columns WHERE table_name = ?",
                    [$table]
                );
                return array_map(fn($row) => $row->column_name, $results);
            } elseif ($driver === 'sqlite') {
                $results = DB::select("PRAGMA table_info({$table})");
                return array_map(fn($row) => $row->name, $results);
            }
            
            return Schema::getColumnListing($table);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get existing foreign keys of a table
     */
    private function getForeignKeys(string $table): array
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                $databaseName = $connection->getDatabaseName();
                $results = DB::select(
                    "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
                    [$databaseName, $table]
                );
                return array_map(fn($row) => $row->CONSTRAINT_NAME, $results);
            } elseif ($driver === 'pgsql') {
                $results = DB::select(
                    "SELECT conname FROM pg_constraint 
                     WHERE conrelid = ?::regclass AND contype = 'f'",
                    [$table]
                );
                return array_map(fn($row) => $row->conname, $results);
            }
            
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
};
<?php
// File: database/migrations/2026_04_13_000001_create_estate_pre_registrations_table.php
// This is the COMPLETE combined migration that creates the table with ALL columns
// and handles adding missing columns if the table already exists

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
    protected $table = 'estate_pre_registrations';

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
            
            // Add any missing foreign keys
            $this->addAllMissingForeignKeys();
            
            Log::info("Migration: All missing columns, indexes, and foreign keys added to '{$this->table}' table.");
            
            return;
        }

        Log::info("Migration: Creating '{$this->table}' table with ALL columns.");

        Schema::create($this->table, function (Blueprint $table) {
            // =================================================================
            // PRIMARY KEY
            // =================================================================
            $table->id();

            // =================================================================
            // FOREIGN KEY - USER RELATIONSHIP
            // =================================================================
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade')
                ->comment('The user who created this estate pre-registration');

            // =================================================================
            // UNIQUE IDENTIFIER
            // =================================================================
            $table->string('unique_id', 36)
                ->unique()
                ->comment('UUID for secure public reference');

            // =================================================================
            // DECEASED (OWNER) PERSONAL INFORMATION
            // =================================================================
            $table->string('deceased_name')
                ->comment('Full name of the deceased person');

            $table->string('deceased_nric', 20)
                ->comment('NRIC/Passport number of the deceased');

            $table->date('date_of_birth')
                ->nullable()
                ->comment('Date of birth of the deceased');

            $table->enum('gender', ['male', 'female'])
                ->nullable()
                ->comment('Gender of the deceased');

            $table->string('contact_phone', 20)
                ->nullable()
                ->comment('Contact phone number');

            $table->string('contact_email')
                ->nullable()
                ->comment('Contact email address');

            $table->text('address')
                ->nullable()
                ->comment('Residential address of the deceased');

            // =================================================================
            // TRUSTEE INFORMATION
            // =================================================================
            $table->string('trustee_name')
                ->nullable()
                ->comment('Full name of the appointed trustee/wasi');

            $table->string('trustee_nric', 20)
                ->nullable()
                ->comment('NRIC/Passport of the trustee');

            $table->string('trustee_phone', 20)
                ->nullable()
                ->comment('Contact phone of the trustee');

            $table->string('trustee_email')
                ->nullable()
                ->comment('Email of the trustee for notifications');

            $table->string('trustee_relationship')
                ->nullable()
                ->comment('Relationship of trustee to deceased');

            $table->text('trustee_address')
                ->nullable()
                ->comment('Full residential address of the trustee');

            // =================================================================
            // ALTERNATE TRUSTEE INFORMATION
            // =================================================================
            $table->string('alternate_trustee_name')
                ->nullable()
                ->comment('Name of alternate trustee');

            $table->string('alternate_trustee_nric', 20)
                ->nullable()
                ->comment('NRIC/Passport of alternate trustee');

            $table->string('alternate_trustee_phone', 20)
                ->nullable()
                ->comment('Phone of alternate trustee');

            $table->string('alternate_trustee_email')
                ->nullable()
                ->comment('Email of alternate trustee');

            $table->string('alternate_trustee_relationship')
                ->nullable()
                ->comment('Relationship of alternate trustee to deceased');

            // =================================================================
            // ESTATE STATUS
            // =================================================================
            $table->enum('status', ['draft', 'completed', 'activated', 'executed'])
                ->default('draft')
                ->comment('Current status of the estate plan');

            $table->timestamp('completed_at')
                ->nullable()
                ->comment('Timestamp when estate was marked as completed');

            $table->timestamp('activated_at')
                ->nullable()
                ->comment('Timestamp when estate was activated');

            $table->timestamp('executed_at')
                ->nullable()
                ->comment('Timestamp when estate was executed');

            // =================================================================
            // WILL VIDEO
            // =================================================================
            $table->string('will_video_url')
                ->nullable()
                ->comment('URL to the will video (uploaded or YouTube)');

            $table->string('will_video_type')
                ->nullable()
                ->comment('Type of will video: upload or youtube');

            $table->string('will_video_thumbnail')
                ->nullable()
                ->comment('Thumbnail for the will video');

            $table->text('will_text_content')
                ->nullable()
                ->comment('Written will content');

            // =================================================================
            // INSTRUCTIONS
            // =================================================================
            $table->text('wasiyyah_instructions')
                ->nullable()
                ->comment('General instructions for wasiyyah/will execution');

            $table->text('special_instructions')
                ->nullable()
                ->comment('Special instructions or conditions for estate distribution');

            $table->text('funeral_instructions')
                ->nullable()
                ->comment('Instructions for funeral arrangements');

            // =================================================================
            // EMERGENCY CONTACT
            // =================================================================
            $table->string('emergency_contact_name')
                ->nullable()
                ->comment('Name of emergency contact person');

            $table->string('emergency_contact_phone', 20)
                ->nullable()
                ->comment('Phone number of emergency contact');

            $table->string('emergency_contact_relationship')
                ->nullable()
                ->comment('Relationship of emergency contact to deceased');

            // =================================================================
            // ESTATE VALUATION
            // =================================================================
            $table->date('estate_valuation_date')
                ->nullable()
                ->comment('Date when estate assets were last valued');

            $table->text('estate_valuation_notes')
                ->nullable()
                ->comment('Notes about estate valuation');

            // =================================================================
            // LAWYER INFORMATION
            // =================================================================
            $table->string('lawyer_name')
                ->nullable()
                ->comment('Name of the lawyer handling the estate');

            $table->string('lawyer_contact')
                ->nullable()
                ->comment('Contact information for the lawyer');

            $table->string('lawyer_firm')
                ->nullable()
                ->comment('Law firm name');

            // =================================================================
            // WITNESS INFORMATION
            // =================================================================
            $table->string('witness_name')
                ->nullable()
                ->comment('Name of the will witness');

            $table->string('witness_nric', 20)
                ->nullable()
                ->comment('NRIC/Passport of the will witness');

            $table->string('witness_phone', 20)
                ->nullable()
                ->comment('Phone number of the will witness');

            // =================================================================
            // SHARIAH COMPLIANCE
            // =================================================================
            $table->boolean('is_shariah_compliant')
                ->default(true)
                ->comment('Flag indicating if estate follows Shariah compliance');

            $table->string('shariah_advisor_name')
                ->nullable()
                ->comment('Name of Shariah advisor consulted');

            $table->string('shariah_advisor_contact')
                ->nullable()
                ->comment('Contact for Shariah advisor');

            // =================================================================
            // NOTES AND REVIEW
            // =================================================================
            $table->text('estate_notes')
                ->nullable()
                ->comment('General notes about the estate');

            $table->timestamp('reviewed_at')
                ->nullable()
                ->comment('Timestamp when estate was last reviewed');

            $table->unsignedBigInteger('reviewed_by')
                ->nullable()
                ->comment('User ID of person who last reviewed the estate');

            // =================================================================
            // SECURITY & ACCESS
            // =================================================================
            $table->string('access_token', 64)
                ->unique()
                ->nullable()
                ->comment('Access token for secure will viewing');

            $table->timestamp('token_expires_at')
                ->nullable()
                ->comment('Expiration timestamp for access token');

            // =================================================================
            // ADMIN APPROVAL
            // =================================================================
            $table->boolean('admin_approved')
                ->default(false)
                ->comment('Whether the estate has been approved by admin');

            $table->timestamp('admin_approved_at')
                ->nullable()
                ->comment('Timestamp when admin approved the estate');

            $table->unsignedBigInteger('admin_approved_by')
                ->nullable()
                ->comment('User ID of admin who approved');

            $table->text('admin_notes')
                ->nullable()
                ->comment('Notes from admin regarding this estate');

            // =================================================================
            // REJECTION FIELDS
            // =================================================================
            $table->text('rejection_reason')
                ->nullable()
                ->comment('Reason for rejection');

            $table->timestamp('rejected_at')
                ->nullable()
                ->comment('Timestamp when estate was rejected');

            $table->unsignedBigInteger('rejected_by')
                ->nullable()
                ->comment('User ID of admin who rejected');

            // =================================================================
            // NOTIFICATION REQUEST
            // =================================================================
            $table->boolean('notification_requested')
                ->default(false)
                ->comment('Whether notification has been requested');

            $table->timestamp('notification_requested_at')
                ->nullable()
                ->comment('Timestamp when notification was requested');

            $table->boolean('notification_sent')
                ->default(false)
                ->comment('Whether notification emails have been sent');

            $table->timestamp('notification_sent_at')
                ->nullable()
                ->comment('Timestamp when notification emails were sent');

            // =================================================================
            // DATA LOCKING
            // =================================================================
            $table->boolean('data_locked')
                ->default(false)
                ->comment('Whether estate data is locked after admin approval');

            $table->timestamp('data_locked_at')
                ->nullable()
                ->comment('Timestamp when data was locked');

            $table->unsignedBigInteger('data_locked_by')
                ->nullable()
                ->comment('User ID who locked the data');

            $table->integer('data_version')
                ->default(1)
                ->comment('Version number for data integrity tracking');

            $table->string('data_hash')
                ->nullable()
                ->comment('SHA-256 hash of estate data for integrity verification');

            // =================================================================
            // PDF DISTRIBUTION
            // =================================================================
            $table->string('distribution_pdf_path')
                ->nullable()
                ->comment('Path to pre-generated distribution PDF');

            $table->timestamp('distribution_pdf_generated_at')
                ->nullable()
                ->comment('Timestamp when PDF was generated');

            $table->string('distribution_pdf_hash')
                ->nullable()
                ->comment('Hash of the generated PDF');

            $table->string('distribution_pdf_status')
                ->default('pending')
                ->comment('Status: pending, locked, released');

            $table->integer('distribution_pdf_version')
                ->default(1)
                ->comment('Version of the generated PDF');

            $table->timestamp('distribution_pdf_locked_at')
                ->nullable()
                ->comment('Timestamp when PDF was locked');

            $table->unsignedBigInteger('distribution_pdf_locked_by')
                ->nullable()
                ->comment('User ID who locked the PDF');

            $table->timestamp('distribution_pdf_released_at')
                ->nullable()
                ->comment('Timestamp when PDF was released');

            $table->unsignedBigInteger('distribution_pdf_released_by')
                ->nullable()
                ->comment('User ID who released the PDF');

            // =================================================================
            // VIDEO PROCESSING
            // =================================================================
            $table->string('will_video_processed_path')
                ->nullable()
                ->comment('Path to processed video file');

            $table->timestamp('will_video_processed_at')
                ->nullable()
                ->comment('Timestamp when video was processed');

            $table->string('will_video_processed_status')
                ->default('pending')
                ->comment('Status: pending, locked, released');

            $table->string('will_video_processed_hash')
                ->nullable()
                ->comment('Hash of processed video');

            $table->timestamp('will_video_released_at')
                ->nullable()
                ->comment('Timestamp when video was released');

            $table->unsignedBigInteger('will_video_released_by')
                ->nullable()
                ->comment('User ID who released the video');

            // =================================================================
            // DOCUMENT RELEASE
            // =================================================================
            $table->boolean('documents_released')
                ->default(false)
                ->comment('Whether documents have been released to beneficiaries');

            $table->timestamp('documents_released_at')
                ->nullable()
                ->comment('Timestamp when documents were released');

            $table->unsignedBigInteger('documents_released_by')
                ->nullable()
                ->comment('User ID who released documents');

            // =================================================================
            // ACCESS TOKENS FOR BENEFICIARIES
            // =================================================================
            $table->string('pdf_access_token')
                ->nullable()
                ->comment('Access token for PDF viewing');

            $table->timestamp('pdf_access_expires_at')
                ->nullable()
                ->comment('Expiration timestamp for PDF access');

            $table->string('video_access_token')
                ->nullable()
                ->comment('Access token for video viewing');

            $table->timestamp('video_access_expires_at')
                ->nullable()
                ->comment('Expiration timestamp for video access');

            // =================================================================
            // DEATH CERTIFICATE UPLOAD
            // =================================================================
            $table->string('death_certificate_url')
                ->nullable()
                ->comment('URL to uploaded death certificate');

            $table->text('ocr_extracted_data')
                ->nullable()
                ->comment('OCR extracted data from death certificate');

            $table->timestamp('death_certificate_uploaded_at')
                ->nullable()
                ->comment('Timestamp when death certificate was uploaded');

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
            $table->index('deceased_nric', 'estate_deceased_nric_idx');
            $table->index('status', 'estate_status_idx');
            $table->index('unique_id', 'estate_unique_id_idx');
            $table->index('user_id', 'estate_user_id_idx');
            $table->index('admin_approved', 'estate_admin_approved_idx');
            $table->index('trustee_email', 'estate_trustee_email_idx');
            $table->index('trustee_nric', 'estate_trustee_nric_idx');
            $table->index('is_shariah_compliant', 'estate_shariah_idx');
            $table->index('reviewed_at', 'estate_reviewed_at_idx');
            $table->index('lawyer_name', 'estate_lawyer_name_idx');
            $table->index('notification_requested', 'estate_notif_req_idx');
            $table->index('notification_sent', 'estate_notif_sent_idx');
            $table->index('data_locked', 'estate_data_locked_idx');
            $table->index('documents_released', 'estate_docs_released_idx');

            // =================================================================
            // FOREIGN KEYS
            // =================================================================
            $table->foreign('reviewed_by', 'estate_reviewed_by_fk')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        Log::info("Migration: Successfully created '{$this->table}' table with ALL columns, indexes, and foreign keys.");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (!Schema::hasTable($this->table)) {
            Log::warning("Migration rollback: Table '{$this->table}' does not exist. Skipping.");
            return;
        }

        Log::info("Migration rollback: Dropping '{$this->table}' table.");

        // Drop foreign key constraints first
        Schema::table($this->table, function (Blueprint $table) {
            $foreignKeys = [
                'estate_reviewed_by_fk',
            ];

            foreach ($foreignKeys as $foreignKey) {
                if ($this->hasForeignKey($foreignKey)) {
                    $table->dropForeign($foreignKey);
                }
            }
        });

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
            // Trustee Information
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

            // Alternate Trustee
            'alternate_trustee_name' => function (Blueprint $table) {
                $table->string('alternate_trustee_name')->nullable()->after('trustee_address')
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

            // Instructions
            'wasiyyah_instructions' => function (Blueprint $table) {
                $table->text('wasiyyah_instructions')->nullable()->after('will_text_content')
                    ->comment('General instructions for wasiyyah/will execution');
            },
            'special_instructions' => function (Blueprint $table) {
                $table->text('special_instructions')->nullable()->after('wasiyyah_instructions')
                    ->comment('Special instructions or conditions for estate distribution');
            },
            'funeral_instructions' => function (Blueprint $table) {
                $table->text('funeral_instructions')->nullable()->after('special_instructions')
                    ->comment('Instructions for funeral arrangements');
            },

            // Emergency Contact
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

            // Estate Valuation
            'estate_valuation_date' => function (Blueprint $table) {
                $table->date('estate_valuation_date')->nullable()->after('emergency_contact_relationship')
                    ->comment('Date when estate assets were last valued');
            },
            'estate_valuation_notes' => function (Blueprint $table) {
                $table->text('estate_valuation_notes')->nullable()->after('estate_valuation_date')
                    ->comment('Notes about estate valuation');
            },

            // Lawyer Information
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

            // Witness Information
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

            // Shariah Compliance
            'is_shariah_compliant' => function (Blueprint $table) {
                $table->boolean('is_shariah_compliant')->default(true)->after('witness_phone')
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

            // Notes and Review
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

            // Admin Approval
            'admin_approved' => function (Blueprint $table) {
                $table->boolean('admin_approved')->default(false)->after('token_expires_at')
                    ->comment('Whether the estate has been approved by admin');
            },
            'admin_approved_at' => function (Blueprint $table) {
                $table->timestamp('admin_approved_at')->nullable()->after('admin_approved')
                    ->comment('Timestamp when admin approved the estate');
            },
            'admin_approved_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('admin_approved_by')->nullable()->after('admin_approved_at')
                    ->comment('User ID of admin who approved');
            },
            'admin_notes' => function (Blueprint $table) {
                $table->text('admin_notes')->nullable()->after('admin_approved_by')
                    ->comment('Notes from admin regarding this estate');
            },

            // Rejection Fields
            'rejection_reason' => function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('admin_notes')
                    ->comment('Reason for rejection');
            },
            'rejected_at' => function (Blueprint $table) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason')
                    ->comment('Timestamp when estate was rejected');
            },
            'rejected_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at')
                    ->comment('User ID of admin who rejected');
            },

            // Notification Request
            'notification_requested' => function (Blueprint $table) {
                $table->boolean('notification_requested')->default(false)->after('rejected_by')
                    ->comment('Whether notification has been requested');
            },
            'notification_requested_at' => function (Blueprint $table) {
                $table->timestamp('notification_requested_at')->nullable()->after('notification_requested')
                    ->comment('Timestamp when notification was requested');
            },
            'notification_sent' => function (Blueprint $table) {
                $table->boolean('notification_sent')->default(false)->after('notification_requested_at')
                    ->comment('Whether notification emails have been sent');
            },
            'notification_sent_at' => function (Blueprint $table) {
                $table->timestamp('notification_sent_at')->nullable()->after('notification_sent')
                    ->comment('Timestamp when notification emails were sent');
            },

            // Data Locking
            'data_locked' => function (Blueprint $table) {
                $table->boolean('data_locked')->default(false)->after('notification_sent_at')
                    ->comment('Whether estate data is locked after admin approval');
            },
            'data_locked_at' => function (Blueprint $table) {
                $table->timestamp('data_locked_at')->nullable()->after('data_locked')
                    ->comment('Timestamp when data was locked');
            },
            'data_locked_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('data_locked_by')->nullable()->after('data_locked_at')
                    ->comment('User ID who locked the data');
            },
            'data_version' => function (Blueprint $table) {
                $table->integer('data_version')->default(1)->after('data_locked_by')
                    ->comment('Version number for data integrity tracking');
            },
            'data_hash' => function (Blueprint $table) {
                $table->string('data_hash')->nullable()->after('data_version')
                    ->comment('SHA-256 hash of estate data for integrity verification');
            },

            // PDF Distribution
            'distribution_pdf_path' => function (Blueprint $table) {
                $table->string('distribution_pdf_path')->nullable()->after('data_hash')
                    ->comment('Path to pre-generated distribution PDF');
            },
            'distribution_pdf_generated_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_generated_at')->nullable()->after('distribution_pdf_path')
                    ->comment('Timestamp when PDF was generated');
            },
            'distribution_pdf_hash' => function (Blueprint $table) {
                $table->string('distribution_pdf_hash')->nullable()->after('distribution_pdf_generated_at')
                    ->comment('Hash of the generated PDF');
            },
            'distribution_pdf_status' => function (Blueprint $table) {
                $table->string('distribution_pdf_status')->default('pending')->after('distribution_pdf_hash')
                    ->comment('Status: pending, locked, released');
            },
            'distribution_pdf_version' => function (Blueprint $table) {
                $table->integer('distribution_pdf_version')->default(1)->after('distribution_pdf_status')
                    ->comment('Version of the generated PDF');
            },
            'distribution_pdf_locked_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_locked_at')->nullable()->after('distribution_pdf_version')
                    ->comment('Timestamp when PDF was locked');
            },
            'distribution_pdf_locked_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('distribution_pdf_locked_by')->nullable()->after('distribution_pdf_locked_at')
                    ->comment('User ID who locked the PDF');
            },
            'distribution_pdf_released_at' => function (Blueprint $table) {
                $table->timestamp('distribution_pdf_released_at')->nullable()->after('distribution_pdf_locked_by')
                    ->comment('Timestamp when PDF was released');
            },
            'distribution_pdf_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('distribution_pdf_released_by')->nullable()->after('distribution_pdf_released_at')
                    ->comment('User ID who released the PDF');
            },

            // Video Processing
            'will_video_processed_path' => function (Blueprint $table) {
                $table->string('will_video_processed_path')->nullable()->after('distribution_pdf_released_by')
                    ->comment('Path to processed video file');
            },
            'will_video_processed_at' => function (Blueprint $table) {
                $table->timestamp('will_video_processed_at')->nullable()->after('will_video_processed_path')
                    ->comment('Timestamp when video was processed');
            },
            'will_video_processed_status' => function (Blueprint $table) {
                $table->string('will_video_processed_status')->default('pending')->after('will_video_processed_at')
                    ->comment('Status: pending, locked, released');
            },
            'will_video_processed_hash' => function (Blueprint $table) {
                $table->string('will_video_processed_hash')->nullable()->after('will_video_processed_status')
                    ->comment('Hash of processed video');
            },
            'will_video_released_at' => function (Blueprint $table) {
                $table->timestamp('will_video_released_at')->nullable()->after('will_video_processed_hash')
                    ->comment('Timestamp when video was released');
            },
            'will_video_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('will_video_released_by')->nullable()->after('will_video_released_at')
                    ->comment('User ID who released the video');
            },

            // Document Release
            'documents_released' => function (Blueprint $table) {
                $table->boolean('documents_released')->default(false)->after('will_video_released_by')
                    ->comment('Whether documents have been released to beneficiaries');
            },
            'documents_released_at' => function (Blueprint $table) {
                $table->timestamp('documents_released_at')->nullable()->after('documents_released')
                    ->comment('Timestamp when documents were released');
            },
            'documents_released_by' => function (Blueprint $table) {
                $table->unsignedBigInteger('documents_released_by')->nullable()->after('documents_released_at')
                    ->comment('User ID who released documents');
            },

            // Access Tokens
            'pdf_access_token' => function (Blueprint $table) {
                $table->string('pdf_access_token')->nullable()->after('documents_released_by')
                    ->comment('Access token for PDF viewing');
            },
            'pdf_access_expires_at' => function (Blueprint $table) {
                $table->timestamp('pdf_access_expires_at')->nullable()->after('pdf_access_token')
                    ->comment('Expiration timestamp for PDF access');
            },
            'video_access_token' => function (Blueprint $table) {
                $table->string('video_access_token')->nullable()->after('pdf_access_expires_at')
                    ->comment('Access token for video viewing');
            },
            'video_access_expires_at' => function (Blueprint $table) {
                $table->timestamp('video_access_expires_at')->nullable()->after('video_access_token')
                    ->comment('Expiration timestamp for video access');
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
            'estate_deceased_nric_idx' => 'deceased_nric',
            'estate_status_idx' => 'status',
            'estate_unique_id_idx' => 'unique_id',
            'estate_user_id_idx' => 'user_id',
            'estate_admin_approved_idx' => 'admin_approved',
            'estate_trustee_email_idx' => 'trustee_email',
            'estate_trustee_nric_idx' => 'trustee_nric',
            'estate_shariah_idx' => 'is_shariah_compliant',
            'estate_reviewed_at_idx' => 'reviewed_at',
            'estate_lawyer_name_idx' => 'lawyer_name',
            'estate_notif_req_idx' => 'notification_requested',
            'estate_notif_sent_idx' => 'notification_sent',
            'estate_data_locked_idx' => 'data_locked',
            'estate_docs_released_idx' => 'documents_released',
        ];

        Schema::table($this->table, function (Blueprint $table) use ($requiredIndexes) {
            foreach ($requiredIndexes as $indexName => $columnName) {
                if (!$this->hasIndex($indexName) && Schema::hasColumn($this->table, $columnName)) {
                    $table->index($columnName, $indexName);
                    Log::info("Migration: Added index '{$indexName}' to '{$this->table}'.");
                }
            }
        });
    }

    // =========================================================================
    // ADD ALL MISSING FOREIGN KEYS
    // =========================================================================

    /**
     * Add ALL possible missing foreign key constraints.
     *
     * @return void
     */
    private function addAllMissingForeignKeys(): void
    {
        // Add reviewed_by foreign key
        if (Schema::hasColumn($this->table, 'reviewed_by') && 
            Schema::hasTable('users') && 
            !$this->hasForeignKey('estate_reviewed_by_fk')) {
            
            Schema::table($this->table, function (Blueprint $table) {
                $table->foreign('reviewed_by', 'estate_reviewed_by_fk')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });

            Log::info("Migration: Added foreign key 'estate_reviewed_by_fk' to '{$this->table}'.");
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
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Neo Faraid database...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear tables in correct order (child tables first, then parent tables)
        $this->clearTables();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create users
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@neofaraid.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone_number' => '0123456789',
            'whatsapp_number' => '0123456789',
            'email_verified_at' => Carbon::now(),
            'status' => 'active',
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $userId = DB::table('users')->insertGetId([
            'name' => 'User',
            'email' => 'user@neofaraid.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone_number' => '0123456789',
            'whatsapp_number' => '0123456789',
            'email_verified_at' => Carbon::now(),
            'status' => 'active',
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $this->command->info('Users created successfully!');
        $this->command->info('Admin ID: ' . $adminId);
        $this->command->info('User ID: ' . $userId);
        
        // Call other seeders - pass user IDs if needed
        $this->callSeeders($userId);
        
        $this->command->info('All seeders completed!');
    }
    
    /**
     * Clear tables in correct order to respect foreign key constraints
     */
    private function clearTables(): void
    {
        // Clear child tables first (tables with foreign keys)
        $this->truncateIfExists('feedback');
        $this->truncateIfExists('calculations');
        $this->truncateIfExists('faraid_calculation_rules');
        
        // Then clear parent tables
        $this->truncateIfExists('users');
        $this->truncateIfExists('faqs');
        
        // Clear any other tables
        $this->truncateIfExists('personal_access_tokens');
        $this->truncateIfExists('failed_jobs');
        $this->truncateIfExists('migrations');
        $this->truncateIfExists('password_reset_tokens');
    }
    
    /**
     * Helper method to truncate table only if it exists
     */
    private function truncateIfExists(string $table): void
    {
        if (Schema::hasTable($table)) {
            DB::table($table)->truncate();
            $this->command->info("✓ Truncated: {$table}");
        } else {
            $this->command->warn("✗ Table does not exist: {$table}");
        }
    }
    
    /**
     * Call other seeders
     */
    private function callSeeders(int $userId): void
    {
        // Run FAQSeeder
        if (class_exists(FAQSeeder::class)) {
            $this->command->info('Running FAQSeeder...');
            $this->call(FAQSeeder::class);
        }
        
        // Run FeedbackSeeder with user ID
        if (class_exists(FeedbackSeeder::class)) {
            $this->command->info('Running FeedbackSeeder...');
            $this->call(FeedbackSeeder::class, [$userId]);
        }
        
        // Run FaraidCalculationRulesSeeder
        if (class_exists(FaraidCalculationRulesSeeder::class)) {
            $this->command->info('Running FaraidCalculationRulesSeeder...');
            $this->call(FaraidCalculationRulesSeeder::class);
        }
        
        // Run CalculationsSeeder with user IDs
        if (class_exists(CalculationsSeeder::class)) {
            $this->command->info('Running CalculationsSeeder...');
            $this->call(CalculationsSeeder::class, [$userId]);
        }
        
        // Run FamilyTreeTestSeeder if exists
        if (class_exists(FamilyTreeTestSeeder::class)) {
            $this->command->info('Running FamilyTreeTestSeeder...');
            $this->call(FamilyTreeTestSeeder::class);
        }
    }
}
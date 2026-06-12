<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FixUserPasswords extends Command
{
    protected $signature = 'users:fix-passwords 
                            {--default=password123 : Default password to set}
                            {--id=* : Specific user IDs to fix}
                            {--all : Fix all users}';
    
    protected $description = 'Fix password hashing for users';

    public function handle()
    {
        $this->info('Starting password fix...');

        $query = User::query();
        
        // Filter by IDs if provided
        if ($this->option('id')) {
            $query->whereIn('id', $this->option('id'));
        }
        
        $users = $query->get();
        $defaultPassword = $this->option('default');
        
        $fixedCount = 0;
        
        foreach ($users as $user) {
            if (!$this->isBcrypt($user->password)) {
                $user->password = Hash::make($defaultPassword);
                $user->save();
                
                $this->line("Fixed user: {$user->email} (ID: {$user->id})");
                Log::info('Fixed password via command', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                
                $fixedCount++;
            } else {
                $this->line("Skipped user (already bcrypt): {$user->email}");
            }
        }
        
        $this->info("Completed! Fixed {$fixedCount} user(s).");
        $this->info("Default password set to: {$defaultPassword}");
        $this->info("Users should reset their passwords after login.");
        
        return Command::SUCCESS;
    }
    
    private function isBcrypt($hash): bool
    {
        return strlen($hash) === 60 && 
               (substr($hash, 0, 4) === '$2y$' || 
                substr($hash, 0, 4) === '$2a$' ||
                substr($hash, 0, 4) === '$2b$');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding from SQL files...');

        $sqlFiles = [
            'seed_01_base.sql',
            'seed_02_vendor1_snackshack.sql',
            'seed_03_vendor2_dormbites.sql',
            'seed_04_vendor3_campuspantry.sql',
            'seed_05_vendor4_quickmart.sql',
            'seed_06_vendor5_freshhub.sql',
            'seed_07_relations.sql',
            'fix_passwords.sql',
        ];

        foreach ($sqlFiles as $file) {
            $path = base_path("database/init/{$file}");
            if (file_exists($path)) {
                $this->command->info("Running {$file}...");
                $sql = file_get_contents($path);
                DB::unprepared($sql);
            } else {
                $this->command->warn("File {$file} not found at {$path}");
            }
        }

        $this->command->info('Running PowerShell script for placeholder images...');
        
        $psScriptPath = base_path('database/init/setup_placeholders.ps1');
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $result = Process::run("powershell -ExecutionPolicy Bypass -File \"{$psScriptPath}\"");
            
            if ($result->successful()) {
                $this->command->info('Placeholder images created successfully!');
                $this->command->line($result->output());
            } else {
                $this->command->error('Failed to run PowerShell script:');
                $this->command->error($result->errorOutput());
            }
        } else {
            $this->command->warn('PowerShell script skipped (not on Windows OS).');
        }

        $this->command->info('Database seeding completed!');
    }
}

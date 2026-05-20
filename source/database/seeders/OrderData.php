<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class EDPDemo extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $file = "seed_00_order_data.sql";
        $path = base_path("database/init/{$file}");
        if (file_exists($path)) {
            $this->command->info("Running {$file}...");
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        } else {
            $this->command->warn("File {$file} not found at {$path}");
        }

        $this->command->info('Database seeding completed!');
    }
}

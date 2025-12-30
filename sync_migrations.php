<?php

/**
 * Simple script to mark existing tables as migrated
 * Run this if you've manually created tables
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Syncing migrations with existing tables...\n\n";

// Get all migration files
$migrationFiles = glob(database_path('migrations/*.php'));
$migrations = [];

foreach ($migrationFiles as $file) {
    $filename = basename($file, '.php');
    $migrations[] = $filename;
}

// Get already recorded migrations
$recorded = DB::table('migrations')->pluck('migration')->toArray();

$marked = 0;
$skipped = 0;

foreach ($migrations as $migration) {
    if (in_array($migration, $recorded)) {
        $skipped++;
        continue;
    }
    
    // Try to extract table name from migration
    $tableName = null;
    if (preg_match('/create_([a-z_]+)_table/', $migration, $matches)) {
        $tableName = $matches[1];
    } elseif (preg_match('/create_(.+)/', $migration, $matches)) {
        $tableName = str_replace('_table', '', $matches[1]);
    }
    
    if ($tableName && Schema::hasTable($tableName)) {
        $batch = DB::table('migrations')->max('batch') ?? 0;
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $batch + 1,
        ]);
        echo "✓ Marked: {$migration} (table: {$tableName})\n";
        $marked++;
    } elseif (!$tableName) {
        // For column additions or other migrations, mark if users table exists (assuming basic setup is done)
        if (Schema::hasTable('users')) {
            $batch = DB::table('migrations')->max('batch') ?? 0;
            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $batch + 1,
            ]);
            echo "✓ Marked: {$migration} (assuming completed)\n";
            $marked++;
        }
    } else {
        echo "✗ Skipped: {$migration} (table not found: {$tableName})\n";
        $skipped++;
    }
}

echo "\n";
echo "Summary:\n";
echo "- Marked: {$marked}\n";
echo "- Skipped: {$skipped}\n";
echo "\nDone! You can now run 'php artisan migrate' safely.\n";


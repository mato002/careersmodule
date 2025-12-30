<?php

namespace App\Console\Commands;

use App\Services\SessionManagementService;
use Illuminate\Console\Command;

class CleanupExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired user sessions';

    /**
     * Execute the console command.
     */
    public function handle(SessionManagementService $sessionService): int
    {
        $this->info('Cleaning up expired sessions...');

        $deleted = $sessionService->cleanupExpiredSessions();

        if ($deleted > 0) {
            $this->info("Successfully deleted {$deleted} expired session(s).");
        } else {
            $this->info('No expired sessions found.');
        }

        return Command::SUCCESS;
    }
}

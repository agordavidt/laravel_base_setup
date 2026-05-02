<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArchiveSecurityLogs extends Command
{
    protected $signature   = 'security:archive-logs';
    protected $description = 'Move security log records older than the configured retention period to the archive table.';

    public function handle(): int
    {
        $days      = config('security.archive_after_days', 90);
        $cutoff    = now()->subDays($days);
        $batchSize = 500; // Process in batches to avoid locking the table
        $total     = 0;

        $this->info("Archiving security_logs records older than {$days} days ({$cutoff->toDateString()})...");

        do {
            // Fetch a batch of IDs to archive
            $ids = DB::table('security_logs')
                ->where('created_at', '<', $cutoff)
                ->limit($batchSize)
                ->pluck('id');

            if ($ids->isEmpty()) {
                break;
            }

            // Copy to archive table
            DB::table('security_logs_archive')
                ->insertUsing(
                    ['event', 'level', 'user_id', 'user_email', 'ip_address',
                     'user_agent', 'path', 'method', 'context', 'created_at', 'archived_at'],
                    DB::table('security_logs')
                        ->select(
                            'event', 'level', 'user_id', 'user_email', 'ip_address',
                            'user_agent', 'path', 'method', 'context', 'created_at',
                            DB::raw('NOW() as archived_at')
                        )
                        ->whereIn('id', $ids)
                );

            // Delete the archived batch from the main table
            DB::table('security_logs')->whereIn('id', $ids)->delete();

            $total += $ids->count();
            $this->line("  Archived {$total} records so far...");

        } while ($ids->count() === $batchSize);

        $this->info("Done. {$total} records archived.");

        return Command::SUCCESS;
    }
}
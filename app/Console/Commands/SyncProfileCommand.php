<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\SyncTaskExecution;
use App\Models\UpdateKind;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncProfileCommand extends Command
{
    protected $signature = 'dmx:sync-profile {name?}';
    protected $description = 'Execute sync profile by name';

    public function handle(): int
    {
        $startTime = microtime(true);
        $name = $this->argument('name') ?? config('sync.profile_name');



        $profile = Profile::where('name', $name)->first();

        if (is_null($profile)) {
            $this->error("❌ Profile '$name' not found.");
            return Command::FAILURE;
        }

        $logConnection = $profile->srcResource->log_connection;
        $startedAt = Carbon::now();
        $execution = SyncTaskExecution::on($logConnection)->create([
            'task_id' => null,
            'profile_id' => $profile->profile_id,
            'profile_name' => $profile->name,
            'source_db' => $profile->srcResource->getDbName(),
            'destination_db' => $profile->dstResource->getDbName(),
            'executed_records' => 0,
            'success_count' => 0,
            'fail_count' => 0,
            'status' => 'profile-started',
            'started_at' => $startedAt,
        ]);

        $totalCount = 0;
        $totalResult = ['success' => 0, 'failed' => 0];


        $this->info("▶️  Executing profile: {$name}");

        foreach ($profile->activeTasks as $task) {

            $execution = SyncTaskExecution::on($logConnection)->create([
                'task_id' => $task->task_id,
                'task_name' => $task->name,
                'source_db' => $task->profile->srcResource->getDbName(),
                'destination_db' => $task->profile->dstResource->getDbName(),
                'profile_id' => $task->profile_id,
                'profile_name' => $task->profile->name,
                'executed_records' => 0,
                'success_count' => 0,
                'fail_count' => 0,
                'status' => 'pending',
                'started_at' => now(),
            ]);

            if ($task->parent && !$task->parent->is_active) {
                $message = sprintf("⚠️  Parent task '%s' for '%s' is inactive, skipping.", $task->parent->name, $task->name);
                $this->warn($message);

                $execution->fill([
                    'status' => 'skipped',
                    'finished_at' => now(),
                    'error_message' => $message
                ])->save();
                continue;
            }
            if (!$task->is_active) {
                $message = sprintf("⚠️  Task '%s' is inactive, skipping.", $task->name);
                $this->warn($message);
                $execution->fill([
                    'status' => 'skipped',
                    'finished_at' => now(),
                    'error_message' => $message
                ])->save();
                continue;
            }
            if ($task->update_kind_id == UpdateKind::NO_DB_ACTION) {
                $message = sprintf("⚠️  Task '%s' has no DB action, skipping.", $task->name);
                $this->warn($message);
                $execution->fill([
                    'status' => 'skipped',
                    'finished_at' => now(),
                    'error_message' => $message
                ])->save();
                continue;
            }

            $taskStart = microtime(true);
            $results = $task->execute();
            $taskEnd = microtime(true);
            $count = $results['success'] + $results['failed'];
            $totalResult['success'] += $results['success'];
            $totalResult['failed'] += $results['failed'];
            $duration = number_format($taskEnd - $taskStart, 3);
            $this->info(sprintf("%s | ✅ %s → %d records in %s sec.", now()->format('H:i:s'), $task->name, $count, $duration));

            $totalCount += $count;
        }

        $execution = SyncTaskExecution::on($logConnection)->create([
            'task_id' => null,
            'task_name' => $name,
            'source_db' => $profile->srcResource->getDbName(),
            'destination_db' => $profile->dstResource->getDbName(),
            'profile_name' => $profile->name,
            'profile_id' => $profile->profile_id,
            'executed_records' => $totalCount,
            'success_count' => $totalResult['success'],
            'fail_count' => $totalResult['failed'],
            'status' => 'profile-completed',
            'started_at' => $startedAt,
            'finished_at' => now(),
        ]);

        $totalTime = number_format(microtime(true) - $startTime, 3);
        $this->info("-------------------------------------");
        $this->info("🎯 Profile '$name' completed: $totalCount records in $totalTime sec.");
        $this->info("=====================================");

        return Command::SUCCESS;
    }
}

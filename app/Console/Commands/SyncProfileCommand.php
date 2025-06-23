<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\SyncBatch;
use App\Models\SyncTaskExecution;
use App\Models\UpdateKind;
use App\Traits\MeasuresElapsedTime;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncProfileCommand extends Command
{
    use MeasuresElapsedTime;

    protected $signature = 'dmx:sync-profile {name?}';
    protected $description = 'Execute sync profile by name';

    private $logConnection;

    public function handle(): int
    {
        $this->startTimer();

        $name = $this->argument('name') ?? config('sync.profile_name');



        $profile = Profile::where('name', $name)->first();

        if (is_null($profile)) {
            $this->error("❌ Profile '$name' not found.");
            return Command::FAILURE;
        }

        $this->logConnection = $profile->source->log_connection;

        $startedAt = Carbon::now();

        $batch = SyncBatch::on($this->logConnection)->create([
            'profile_id' => $profile->profile_id,
            'profile_name' => $profile->name,
            'source_db' => $profile->source->getDbName(),
            'destination_db' => $profile->destination->getDbName(),
            'executed_records' => 0,
            'success_count' => 0,
            'fail_count' => 0,
            'status' => 'running',
            'started_at' => $startedAt,
        ]);
        $totalCount = 0;
        $totalResult = ['success' => 0, 'failed' => 0];


        $this->info("▶️  Executing profile: {$name}");

        foreach ($profile->activeTasks as $task) {

            if ($task->parent && !$task->parent->is_active) {
                $message = "🗂️ ☑️ Parent task '{$task->parent->name}' for '{$task->name}' is inactive, skipping.";
                $this->skipTaskExecution($task, $message, $batch->batch_id);
                continue;
            }
            if (!$task->is_active) {
                $message = "☑️ Task '{$task->name}' is inactive, skipping.";
                $this->skipTaskExecution($task, $message, $batch->batch_id);
                continue;
            }
            if ($task->update_kind_id == UpdateKind::NO_DB_ACTION) {
                $message = "↪️ Task '{$task->name}' has no DB action, skipping.";
                $this->skipTaskExecution($task, $message, $batch->batch_id);
                continue;
            }

            $results = $task->execute($batch->batch_id);
            $count = $results['success'] + $results['failed'];
            $totalResult['success'] += $results['success'];
            $totalResult['failed'] += $results['failed'];

            $this->info(sprintf("%s | ✅ %s → %d records in %s sec.", now()->format('H:i:s'), $task->name, $count, $this->elapsedSeconds()));

            $totalCount += $count;
        }



        $batch->fill([
            'executed_records' => $totalCount,
            'success_count' => $totalResult['success'],
            'fail_count' => $totalResult['failed'],
            'status' => 'completed',
            'finished_at' => now(),
            'elapsed_time_ms' => $this->elapsedMilliseconds(), // convert to milliseconds
        ])->save();


        $this->info("-------------------------------------");
        $this->info("🎯 Profile '$name' completed: $totalCount records in {$this->elapsedSeconds()} sec.");
        $this->info("=====================================");

        return Command::SUCCESS;
    }

    private function skipTaskExecution($task, string $message, ?int $batchId): void
    {
        $this->warn($message);

        SyncTaskExecution::on($this->logConnection)->create([
            'batch_id' => $batchId,
            'task_id' => $task->task_id,
            'task_name' => $task->name,
            'profile_id' => $task->profile_id,
            'profile_name' => $task->profile->name,
            'executed_records' => 0,
            'success_count' => 0,
            'fail_count' => 0,
            'status' => 'skipped',
            'started_at' => now(),
            'finished_at' => null,
            'error_message' => $message,
            'elapsed_time_ms' => 0,
        ]);
    }
}

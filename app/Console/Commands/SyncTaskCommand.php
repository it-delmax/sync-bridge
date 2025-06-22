<?php

namespace App\Console\Commands;

use App\Models\SyncTaskExecution;
use App\Models\Task;
use Illuminate\Console\Command;

class SyncTaskCommand extends Command
{
    protected $signature = 'dmx:sync-task
                            {task_id : ID of the task to execute}
                            {--range=* : Optional range in format from,to (e.g. 1000,2000)}
                            {--step=500 : Step size for record processing}';

    protected $description = 'Execute a sync task by ID, optionally using a record range with step chunks.';

    public function handle(): int
    {

        $startTime = microtime(true);

        $taskId = $this->argument('task_id');
        $range = $this->option('range');
        $step = (int)$this->option('step');

        $task = Task::find($taskId);

        if (!$task) {
            $this->error("❌ Task ID $taskId not found.");
            return Command::FAILURE;
        }
        $logConnection = $task->profile->srcResource->log_connection;

        $execution = SyncTaskExecution::on($logConnection)->create([
            'task_id' => $task->task_id,
            'task_name' => $task->name,
            'source_db' => $task->profile->srcResource->getDbName(),
            'destination_db' => $task->profile->dstResource->getDbName(),
            'profile_name' => $task->profile->name,
            'profile_id' => $task->profile_id,
            'executed_records' => 0,
            'success_count' => 0,
            'fail_count' => 0,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        $this->info("▶️  Executing task: {$task->name} [ID: {$task->task_id}]");

        $taskResult = count($range) === 2
            ? $this->executeInSteps($task, (int)$range[0], (int)$range[1], $step)
            : $task->execute();

        $totalCount = $taskResult['success'] + $taskResult['failed'];

        $execution->fill([
            'executed_records' => $taskResult['success'] + $taskResult['failed'],
            'success_count' => $taskResult['success'],
            'fail_count' => $taskResult['failed'],
            'status' => 'success',
            'finished_at' => now(),
        ])->save();

        $duration = number_format(microtime(true) - $startTime, 3);
        $this->info("✅ Total records processed: {$totalCount} in {$duration} seconds.");
        $this->info('=====================================================');

        return Command::SUCCESS;
    }

    protected function executeInSteps(Task $task, int $start, int $end, int $step): int
    {
        $this->info("Processing range: from {$start} to {$end} in steps of {$step}");

        $total = 0;
        for ($i = $start; $i <= $end; $i += $step) {
            $from = $i;
            $to = min($i + $step - 1, $end);

            $this->info("➡️  Executing batch: {$from} to {$to}");

            $batchStart = microtime(true);
            $count = $task->execute([$from, $to]);
            $elapsed = number_format(microtime(true) - $batchStart, 3);

            $this->info("✔️  Records processed: {$count} in {$elapsed} sec.");
            $total += $count;
        }

        return $total;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\SyncTaskExecution;
use App\Models\Task;
use App\Traits\MeasuresElapsedTime;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncTaskCommand extends Command
{
    use MeasuresElapsedTime;

    protected $signature = 'dmx:sync-task
                            {task_id : ID of the task to execute}
                            {--range=* : Optional range in format from,to (e.g. 1000,2000)}
                            {--step=500 : Step size for record processing}';

    protected $description = 'Execute a sync task by ID, optionally using a record range with step chunks.';

    public function handle(): int
    {

        $this->startTimer();

        $taskId = $this->argument('task_id');
        $range = $this->option('range');
        $step = (int)$this->option('step');

        $task = Task::with('profile')->find($taskId);

        if (!$task) {
            $this->error("❌ Task ID $taskId not found.");
            return Command::FAILURE;
        }

        $this->info("▶️  Executing task: {$task->name} [ID: {$task->task_id}]");

        $taskResult = count($range) === 2
            ? $this->executeInSteps($task, null, (int)$range[0], (int)$range[1], $step)
            : $task->execute();

        $totalCount = $taskResult['success'] + $taskResult['failed'];


        $duration = $this->elapsedSeconds();
        $this->info("✅ Total records processed: {$totalCount} in {$duration} seconds.");
        $this->info('=====================================================');

        return Command::SUCCESS;
    }

    protected function executeInSteps(Task $task, ?int $batchid, int $start, int $end, int $step): int
    {
        $this->info("Processing range: from {$start} to {$end} in steps of {$step}");

        $total = 0;
        for ($i = $start; $i <= $end; $i += $step) {
            $from = $i;
            $to = min($i + $step - 1, $end);

            $this->info("➡️  Executing batch: {$from} to {$to}");

            $batchStart = microtime(true);
            $count = $task->execute($batchid, [$from, $to]);
            $elapsed = number_format(microtime(true) - $batchStart, 3);

            $this->info("✔️  Records processed: {$count} in {$elapsed} sec.");
            $total += $count;
        }

        return $total;
    }
}

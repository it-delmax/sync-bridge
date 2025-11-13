<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\SyncBatch;
use App\Traits\MeasuresElapsedTime;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteSync2TablesCommand extends Command
{
    use MeasuresElapsedTime;

    protected $signature = 'dmx:delete-sync2-tables
                            {profile? : Ime profila za brisanje podataka iz *2 tabela, ako nije definisano, koristi se config.sync.profile_name})}
                            {--days=7 : Koliko dana unazad da zadržiš zapise (created_at)}';

    protected $description = 'Briše stare zapise iz *2 staging tabela za sync, starije od definisanog broja dana.';

    protected array $tables = [
        'country2',
        'description2',
        'description_dimension_template2',
        'dimension2',
        'dmx_vehicle_manufacturer2',
        'manufacturer2',
        'oem2',
        'packaging2',
        'product2',
        'product_dimension2',
        'product_group2',
        'stock2',
        'uom2',
        'sync_log',
    ];

    private $logConnection;

    public function handle(): int
    {
        $this->startTimer();

        $name = $this->argument('name') ?? config('sync.profile_name');

        $days = (int) $this->option('days');

        $profile = Profile::where('name', $name)->first();

        if (is_null($profile)) {
            $this->error("❌ Profile '$name' not found.");
            return Command::FAILURE;
        }

        $this->logConnection = $profile->destination->log_connection;

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

        $this->info("🧹 Brišem stare podatke iz tabela na konekciji: {$profile->destination->log_connection} (starije od {$days} dana)");
        $this->line('-----------------------------------------------------');

        foreach ($this->tables as $table) {
            $sql = "DELETE FROM `{$table}` WHERE DATEDIFF(CURDATE(), created_at) > ?";

            try {
                $executions = $batch->executions()->create([
                    'batch_id' => $batch->batch_id,
                    'task_id' => null,
                    'task_name' => "🗑️ Brisanje iz {$table} starijih od {$days} dana",
                    'profile_name' => $profile->name,
                    'profile_id' => $profile->profile_id,
                    'executed_records' => 0,
                    'success_count' => 0,
                    'fail_count' => 0,
                    'status' => 'running',
                    'started_at' => now(),
                ]);

                $deleted = DB::connection($profile->destination->log_connection)->affectingStatement($sql, [$days]);

                $executions->fill([
                    'executed_records' => $deleted,
                    'success_count' => $deleted,
                    'fail_count' => 0,
                    'status' => 'completed',
                    'started_at' => now(),
                    'finished_at' => now(),
                    'elapsed_time_ms' => $this->elapsedMilliseconds()
                ])->save();

                $this->info("🗑️  {$table}: obrisano {$deleted} zapisa za {$this->elapsedSeconds()}s");
            } catch (\Throwable $e) {
                $this->error("⚠️  Greška prilikom brisanja u tabeli {$table}: {$e->getMessage()}");
                $executions->fill([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'finished_at' => now(),
                    'elapsed_time_ms' => $this->elapsedMilliseconds()
                ])->save();
            }
        }

        $batch->fill([
            'executed_records' => $batch->executions()->sum('executed_records'),
            'success_count' => $batch->executions()->sum('success_count'),
            'fail_count' => $batch->executions()->sum('fail_count'),
            'status' => 'completed',
            'finished_at' => now(),
            'elapsed_time_ms' => $this->elapsedMilliseconds(),
        ])->save();

        $this->line('-----------------------------------------------------');
        $this->info("🏁 Gotovo. Ukupno vreme: {$this->elapsedMilliseconds()}s");

        return Command::SUCCESS;
    }
}

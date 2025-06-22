<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteSync2TablesCommand extends Command
{
    protected $signature = 'dmx:delete-sync2-tables
                            {connection : DB konekcija (npr. staging)}
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

    public function handle(): int
    {
        $startTime = microtime(true);
        $connection = $this->argument('connection') ?? config('sync.target_connection');
        $days = (int) $this->option('days');

        $this->info("🧹 Brišem stare podatke iz tabela na konekciji: {$connection} (starije od {$days} dana)");
        $this->line('-----------------------------------------------------');

        foreach ($this->tables as $table) {
            $sql = "DELETE FROM `{$table}` WHERE DATEDIFF(CURDATE(), created_at) > ?";
            $t0 = microtime(true);

            try {
                $deleted = DB::connection($connection)->affectingStatement($sql, [$days]);
                $elapsed = number_format(microtime(true) - $t0, 3);

                $this->info("🗑️  {$table}: obrisano {$deleted} zapisa za {$elapsed}s");
            } catch (\Throwable $e) {
                $this->error("⚠️  Greška prilikom brisanja u tabeli {$table}: {$e->getMessage()}");
            }
        }

        $total = number_format(microtime(true) - $startTime, 3);
        $this->line('-----------------------------------------------------');
        $this->info("🏁 Gotovo. Ukupno vreme: {$total}s");

        return Command::SUCCESS;
    }
}

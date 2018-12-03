<?php

namespace App\Console\Commands;

use App\Domains\CSV\CSVImport;
use Illuminate\Console\Command;

class SynchronizeProductCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize product CSVs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = new CSVImport();
        $service->synchronizeCSV();
    }
}

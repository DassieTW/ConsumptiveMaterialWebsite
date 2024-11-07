<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Laravel\Telescope\Contracts\PrunableRepository;

class TelescopePruneAllDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope:prune_alldb {--hours=24 : The number of hours to retain Telescope data} {--keep-exceptions : Retain exception data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune stale entries from the Telescope table for all databases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Telescope\Contracts\PrunableRepository  $repository
     * @return int
     */
    public function handle(PrunableRepository $repository)
    {
        $databaseArray = config('database_list.databases');
        try {
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                $this->info($site . ": " . $repository->prune(now()->subHours($this->option('hours')), $this->option('keep-exceptions')) . ' entries pruned.');
            } // foreach
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

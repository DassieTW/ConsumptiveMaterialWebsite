<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class MigrateAllServerDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom command for migrating every database listed inside config/database_list.php';

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
     * @return int
     */
    public function handle()
    {
        $databaseArray = config('database_list.databases');
        try {
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                $this->warn("Now migrating: {$site}");
                $this->call('migrate');
            } // foreach
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    }
}

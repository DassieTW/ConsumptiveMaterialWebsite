<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class MigrateAllDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateAll:fresh {--seed}';

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
        if (strcmp(env('APP_ENV'), 'production') != 0) {
            $databaseArray = config('database_list.databases');
            try {
                foreach ($databaseArray as $site) {
                    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                    \DB::purge(env("DB_CONNECTION"));
                    $this->warn("Now migrating: {$site}");
                    if ($this->option('seed')) {
                        $this->call('migrate:fresh', [
                            '--seed' => true,
                        ]);
                    } else {
                        $this->call('migrate:fresh');
                    } // else
                } // foreach
            } catch (Exception $e) {
                $this->error("Command execution failed with error : " . $e->getMessage());
            } // try - catch
        } else {
            $this->error("Command execution failed with error : " . "Not Allowed When In Production Env.");
        } // else

        return 0;
    } // handle
}

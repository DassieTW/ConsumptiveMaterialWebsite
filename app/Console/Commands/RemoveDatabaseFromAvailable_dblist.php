<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class RemoveDatabaseFromAvailable_dblist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dblist:cleanup {DatabaseName}';
    // ex: php artisan dblist:cleanup "巴淡-LOT2"

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up available database list when the database is removed from the server.';

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
        $this->warn("Now Running [dblist:cleanup]");
        $databaseArray = config('database_list.databases');
        array_shift($databaseArray); // remove the 'Consumables management' db from array
        try {
            \Log::channel('dbquerys')->info('---------------------------清理available_dblist--------------------------');
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::statement("UPDATE [login] SET [available_dblist] = REPLACE([available_dblist], N'_" . $this->argument('DatabaseName') . "', '')");
                \DB::statement("UPDATE [login] SET [available_dblist] = REPLACE([available_dblist], N'" . $this->argument('DatabaseName') . "_', '')");
                \DB::table('login')
                    ->where('available_dblist', '=', "")
                    ->orWhereNull('available_dblist')
                    ->delete();
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------清理available_dblist結束--------------------------');
            $this->info("[dblist:cleanup] Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('dbquerys')->error("Database List Clean Up Command execution failed with error : " . $e->getMessage());
            $this->error("Database List Clean Up Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

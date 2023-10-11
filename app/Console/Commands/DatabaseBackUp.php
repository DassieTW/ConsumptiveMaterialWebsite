<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mssql:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup our production server regularly';

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
        // array_shift($databaseArray); // remove the 'Consumables management' db from array
        try {
            \Log::channel('dbquerys')->info('---------------------------備份資料庫開始--------------------------');
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', 'Consumables management');
            \DB::purge(env("DB_CONNECTION"));
            foreach ($databaseArray as $site) {
                \DB::statement("BACKUP DATABASE [" . $site .
                    "] TO DISK = N'/var/opt/mssql/data/" . $site . ".bak' WITH RETAINDAYS = 30, NOFORMAT, NOINIT," .
                    " NAME = N'" . $site . "-Full Database Backup', SKIP, NOREWIND, NOUNLOAD, STATS = 10;");
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------備份資料庫結束--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('dbquerys')->error("Backup MSSQL Command execution failed with error : " . $e->getMessage());
            $this->error("Backup MSSQL Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
} // class DatabaseBackUp

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class CleanUpInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete rows with 0 inventory and updated time older than 3 months.';

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
        array_shift($databaseArray); // remove the 'Consumables management' db from array
        try {
            \Log::channel('dbquerys')->info('---------------------------清理久未更新之0現有庫存條目--------------------------');
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::table('inventory')
                    ->whereDate('最後更新時間', '<=', \Carbon\Carbon::now()->modify('-3 months')->toDateTimeString())
                    ->where('現有庫存', '=', 0)
                    ->delete();
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------清理久未更新之0現有庫存條目結束--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('dbquerys')->error("Inventory Clear Command execution failed with error : " . $e->getMessage());
            $this->error("Inventory Clear Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\Login;

class CleanUpDatedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olduser:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up users that did not log in within the last 6 months.';

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
            \Log::channel('dbquerys')->info('---------------------------清理久未登入人員開始--------------------------');
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::table('login')
                    ->orWhereNull('last_login_time')
                    ->whereDate('last_login_time', '<=', \Carbon\Carbon::now()->modify('-6 months')->toDateTimeString())
                    ->delete();
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------清理久未登入人員結束--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('dbquerys')->error("Dated User Clear Command execution failed with error : " . $e->getMessage());
            $this->error("Dated User Clear Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

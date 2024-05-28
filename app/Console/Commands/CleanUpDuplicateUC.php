<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\Login;

class CleanUpDuplicateUC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uc:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up redundant unit consumption.';

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
            \Log::channel('dbquerys')->info('---------------------------清理重複的月請購_單耗--------------------------');
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::table('月請購_單耗')
                    ->raw(
                        'WITH cte AS (' .
                            'SELECT*,' .
                            'row_number() OVER(PARTITION BY [料號], [料號90] ORDER BY [送單時間] desc) AS [rn]' .
                            'FROM [月請購_單耗]) ' .
                            'Delete from cte WHERE [rn] > 1;'
                    );
            } // foreach

            \Log::channel('dbquerys')->info('---------------------------清理重複的月請購_單耗結束--------------------------');
            $this->info("Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('dbquerys')->error("Dated User Clear Command execution failed with error : " . $e->getMessage());
            $this->error("Dated User Clear Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
} // CleanUpDuplicateUC

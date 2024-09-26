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
    protected $signature = 'test:ssz {FlowNumber}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom command for testing SSZ database from MIS.';

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
        try {
            $this->info(
                \DB::connection('sqlsrv_ssztest')->table('[SAPBPM].[dbo].[V_SSZ_RelQtyInfo]')
                    ->where('FlowNumber', $this->argument('FlowNumber'))
                    ->get()
            );
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestSSZ extends Command
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
            \DB::connection('sqlsrv_ssztest')->table('V_SSZ_RelQtyInfo')
                ->where('FlowNumber', $this->argument('FlowNumber'))
                ->get();

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "HQ TEST Consumables management");
            \DB::purge(env("DB_CONNECTION"));
            $dbName = \DB::connection()->getDatabaseName(); // test
            $datetime = Carbon::now();
            $allRecords = \DB::connection('sqlsrv_ssztest')->table('V_SSZ_RelQtyInfo')
                ->where('FlowNumber', $this->argument('FlowNumber'))
                ->get();
            $allRecords_associative_array = array();
            foreach ($allRecords as $record) {
                // dd(gettype($record)); // test
                $temp = json_decode(json_encode($record), true);
                $temp['relQty'] = (int)$temp['relQty'];
                $allRecords_associative_array[] = $temp;
            } // foreach

            \DB::beginTransaction();

            // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
            $whole_load = array_chunk($allRecords_associative_array, 100, true);
            for ($i = 0; $i < count($whole_load); $i++) {
                $temp_record = \DB::table('SSZInfo')->upsert(
                    $whole_load[$i],
                    ['FlowNumber', 'MatShort'],
                    ['Applicant', 'relQty', 'MaterialType', 'Company', 'DeptManager1', 'CostDept', 'Spec', 'Keeper', 'SSZMemo']
                );
            } // for

            \DB::commit();
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    }
}

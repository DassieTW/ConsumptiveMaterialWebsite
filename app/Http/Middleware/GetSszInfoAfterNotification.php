<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class GetSszInfoAfterNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    } // handle

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if ($request->route()->named('SSZ')) {
            try {
                \Log::channel('dbquerys')->info('----------------------------SSZ Info Query to MIS---------------------------');
                \Log::channel('dbquerys')->info(json_encode($request->post()));

                $og_dbName = \DB::connection()->getDatabaseName();
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "Consumables management");
                \DB::purge(env("DB_CONNECTION"));
                $datetime = Carbon::now();

                $allRecords = \DB::connection('sqlsrv_ssz')->table('V_SSZ_RelQtyInfo')
                    ->where('FlowNumber', $request->input('FlowNumber'))
                    ->select(
                        'FlowNumber',
                        'MatShort',
                        'Applicant',
                        'MaterialType',
                        'Company',
                        'DeptManager1',
                        'CostDept',
                        'Spec',
                        'Keeper',
                        'SSZMemo',
                        \DB::raw('SUM(relQty) as relQty')
                    )
                    ->groupBy('FlowNumber', 'MatShort', 'Applicant', 'MaterialType', 'Company', 'DeptManager1', 'CostDept', 'Spec', 'Keeper', 'SSZMemo')
                    ->get();
                $allRecords_associative_array = array();
                foreach ($allRecords as $record) {
                    // dd(gettype($record)); // test
                    $temp = array();
                    $temp['FlowNumber'] = $record->FlowNumber;
                    $temp['MatShort'] = $record->MatShort;
                    $temp['Applicant'] = $record->Applicant;
                    $temp['MaterialType'] = $record->MaterialType;
                    $temp['Company'] = $record->Company;
                    $temp['DeptManager1'] = $record->DeptManager1;
                    $temp['CostDept'] = $record->CostDept;
                    $temp['Spec'] = $record->Spec;
                    $temp['Keeper'] = $record->Keeper;
                    $temp['SSZMemo'] = $record->SSZMemo;
                    $temp['relQty'] = (int)$record->relQty;
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
                \Log::channel('dbquerys')->error(json_encode($e));
                dd($e); // dump error
            } // try - catch

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $og_dbName);
            \DB::purge(env("DB_CONNECTION"));
            \Log::channel('dbquerys')->info('--------------------------SSZ Info Query to MIS END--------------------------');
        } else if ($request->route()->named('SSZ_Test')) {
            try {
                \Log::channel('dbquerys')->info('----------------------------SSZ Info Query to MIS TEST---------------------------');
                \Log::channel('dbquerys')->info(json_encode($request->post()));

                $og_dbName = \DB::connection()->getDatabaseName();
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', "HQ TEST Consumables management");
                \DB::purge(env("DB_CONNECTION"));
                $datetime = Carbon::now();

                $allRecords = \DB::connection('sqlsrv_ssztest')->table('V_SSZ_RelQtyInfo')
                    ->where('FlowNumber', $request->input('FlowNumber'))
                    ->select(
                        'FlowNumber',
                        'MatShort',
                        'Applicant',
                        'MaterialType',
                        'Company',
                        'DeptManager1',
                        'CostDept',
                        'Spec',
                        'Keeper',
                        'SSZMemo',
                        \DB::raw('SUM(relQty) as relQty')
                    )
                    ->groupBy('FlowNumber', 'MatShort', 'Applicant', 'MaterialType', 'Company', 'DeptManager1', 'CostDept', 'Spec', 'Keeper', 'SSZMemo')
                    ->get();
                $allRecords_associative_array = array();
                foreach ($allRecords as $record) {
                    // dd(gettype($record)); // test
                    $temp = array();
                    $temp['FlowNumber'] = $record->FlowNumber;
                    $temp['MatShort'] = $record->MatShort;
                    $temp['Applicant'] = $record->Applicant;
                    $temp['MaterialType'] = $record->MaterialType;
                    $temp['Company'] = $record->Company;
                    $temp['DeptManager1'] = $record->DeptManager1;
                    $temp['CostDept'] = $record->CostDept;
                    $temp['Spec'] = $record->Spec;
                    $temp['Keeper'] = $record->Keeper;
                    $temp['SSZMemo'] = $record->SSZMemo;
                    $temp['relQty'] = (int)$record->relQty;
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
                \Log::channel('dbquerys')->error(json_encode($e));
                dd($e); // dump error
            } // try - catch

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $og_dbName);
            \DB::purge(env("DB_CONNECTION"));
            \Log::channel('dbquerys')->info('--------------------------SSZ Info Query to MIS TEST END--------------------------');
        } // if else
    } // terminate
}

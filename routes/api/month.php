<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\在途量;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|                                !!!!! NOTICE !!!!!
|                        !!! API CANNOT ACCESS SESSION !!!
|
|
*/

// get the info
Route::post('transit', 'api\MonthlyPRController@showTransit');

Route::post('/sxb', 'api\MonthlyPRController@showSXB');

Route::post('/notmonth', 'api\MonthlyPRController@showNonMonthly');

Route::post('/rejectedUC', 'api\MonthlyPRController@showRejectedUnitConsumption');
Route::post('/checkersMail', 'api\MonthlyPRController@showCheckersEmail');
Route::post('/send_UC_to_DB', 'api\MonthlyPRController@update_UnitConsumption');

Route::post('/combined_month', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $datas = DB::table('月請購_單耗')
        ->join('MPS', function ($join) {
            $join->on('MPS.料號', '=', '月請購_單耗.料號')
                ->on('MPS.料號90', '=', '月請購_單耗.料號90');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
        })->where('月請購_單耗.狀態', '=', "已完成")->get();

    // foreach ($datas as $data) {
    //     $test = $data->幣別;
    //     if ($test !== $money) {
    //         if ($test === "USD" && $usd === null) {
    //             return back()->withErrors([
    //                 'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "USD" && $usd !== null) {
    //             array_push($array, $usd);
    //         } else if ($test === "RMB" && $rmb === null) {
    //             return back()->withErrors([
    //                 'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "RMB" && $rmb !== null) {
    //             array_push($array, $rmb);
    //         } else if ($test === "JPY" && $jpy === null) {
    //             return back()->withErrors([
    //                 'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "JPY" && $jpy !== null) {
    //             array_push($array, $jpy);
    //         } else if ($test === "TWD" && $twd === null) {
    //             return back()->withErrors([
    //                 'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "TWD" && $twd !== null) {
    //             array_push($array, $twd);
    //         } else if ($test === "VND" && $vnd === null) {
    //             return back()->withErrors([
    //                 'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "VND" && $vnd !== null) {
    //             array_push($array, $vnd);
    //         } else if ($test === "IDR" && $idr === null) {
    //             return back()->withErrors([
    //                 'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "IDR" && $idr !== null) {
    //             array_push($array, $idr);
    //         }
    //     } else {
    //         array_push($array, 1);
    //         continue;
    //     }
    // } // for each

    $datas2 = DB::table('非月請購')
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '非月請購.料號');
        })->whereNull('非月請購.SXB單號')->get();
        
    // foreach ($datas2 as $data) {
    //     $test = $data->幣別;
    //     if ($test !== $money) {
    //         if ($test === "USD" && $usd === null) {
    //             return back()->withErrors([
    //                 'usd' => trans('monthlyPRpageLang.plz_write') . 'USD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "USD" && $usd !== null) {
    //             array_push($array1, $usd);
    //         } else if ($test === "RMB" && $rmb === null) {
    //             return back()->withErrors([
    //                 'rmb' => trans('monthlyPRpageLang.plz_write') . 'RMB TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "RMB" && $rmb !== null) {
    //             array_push($array1, $rmb);
    //         } else if ($test === "JPY" && $jpy === null) {
    //             return back()->withErrors([
    //                 'jpy' => trans('monthlyPRpageLang.plz_write') . 'JPY TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "JPY" && $jpy !== null) {
    //             array_push($array1, $jpy);
    //         } else if ($test === "TWD" && $twd === null) {
    //             return back()->withErrors([
    //                 'twd' => trans('monthlyPRpageLang.plz_write') . 'TWD TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "TWD" && $twd !== null) {
    //             array_push($array1, $twd);
    //         } else if ($test === "VND" && $vnd === null) {
    //             return back()->withErrors([
    //                 'vnd' => trans('monthlyPRpageLang.plz_write') . 'VND TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "VND" && $vnd !== null) {
    //             array_push($array1, $vnd);
    //         } else if ($test === "IDR" && $idr === null) {
    //             return back()->withErrors([
    //                 'idr' => trans('monthlyPRpageLang.plz_write') . 'IDR TO' . ' ' . $money . trans('monthlyPRpageLang.rate'),
    //             ]);
    //         } else if ($test === "IDR" && $idr !== null) {
    //             array_push($array1, $idr);
    //         } // if else if
    //     } else {
    //         array_push($array1, 1);
    //         continue;
    //     } // if else
    // } // for each

    return \Response::json(['data' => $datas->merge($datas2), /*'rate1' => $array, 'rate2' => $array1,*/ "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

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
Route::post('/transit', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $transitisn = json_decode($request->input('transitisn'));
    $transitsend = json_decode($request->input('transitsend'));

    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test

    $test = DB::table('在途量')->select('料號', DB::raw('SUM(請購數量) as 請購數量'))
        ->groupBy('料號');

    $datas = DB::table('consumptive_material')
        ->joinSub($test, '在途量', function ($join) {
            $join->on('在途量.料號', '=', 'consumptive_material.料號');
        })->where('consumptive_material.料號', 'like', $transitisn . '%')
        ->where('consumptive_material.發料部門', 'like', $transitsend . '%')

        ->where('請購數量', '>', 0)->get();

    //dd($datas);
    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/sxb', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $sxbisn = json_decode($request->input('sxbisn'));
    $sxbsend = json_decode($request->input('sxbsend'));
    $sxbcheck = json_decode($request->input('sxbcheck'));
    $sxbbegin = date(json_decode($request->input('sxbbegin')));
    $sxbend = strtotime(json_decode($request->input('sxbend')));
    $end = date('Y-m-d H:i:s', strtotime('+ 1 day', $sxbend));

    //dd($transitsend);
    // dd($send);
    $datas = [];
    $datas1 = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('請購單', function ($join) {
            $join->on('請購單.料號', '=', 'consumptive_material.料號')
                ->whereNotNull('SXB單號');
        })->where('consumptive_material.料號', 'like', $sxbisn . '%')
        ->where('consumptive_material.發料部門', 'like', $sxbsend . '%')
        ->get();


    if ($sxbcheck) {
        $datas = $datas->whereBetween('請購時間', [$sxbbegin, $end])->values();
    }

    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/notmonth', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    //dd($transitsend);
    // dd($send);
    $datas = [];
    // dd(json_decode($request->input('LookInTargets'))); // test
    $datas = DB::table('consumptive_material')
        ->join('非月請購', function ($join) {
            $join->on('非月請購.料號', '=', 'consumptive_material.料號')
                ->whereNull('SXB單號');
        })->get();


    return \Response::json(['datas' => $datas, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

Route::post('/combined_month', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    // $money = $request->input('money');
    // $send = $request->input('send');
    // $array = array();
    // $array1 = array();
    // $usd = $request->input('usd');
    // $jpy = $request->input('jpy');
    // $twd = $request->input('twd');
    // $rmb = $request->input('rmb');
    // $vnd = $request->input('vnd');
    // $idr = $request->input('idr');

    // if ($money === null) {
    //     return back()->withErrors([
    //         'money' => trans('validation.required'),
    //     ]);
    // } // if

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

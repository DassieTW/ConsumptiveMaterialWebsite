<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicInformationController;
use Maatwebsite\Excel\Concerns\ToArray;
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

// get 安全庫存
Route::post('/safesearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號 '))->groupBy('客戶別', '料號');
    $datas = DB::table('月請購_單耗')
        ->join('MPS', function ($join) {
            $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                ->on('MPS.機種', '=', '月請購_單耗.機種')
                ->on('MPS.製程', '=', '月請購_單耗.製程');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
        })
        ->leftjoin('safestock報警備註', function ($join) {
            $join->on('safestock報警備註.料號', '=', '月請購_單耗.料號');
            $join->on('safestock報警備註.客戶別', '=', '月請購_單耗.客戶別');
        })
        ->leftJoinSub($inventorys, 'suminventory', function ($join) {
            $join->on('月請購_單耗.客戶別', '=', 'suminventory.客戶別');
            $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
        })
        ->select(
            '月請購_單耗.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.安全庫存',
            // 'consumptive_material.耗材歸屬',
            'consumptive_material.發料部門',
            '月請購_單耗.單耗',
            'MPS.下月MPS',
            'MPS.下月生產天數',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )->groupBy(
            '月請購_單耗.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.安全庫存',
            // 'consumptive_material.耗材歸屬',
            'consumptive_material.發料部門',
            '月請購_單耗.單耗',
            'MPS.下月MPS',
            'MPS.下月生產天數',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )
        ->where('consumptive_material.月請購', '=', "是")
        // ->where('consumptive_material.耗材歸屬', '=', "單耗")
        ->where('月請購_單耗.狀態', '=', "已完成")
        ->get()->toArray();

    // dd($datas); // test

    foreach ($datas as $data) {
        $safe =  $data->LT * $data->單耗 * $data->下月MPS / $data->下月生產天數;
        $data->安全庫存 = round($safe);
    } // for each

    $count = count($datas);
    for ($a = 0; $a < $count; $a++) {
        for ($i = $a; $i + 1 < $count; $i++) {
            if ((isset($datas[$a])) && (isset($datas[$i + 1]))) {
                if ($datas[$a]->客戶別 === $datas[$i + 1]->客戶別 && $datas[$a]->料號 === $datas[$i + 1]->料號) {
                    $datas[$a]->安全庫存 += $datas[$i + 1]->安全庫存;
                    unset($datas[$i + 1]);
                    $datas = array_values($datas);
                } // if
            } // if
        } // for
    } // for

    foreach ($datas as $key => $value) {
        if ($value->inventory現有庫存 > $value->安全庫存) {
            unset($datas[$key]);
        }
    }
    $datas = array_values($datas);

    $inventorys1 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 客戶別 , 料號'))->groupBy('客戶別', '料號');
    $datas1 = DB::table('月請購_站位')
        ->join('MPS', function ($join) {
            $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                ->on('MPS.機種', '=', '月請購_站位.機種')
                ->on('MPS.製程', '=', '月請購_站位.製程');
        })
        ->join('consumptive_material', function ($join) {
            $join->on('consumptive_material.料號', '=', '月請購_站位.料號');
        })
        ->leftjoin('safestock報警備註', function ($join) {
            $join->on('safestock報警備註.料號', '=', '月請購_站位.料號');
            $join->on('safestock報警備註.客戶別', '=', '月請購_站位.客戶別');
        })
        ->leftJoinSub($inventorys1, 'suminventory', function ($join) {
            $join->on('月請購_站位.客戶別', '=', 'suminventory.客戶別');
            $join->on('月請購_站位.料號', '=', 'suminventory.料號');
        })

        ->select(
            '月請購_站位.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.MPQ',
            'consumptive_material.安全庫存',
            // 'consumptive_material.耗材歸屬',
            'consumptive_material.發料部門',
            '月請購_站位.下月站位人數',
            '月請購_站位.下月開線數',
            '月請購_站位.下月開班數',
            '月請購_站位.下月每人每日需求量',
            '月請購_站位.下月每日更換頻率',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )->groupBy(
            '月請購_站位.客戶別',
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.LT',
            'consumptive_material.月請購',
            'consumptive_material.MPQ',
            'consumptive_material.安全庫存',
            // 'consumptive_material.耗材歸屬',
            'consumptive_material.發料部門',
            '月請購_站位.下月站位人數',
            '月請購_站位.下月開線數',
            '月請購_站位.下月開班數',
            '月請購_站位.下月每人每日需求量',
            '月請購_站位.下月每日更換頻率',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )
        ->where('consumptive_material.月請購', '=', "是")
        // ->where('consumptive_material.耗材歸屬', '=', "站位")
        ->where('月請購_站位.狀態', '=', "已完成")
        ->get()->toArray();

    foreach ($datas1 as $data) {
        $safe =  $data->LT * $data->下月站位人數 * $data->下月開線數 * $data->下月開班數 * $data->下月每人每日需求量 * $data->下月每日更換頻率 / $data->MPQ;
        $data->安全庫存 = round($safe);
    }

    $count1 = count($datas1);
    for ($a = 0; $a < $count1; $a++) {
        for ($i = $a; $i + 1 < $count1; $i++) {
            if ((isset($datas1[$a])) && (isset($datas1[$i + 1]))) {
                if ($datas1[$a]->客戶別 === $datas1[$i + 1]->客戶別 && $datas1[$a]->料號 === $datas1[$i + 1]->料號) {
                    $datas1[$a]->安全庫存 += $datas1[$i + 1]->安全庫存;
                    unset($datas1[$i + 1]);
                    $datas1 = array_values($datas1);
                } // if
            } // if
        } // for
    } // for

    foreach ($datas1 as $key => $value) {
        if ($value->inventory現有庫存 > $value->安全庫存) {
            unset($datas1[$key]);
        }
    }
    $datas1 = array_values($datas1);

    $inventorys2 = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存  ,料號'))->groupBy('料號');
    $datas2 = DB::table('consumptive_material')
        ->leftJoinSub($inventorys2, 'suminventory', function ($join) {
            $join->on('consumptive_material.料號', '=', 'suminventory.料號');
        })
        ->leftjoin('safestock報警備註', function ($join) {
            $join->on('safestock報警備註.料號', '=', 'consumptive_material.料號');
        })
        ->select(
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.安全庫存',
            'consumptive_material.月請購',
            'consumptive_material.發料部門',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )->groupBy(
            'consumptive_material.料號',
            'consumptive_material.品名',
            'consumptive_material.規格',
            'consumptive_material.安全庫存',
            'consumptive_material.月請購',
            'consumptive_material.發料部門',
            'inventory現有庫存',
            'safestock報警備註.備註',
        )
        ->where('consumptive_material.月請購', '=', "否")
        ->get()->unique('料號')->toArray();

    foreach ($datas2 as $data) {
        $safe = $data->安全庫存;
        $data->安全庫存 = round($safe);
    } // for each

    foreach ($datas2 as $key => $value) {

        if ($value->inventory現有庫存 >= $value->安全庫存) {
            unset($datas2[$key]);
        }
    }
    $datas2 = array_values($datas2);
    $num = count($datas) + count($datas1) + count($datas2);

    $alldata = array_merge($datas, $datas1, $datas2);
    dd($alldata);
    return \Response::json(['datas' => $alldata, "dbName" => $dbName], 200/* Status code here default is 200 ok*/);
});

// get 呆滯天數
Route::post('/daysearch', function (Request $request) {
    \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
    \DB::purge(env("DB_CONNECTION"));
    $dbName = DB::connection()->getDatabaseName(); // test

    $inboundclient = json_decode($request->input('inboundclient'));
    $inboundisn = json_decode($request->input('inboundisn'));
    $inboundloc = json_decode($request->input('inboundloc'));
    $inboundsend = json_decode($request->input('inboundsend'));
    $inboundmonth = json_decode($request->input('inboundmonth'));
    $inboundnogood = json_decode($request->input('inboundnogood'));

    //$datas = [];

    //不良品inventory
    if ($inboundnogood) {

        $datas = DB::table('consumptive_material')
            ->join('不良品inventory', function ($join) {
                $join->on('不良品inventory.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('不良品inventory.客戶別', 'like', $inboundclient . '%')
            ->where('不良品inventory.儲位', 'like', $inboundloc . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')
            ->where('現有庫存', '>', 0)->get();

        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
            ->where('月請購_單耗.狀態', '=', "已完成")
            // ->where('consumptive_material.耗材歸屬', '=', "單耗")
            ->where('consumptive_material.月請購', '=', "是");

        $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
            ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


        $datas2 = DB::table('月請購_站位')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                    ->on('MPS.機種', '=', '月請購_站位.機種')
                    ->on('MPS.製程', '=', '月請購_站位.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
            ->where('月請購_站位.狀態', '=', "已完成");
            // ->where('consumptive_material.耗材歸屬', '=', "站位");

        $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                    * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
            ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


        for ($i = 0; $i < count($datas); $i++) {

            $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
            $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int) $interval;
            $datas[$i]->呆滯天數 = $interval;
            if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                    $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                }
            }
            for ($k = 0; $k < count($datas2); $k++) {
                if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                    $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
                }
            }
        }
    }
    //normal inventory
    else {

        $datas = DB::table('consumptive_material')
            ->join('inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('inventory.客戶別', 'like', $inboundclient . '%')
            ->where('inventory.儲位', 'like', $inboundloc . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')
            ->where('現有庫存', '>', 0)
            ->get();


        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.下月MPS', 'MPS.下月生產天數', '月請購_單耗.*', DB::raw('(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存)'))
            ->where('月請購_單耗.狀態', '=', "已完成")
            // ->where('consumptive_material.耗材歸屬', '=', "單耗")
            ->where('consumptive_material.月請購', '=', "是");

        $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.下月MPS * 月請購_單耗.單耗 * consumptive_material.LT / MPS.下月生產天數) as 安全庫存'))
            ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


        $datas2 = DB::table('月請購_站位')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                    ->on('MPS.機種', '=', '月請購_站位.機種')
                    ->on('MPS.製程', '=', '月請購_站位.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.*', '月請購_站位.*', DB::raw('(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                        * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
            ->where('月請購_站位.狀態', '=', "已完成");
            // ->where('consumptive_material.耗材歸屬', '=', "站位");

        $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(consumptive_material.LT * 月請購_站位.下月站位人數 * 月請購_站位.下月開線數
                    * 月請購_站位.下月開班數 * 月請購_站位.下月每人每日需求量 * 月請購_站位.下月每日更換頻率 / consumptive_material.MPQ) as 安全庫存'))
            ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();


        for ($i = 0; $i < count($datas); $i++) {

            $maxtime = date_create(date('Y-m-d', strtotime($datas[$i]->最後更新時間)));
            $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
            $interval = date_diff($maxtime, $nowtime);
            $interval = $interval->format('%R%a');
            $interval = (int) $interval;
            $datas[$i]->呆滯天數 = $interval;
            if ($datas[$i]->安全庫存 === null) $datas[$i]->安全庫存 = 0;
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                    $datas[$i]->安全庫存 = round($datas1[$j]->安全庫存, 3);
                }
            }
            for ($k = 0; $k < count($datas2); $k++) {
                if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                    $datas[$i]->安全庫存 = round($datas2[$k]->安全庫存, 3);
                }
            }
        }
    }
    //庫存使用月數
    if ($inboundmonth) {
        $test = DB::table('inventory')
            ->select('料號', '客戶別', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號', '客戶別');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, 'inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('consumptive_material.料號', 'like', $inboundisn . '%')
            ->where('inventory.客戶別', 'like', $inboundclient . '%')
            ->where('consumptive_material.發料部門', 'like', $inboundsend . '%')
            ->where('現有庫存', '>', 0)->get();

        $datas1 = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_單耗.客戶別')
                    ->on('MPS.機種', '=', '月請購_單耗.機種')
                    ->on('MPS.製程', '=', '月請購_單耗.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.本月MPS', '月請購_單耗.*', DB::raw('(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量)'))
            ->where('月請購_單耗.狀態', '=', "已完成");
            // ->where('consumptive_material.耗材歸屬', '=', "單耗");

        $datas1 = $datas1->select('月請購_單耗.客戶別', '月請購_單耗.料號', DB::raw('SUM(MPS.本月MPS * 月請購_單耗.單耗) as 月使用量'))
            ->groupBy('月請購_單耗.料號', '月請購_單耗.客戶別')->get();


        $datas2 = DB::table('月請購_站位')
            ->join('MPS', function ($join) {
                $join->on('MPS.客戶別', '=', '月請購_站位.客戶別')
                    ->on('MPS.機種', '=', '月請購_站位.機種')
                    ->on('MPS.製程', '=', '月請購_站位.製程');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('月請購_站位.料號', '=', 'consumptive_material.料號');
            })
            ->select('MPS.*', '月請購_站位.*', DB::raw('(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
                        * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
            ->where('月請購_站位.狀態', '=', "已完成");
            // ->where('consumptive_material.耗材歸屬', '=', "站位");

        $datas2 = $datas2->select('月請購_站位.客戶別', '月請購_站位.料號', DB::raw('SUM(MPS.本月生產天數 * 月請購_站位.當月站位人數 * 月請購_站位.當月開線數
                    * 月請購_站位.當月開班數 * 月請購_站位.當月每人每日需求量 * 月請購_站位.當月每日更換頻率 / consumptive_material.MPQ) as 月使用量'))
            ->groupBy('月請購_站位.料號', '月請購_站位.客戶別')->get();

        for ($i = 0; $i < count($datas); $i++) {
            for ($j = 0; $j < count($datas1); $j++) {
                if ($datas[$i]->料號 === $datas1[$j]->料號 && $datas[$i]->客戶別 === $datas1[$j]->客戶別) {
                    $datas[$i]->月使用量 = $datas1[$j]->月使用量;
                    $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
                }
            }
            for ($k = 0; $k < count($datas2); $k++) {
                if ($datas[$i]->料號 === $datas2[$k]->料號 && $datas[$k]->客戶別 === $datas2[$k]->客戶別) {
                    $datas[$i]->月使用量 = $datas2[$k]->月使用量;
                    $datas[$i]->庫存使用月數 = round($datas[$i]->現有庫存 / $datas1[$j]->月使用量, 5);
                }
            }
        }

        //補月使用量
        for ($i = 0; $i < count($datas); $i++) {
            $datas[$i]->單價 = round($datas[$i]->單價, 5);
            if (!(property_exists($datas[$i], "月使用量"))) {
                $datas[$i]->月使用量 = 0;
                $datas[$i]->庫存使用月數 = 0;
            }
        }
    }

    return \Response::json(['datas' => $datas, "dbName" => $dbName, "month" => $inboundmonth], 200/* Status code here default is 200 ok*/);
});

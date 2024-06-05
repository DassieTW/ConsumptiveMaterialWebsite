<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\客戶別;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CallController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //安全庫存警報
    public function safesubmit(Request $request)
    {
        $send = $request->input('send');

        $inventorys = DB::table('inventory')->select(DB::raw('sum(現有庫存) as inventory現有庫存 , 料號 '))->groupBy('料號');
        $datas = DB::table('月請購_單耗')
            ->join('MPS', function ($join) {
                $join->on('MPS.料號90', '=', '月請購_單耗.料號90')
                    ->on('MPS.料號', '=', '月請購_單耗.料號');
            })
            ->join('consumptive_material', function ($join) {
                $join->on('consumptive_material.料號', '=', '月請購_單耗.料號');
            })
            ->leftjoin('safestock報警備註', function ($join) {
                $join->on('safestock報警備註.料號', '=', '月請購_單耗.料號')
                ->on('safestock報警備註.客戶別', '=', '月請購_單耗.料號90');

            })
            ->leftJoinSub($inventorys, 'suminventory', function ($join) {
                $join->on('月請購_單耗.料號', '=', 'suminventory.料號');
            })
            ->select(
                'consumptive_material.料號',
                'consumptive_material.品名',
                'consumptive_material.規格',
                'consumptive_material.LT',
                'consumptive_material.月請購',
                'consumptive_material.安全庫存',
                'consumptive_material.發料部門',
                '月請購_單耗.單耗',
                'MPS.下月MPS',
                'MPS.下月生產天數',
                'inventory現有庫存',
                'safestock報警備註.備註',
            )->groupBy(
                'consumptive_material.料號',
                'consumptive_material.品名',
                'consumptive_material.規格',
                'consumptive_material.LT',
                'consumptive_material.月請購',
                'consumptive_material.安全庫存',
                'consumptive_material.發料部門',
                '月請購_單耗.單耗',
                'MPS.下月MPS',
                'MPS.下月生產天數',
                'inventory現有庫存',
                'safestock報警備註.備註',
            )
            ->where('consumptive_material.月請購', '=', "是")
            // ->where('consumptive_material.耗材歸屬', '=', "單耗")
            ->where('consumptive_material.發料部門', 'like', $send . '%')
            ->where('月請購_單耗.狀態', '=', "已完成")
            ->get()->toArray();

        // dd($datas); // test

        foreach ($datas as $data) {
            $safe =  5 * $data->單耗 * $data->下月MPS / 26;
            $data->安全庫存 = round($safe);
        } // for each

        $count = count($datas);
        for ($a = 0; $a < $count; $a++) {
            for ($i = $a; $i + 1 < $count; $i++) {
                if ((isset($datas[$a])) && (isset($datas[$i + 1]))) {
                    if ($datas[$a]->料號 === $datas[$i + 1]->料號) {
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
            ->where('consumptive_material.發料部門', 'like', $send . '%')
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
        $num = count($datas) + count($datas2);
        return view('call.safe')->with(['data' => $datas])->with(['data2' => $datas2])->with(['num' => $num]);
    }

    //呆滯天數警報
    public function daysubmit(Request $request)
    {
        $send = $request->input('send');

        $datas = Inventory::join('consumptive_material', 'consumptive_material.料號', "=", 'inventory.料號')
            ->select(
                'inventory.料號',
                DB::raw('max(inventory.最後更新時間) as inventory最後更新時間'),
                DB::raw('sum(inventory.現有庫存) as inventory現有庫存'),
                'consumptive_material.品名',
                'consumptive_material.規格',
                'sluggish報警備註.備註',
            )
            ->leftjoin('sluggish報警備註', function ($join) {
                $join->on('sluggish報警備註.料號', '=', 'inventory.料號');
            })
            ->groupBy('inventory.料號', 'consumptive_material.品名', 'consumptive_material.規格', 'sluggish報警備註.備註')
            ->havingRaw('DATEDIFF(dd,max(inventory.最後更新時間),getdate())>90')
            ->havingRaw('sum(inventory.現有庫存) > ?', [0])
            ->where('consumptive_material.發料部門', 'like', $send . '%')
            ->get();

        $num = count($datas);
        return view('call.day')->with(['data' => $datas])->with(['num' => $num]);
    }

    //安全庫存備註
    public function saferemark(Request $request)
    {
        $record = 0;
        $number = $request->input('number');
        $remark = $request->input('remark');
        if ($number === null) {
            return \Response::json([]/* Status code here default is 200 ok*/);
        } else {
            $count = count($remark);
            try {
                $res_arr_values = array();
                for ($i = 0; $i < $count; $i++) {
                    $temp = array(
                        "料號" => $request->input('number')[$i],
                        "備註" => $request->input('remark')[$i],
                    );

                    $res_arr_values[] = $temp;
                } //for

                DB::beginTransaction();

                // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
                $whole_load = array_chunk($res_arr_values, 200, true);
                for ($i = 0; $i < count($whole_load); $i++) {
                    $temp_record = \DB::table('safestock報警備註')->upsert(
                        $whole_load[$i],
                        ['料號'],
                        ['客戶別'],
                        ['備註']
                    );

                    $record = $record + $temp_record;
                } // for

                DB::commit();

                return \Response::json(["record" => $record]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
            } // try catch
        } // if else
    } // saferemark

    //呆滯天數備註
    public function dayremark(Request $request)
    {
        $number = $request->input('number');
        $remark = $request->input('remark');
        $client = $request->input('client');
        $record = 0;
        if ($number === null) {
            return \Response::json([]/* Status code here default is 200 ok*/);
        } else {
            $count = count($number);
            try {
                $res_arr_values = array();
                for ($i = 0; $i < $count; $i++) {
                    $temp = array(
                        "料號" => $request->input('number')[$i],
                        "備註" => $request->input('remark')[$i],
                    );

                    $res_arr_values[] = $temp;
                } //for

                DB::beginTransaction();

                // chunk the parameter array first so it doesnt exceed the MSSQL hard limit
                $whole_load = array_chunk($res_arr_values, 200, true);
                for ($i = 0; $i < count($whole_load); $i++) {
                    $temp_record = \DB::table('sluggish報警備註')->upsert(
                        $whole_load[$i],
                        ['料號'],
                        ['備註']
                    );

                    $record = $record + $temp_record;
                } // for

                DB::commit();

                return \Response::json(['records' => $record]/* Status code here default is 200 ok*/);
            } catch (\Exception $e) {
                DB::rollback();
                $mess = $e->getMessage();
                return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
            } // try catch
        } // if else
    } // dayremark
}

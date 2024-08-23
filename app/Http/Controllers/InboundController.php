<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\客戶別;
use App\Models\機種;
use App\Models\製程;
use App\Models\線別;
use App\Models\領用原因;
use App\Models\退回原因;
use App\Models\入庫原因;
use App\Models\發料部門;
use App\Models\Outbound;
use App\Models\出庫退料;
use App\Models\儲位;
use App\Models\在途量;
use App\Models\Inventory;
use App\Models\不良品Inventory;
use App\Models\Inbound;
use App\Models\ConsumptiveMaterial;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InboundController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //入庫-儲位調撥
    public function change(Request $request)
    {
        $test = DB::table('inventory')
            ->select('料號', '儲位', '最後更新時間', DB::raw('SUM(現有庫存) as 現有庫存'))
            ->groupBy('料號', '儲位', '最後更新時間');

        $datas = DB::table('consumptive_material')
            ->joinSub($test, 'inventory', function ($join) {
                $join->on('inventory.料號', '=', 'consumptive_material.料號');
            })->where('現有庫存', '>', 0)
            ->where('consumptive_material.料號', 'like', $request->input('number') . '%')
            ->where('inventory.儲位', 'like', $request->input('position') . '%')
            ->where('consumptive_material.發料部門', 'like', $request->input('send') . '%')->get();


        return view('inbound.change')->with(['data' => $datas]);
    }

    //入庫-儲位調撥提交
    public function changesubmit(Request $request)
    {
        $number = $request->input('number');
        $oldposition = $request->input('oldposition');
        $amount = $request->input('amount');
        $newposition = $request->input('newposition');
        //$stock = $request->input('stock');
        $now = Carbon::now();
        $test = DB::table('inventory')
            ->where('料號', $number)
            ->where('儲位', $newposition)
            ->value('現有庫存');

        DB::beginTransaction();
        try {

            if ($test !== null) {
                DB::table('inventory')
                    ->where('料號', $number)
                    ->where('儲位', $newposition)
                    ->update(['現有庫存' => $test + $amount, '最後更新時間' => $now]);
            } else {
                DB::table('inventory')
                    ->insert(['料號' => $number, '現有庫存' => $amount, '儲位' => $newposition, '最後更新時間' => $now]);
            }

            $stock = DB::table('inventory')
                ->where('料號', $number)
                ->where('儲位', $oldposition)
                ->value('現有庫存');
            DB::table('inventory')
                ->where('料號', $number)
                ->where('儲位', $oldposition)
                ->update(['現有庫存' => $stock - $amount, '最後更新時間' => $now]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $mess = $e->getMessage();
            return \Response::json(['message' => $mess], 420/* Status code here default is 200 ok*/);
        }

        return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
    }
} // InboundController

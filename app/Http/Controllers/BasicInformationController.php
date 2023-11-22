<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\發料部門;
use App\Models\ConsumptiveMaterial;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use Session;
use Route;
use Carbon\Carbon;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class BasicInformationController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //基礎信息更新或刪除
    public function changeordelete(Request $request)
    {
        if (is_array($request->input('data'))) {
            $count = count($request->input('data'));
        } else {
            $count = '0';
        }

        $names = $request->input('data');
        $oldnames = $request->input('olddata');
        $datanew = $request->input('datanew');
        $choose = "";
        $chooseindex = "";
        $errnew = "";

        //factory
        if ($request->input('dataname') === "factory") {
            $choose = "App\Models\廠別";
            $chooseindex = "廠別";
            $table = "廠別";
            $errnew = "factorynew";
        }
        //client
        else if ($request->input('dataname') === "client") {
            $choose = "App\Models\客戶別";
            $chooseindex = '客戶';
            $table = "客戶別";
            $errnew = "clientnew";
        }
        //machine
        else if ($request->input('dataname') === "machine") {
            $choose = "App\Models\機種";
            $chooseindex = '機種';
            $table = "機種";
            $errnew = "machinenew";
        }
        //production
        else if ($request->input('dataname') === "production") {
            $choose = "App\Models\製程";
            $chooseindex = '制程';
            $table = "製程";
            $errnew = "productionnew";
        }
        //line
        else if ($request->input('dataname') === "line") {
            $choose = "App\Models\線別";
            $chooseindex = '線別';
            $table = "線別";
            $errnew = "linenew";
        }
        //use
        else if ($request->input('dataname') === "use") {
            $choose = "App\Models\領用部門";
            $chooseindex = '領用部門';
            $table = "領用部門";
            $errnew = "usenew";
        }
        //usereason
        else if ($request->input('dataname') === "usereason") {
            $choose = "App\Models\領用原因";
            $chooseindex = '領用原因';
            $table = "領用原因";
            $errnew = "usereasonnew";
        }
        //inreason
        else if ($request->input('dataname') === "inreason") {
            $choose = "App\Models\入庫原因";
            $chooseindex = '入庫原因';
            $table = "入庫原因";
            $errnew = "inreasonnew";
        }
        //position
        else if ($request->input('dataname') === "position") {
            $choose = "App\Models\儲位";
            $chooseindex = '儲存位置';
            $table = "儲位";
            $errnew = "positionnew";
        }
        //send
        else if ($request->input('dataname') === "send") {
            $choose = "App\Models\發料部門";
            $chooseindex = '發料部門';
            $table = "發料部門";
            $errnew = "sendnew";
        }
        //o庫
        else if ($request->input('dataname') === "o") {
            $choose = "App\Models\O庫";
            $chooseindex = 'O庫';
            $table = "O庫";
            $errnew = "onew";
        }
        //退回原因
        else if ($request->input('dataname') === "back") {
            $choose = "App\Models\退回原因";
            $chooseindex = '退回原因';
            $table = "退回原因";
            $errnew = "backnew";
        }
        if ($request->input('select') === "刪除") {
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {
                    $choose::where($chooseindex, $names[$i])->delete();
                } //for
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
            }

            return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
        }
        //change
        else if ($request->input('select') === "更新") {
            DB::beginTransaction();
            try {
                for ($i = 0; $i < $count; $i++) {

                    DB::table($table)
                        ->where($chooseindex, $oldnames[$i])
                        ->update([$chooseindex => $names[$i]]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
            }

            if ($datanew !== null) {
                /*$test = $choose::onlyTrashed()
                        ->where($chooseindex, $datanew)
                        ->get();*/
                $testa = DB::table($table)->where($chooseindex, $datanew)->value($chooseindex);
                if ($testa !== null) {
                    return \Response::json(['message' => $errnew], 422/* Status code here default is 200 ok*/);
                }
                DB::beginTransaction();
                try {

                    DB::table($table)
                        ->insert([$chooseindex => $datanew]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $errnew], 422/* Status code here default is 200 ok*/);
                }
            }
            return \Response::json(['boolean' => 'true']/* Status code here default is 200 ok*/);
        } //if-else
    }

    //基礎資料上傳
    public function uploadbasic(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip'
        ]);
        $path = $request->file('select_file')->getRealPath();

        $testAgainstFormats = [
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
            \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX,
        ];

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path, 1, $testAgainstFormats);


        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $choose = $sheetData[0][0];


        if (
            $choose !== "廠別" && $choose !== "客戶別" && $choose !== "機種"  && $choose !== "製程" && $choose !== "線別" && $choose !== "領用部門"
            && $choose !== "領用原因" && $choose !== "入庫原因" && $choose !== "儲位" && $choose !== "發料部門" && $choose !== "O庫" && $choose !== "退回原因"
        ) {
            $mess = trans('basicInfoLang.uploaderror');
            echo ("<script LANGUAGE='JavaScript'>
                    window.alert('$mess');
                    window.location.href = '/basic';
                    </script>");
        } else {
            unset($sheetData[0]);
            return view('basic.uploadbasic')->with(['data' => $sheetData])->with('choose', $choose);
        }
    }

    //基礎信息批量新增至資料庫
    public function insertuploadbasic(Request $request)
    {
        $dataarray =  $request->input('data');
        $count = count($dataarray);
        $choose = $request->input('title');
        $row = $request->input('row');
        $record = 0;
        $check = array();
        $test  = [];

        if ($choose == '客戶別') {
            $chooseindex = '客戶';
            // $table = "App\Models\客戶別";
        } else if ($choose == '儲位') {
            $chooseindex = '儲存位置';
            // $table = "App\Models\儲位";
        } else if ($choose == '製程') {
            $chooseindex = '制程';
            // $table = "App\Models\製程";
        } else {
            $chooseindex = $choose;
            // $table = "App\Models" . "\\" . $choose;
        }

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $data = $dataarray[$i];
                $testa = DB::table($choose)->where($chooseindex, $data)->value($chooseindex);

                //判斷data是否重複
                if ($testa === null) {
                    $test[$i] = 1;
                } else {
                    $test[$i] = 2;
                }
                if ($test[$i] == 1) {
                    DB::table($choose)
                        ->insert([$chooseindex => $data]);
                    $record++;
                    array_push($check, $row[$i]);
                } else {
                    continue;
                }
            }
            DB::commit();
            return \Response::json(['record' => $record, 'choose' => $choose, 'check' => $check]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 421/* Status code here default is 200 ok*/);
        }
    }
}

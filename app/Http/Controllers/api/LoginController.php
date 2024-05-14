<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\人員信息;
use DB;
use Lang;
use Session;
use App\Http\Controllers\Controller;
use DateTime;

class LoginController extends Controller
{
    // 權限0的User可透過此功能修改可登入DB清單
    public function update_available_dblist(Request $request)
    {
        $username = $request->input('username');
        $dbListStr = $request->input('list');
        $databaseArray = config('database_list.databases');
        $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
        $error = '';

        foreach ($databaseArray as $site) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
            \DB::purge(env("DB_CONNECTION"));

            DB::beginTransaction();

            try {
                $affected = DB::table('login')
                    ->where('username', '=', $username)
                    ->update(['available_dblist' => $dbListStr, 'update_priority_time' => $datetime]);

                DB::commit();
            } catch (\Exception $e) {
                // dd($e); // test
                return \Response::json(['message' => $e], 420); // Status code here

                DB::rollback();
                // something went wrong
            } // try catch
        } // foreach

        return \Response::json(['message' => "success"], 200); // Status code here

    } // update_available_dblist

    //用戶權限更新
    public function usernamechange(Request $request)
    {
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $request->input("DB"));
        \DB::purge(env("DB_CONNECTION"));
        $dbName = \DB::connection()->getDatabaseName(); // test
        $username = $request->input('username');
        $priority = $request->input('priority');
        $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());

        DB::table('login')
            ->where('username', $username)
            ->update(['priority' => $priority, 'update_priority_time' => $datetime]);

        return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
    } // usernamechange()

    //用戶信息刪除 權限0 才能刪除User
    public function username_del(Request $request)
    {
        //將此工號從每個DB都刪除 下次此人登入時應該要從選擇廠別重新開始
        $username = $request->input('username');
        $databaseArray = config('database_list.databases');
        try {
            $currentDB = \Config::get('database.connections.' . env("DB_CONNECTION") . '.database', 'default');
            foreach ($databaseArray as $site) {
                \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $site);
                \DB::purge(env("DB_CONNECTION"));
                \DB::table('login')
                    ->where('username', '=', $username)
                    ->delete();
            } // foreach

            // reset back to login db, just in case
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $currentDB);
            \DB::purge(env("DB_CONNECTION"));
            return \Response::json(['message' => 'success']/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e, "\n";
            dd($e); // test
            return \Response::json(['message' => json_encode($e)], 420);
        } // try catch
    } // username_del()

    // to do, not yet move to API (used by Vue)
    //人員信息刪除,新增
    public function numberchangeordel(Request $request)
    {
        $select = $request->input('select');
        $count = $request->input('count');
        $number = $request->input('number');
        $numbers = DB::table('人員信息')->pluck('工號');
        $newname = $request->input('newname');
        $newnumber = $request->input('newnumber');
        $newdep = $request->input('newdep');

        //delete
        if ($select === "刪除") {
            for ($i = 0; $i < $count; $i++) {
                DB::beginTransaction();
                try {
                    DB::table('人員信息')
                        ->where('工號', $number[$i])
                        ->delete();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
                }
            }
            return \Response::json(['message' => $count, 'status' => 202]/* Status code here default is 200 ok*/);
        }
        //new
        else {

            DB::beginTransaction();
            //job number repeat
            for ($i = 0; $i < count($numbers); $i++) {
                if (strcasecmp($newnumber, $numbers[$i]) === 0) {
                    return \Response::json(['message' => Lang::get('loginPageLang.jobrepeat')], 421/* Status code here default is 200 ok*/);
                } else {
                    continue;
                }
            }

            DB::table('人員信息')->insert(['工號' => $newnumber, '姓名' => $newname, '部門' => $newdep]);
            DB::commit();
            return \Response::json(['message' => $count, 'status' => 201]/* Status code here default is 200 ok*/);
        } // if else
    } // numberchangeordel
}

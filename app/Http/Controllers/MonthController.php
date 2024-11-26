<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\月請購_單耗;
use App\Models\人員信息;
use DB;
use Session;
use Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mail;
use Mockery\Undefined;
use PhpParser\Node\Expr\Cast\Array_;

class MonthController extends Controller
{
    // Get OA Account Info from 畫押人員/單位工程師
    public function testconsumeOALogin(Request $request)
    {
        if (request()->filled('r') && request()->filled('u') && request()->filled('d')) {
            $email = request()->query('r');
            $username = request()->query('u');
            $database = request()->query('d');
            $datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', \Carbon\Carbon::now());
            $lang = request()->query('l');
            request()->getSession()->put('locale', $lang);
            \App::setLocale($lang);

            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
            \DB::purge(env("DB_CONNECTION"));

            // update the info from SSO POST
            $user = Login::upsert([
                [
                    'username' => $request->work_id,
                    'password' => '123456',
                    'priority' => 69,
                    'last_login_time' => $datetime
                ],
            ], ['username'], ['last_login_time']);

            $people = 人員信息::upsert(
                [
                    [
                        '工號' => $request->work_id,
                        '姓名' => $request->user_name,
                        '部門' => $request->dept_name,
                        'email' => $request->office_mail,
                        '主管工號' => $request->m_work_id
                    ],
                    [
                        '工號' => $request->m_work_id,
                        '姓名' => $request->m_name,
                        '部門' => $request->m_dept_name,
                        'email' => $request->m_office_mail,
                        '主管工號' => $request->m2_work_id
                    ]
                ],
                ['工號'],
                ['姓名', '部門', 'email', '主管工號']
            );

            $people_m2 = 人員信息::upsert(
                [
                    [
                        '工號' => $request->m2_work_id,
                        '姓名' => $request->m2_name,
                        '部門' => $request->m2_dept_name,
                        'email' => $request->m2_office_mail,
                    ]
                ],
                ['工號'],
                ['姓名', '部門', 'email']
            );

            DB::table('月請購_單耗')
                ->where('狀態', '=', "待畫押")
                ->where("畫押信箱", '=', $email)
                ->update([
                    '畫押信箱' => $request->office_mail
                ]);

            $name = DB::table('人員信息')->where('工號', $username)->value('姓名');

            return view('month.testconsume')->with(['data' => \App\Models\月請購_單耗::cursor()->where('狀態', "待畫押")->where("畫押信箱", $request->office_mail)])
                ->with(['email' => $request->office_mail])->with(['username' => $name])->with(['database' => $database]);
        } else {
            return abort(404);
        } // if else
    } // testconsumeOALogin

    //單耗畫押提交
    public function testconsume(Request $request)
    {
        $now = Carbon::now();
        $count = $request->input('count');
        $sender = $request->input('sender');
        $Alldata = json_decode($request->input('AllData'));
        $database = $request->input('database');
        \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', $database);
        \DB::purge(env("DB_CONNECTION"));

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $number = $Alldata[0][$i];
                $number90 = $Alldata[5][$i];
                $check = $Alldata[3][$i];

                if ($check) {
                    月請購_單耗::where('料號', $number)
                        ->where('料號90', $number90)
                        ->update([
                            '狀態' => "已完成", '畫押時間' => $now,
                        ]);
                } else {
                    月請購_單耗::where('料號', $number)
                        ->where('料號90', $number90)
                        ->update([
                            '狀態' => "待重畫", '畫押時間' => $now,
                        ]);
                } // if else
            } //for
            DB::commit();
            self::sendcheckconsume($Alldata, $count, $sender);

            return \Response::json(['message' => $count]/* Status code here default is 200 ok*/);
        } catch (\Exception $e) {
            DB::rollback();
            return \Response::json(['message' => $e->getmessage()], 420/* Status code here default is 200 ok*/);
        } //try-catch
    } // testconsume

    //send check consume mail
    public static function sendcheckconsume($alldata, $count, $sender)
    {
        $data = array('datas' => $alldata, 'count' => $count);

        Mail::send('mail/markconsume', $data, function ($message) use ($sender) {
            $email = DB::table('login')
                ->join('人員信息', function ($join) {
                    $join->on('人員信息.工號', '=', 'login.username');
                })
                ->where('姓名', $sender)
                ->value('email');
            if ($email !== null) {
                // dd($email);
                $message->to($email, 'Test Default')->subject('RE:請確認單耗資料');
                $message->bcc('Vincent6_Yeh@pegatroncorp.com');
                // $message->bcc('Tony_Tseng@pegatroncorp.com');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            } else {
                // $message->to('Tony_Tseng@pegatroncorp.com')->subject('無信箱');
                $message->to('Vincent6_Yeh@pegatroncorp.com')->subject('無信箱');
                $message->from('Consumables_Management_No-Reply@pegatroncorp.com', 'Consumables Management_No-Reply');
            } // if else
        });
    } // sendcheckconsume
} // MonthController

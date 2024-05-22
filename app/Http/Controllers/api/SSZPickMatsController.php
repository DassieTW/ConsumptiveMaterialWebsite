<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Date;
use DateTime;
use Illuminate\Http\Request;
use Exception;

class SSZPickMatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDataFromMIS(Request $request)
    {
        \Log::channel('dbquerys')->info('---------------------------MIS--------------------------');
        \Log::channel('dbquerys')->info(json_encode($request->post()));
        \Log::channel('dbquerys')->info('---------------------------MIS--------------------------');

        try {
            $path = public_path('MIS_API.json');
            if (!file_exists($path)) {
                $newFile = fopen(public_path() . "/MIS_API.json", "w");
                fclose($newFile);
            } // if

            $mytime = \Carbon\Carbon::now();
            file_put_contents($path, "");
            file_put_contents($path, $mytime->toDateTimeString() . " " . json_encode($request->post()));
        } catch (Exception $e) {
            dd($e); // dump error
        } // try - catch

        return \Response::json(['message' => 'Data Has Been Received', 'Status' => '000'], 200);
    } // storeDataFromMIS

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

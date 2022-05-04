<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checking_inventory;
use App\Models\客戶別;
use App\Models\發料部門;
use App\Models\製程;
use App\Models\領用原因;
use App\Models\領用部門;
use App\Models\廠別;
use App\Models\線別;
use App\Models\機種;
use App\Models\儲位;
use App\Models\退回原因;
use App\Models\O庫;
use App\Models\ConsumptiveMaterial;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use Session;
use Route;
use Carbon\Carbon;

use App\Services\InventoryCheckService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class CheckingInventoryController extends Controller
{
    private $service;
    public function __construct(InventoryCheckService $inventoryCheckService)
    {
        $this->service = $inventoryCheckService;
    } // constructor

    public function dbSearch(Request $request)
    {
        $rules = [
            'texBox' => [],
        ];

        $this->validate($request, $rules);
        $fetchedData = [];
        // dd($request->input('isDetailed')); // test
        if( $request->input('isDetailed') === "true" ) { // if coming from result page, its a detailed search
            $fetchedData = $this->service->fetchInventCheckRecordWithDetailedConditions($request);
        } // if
        else {
            $fetchedData = $this->service->fetchInventCheckRecord($request);
        } // else

        // dd($fetchedData); // test

        if (count($fetchedData) === 0) { // return 420 if the search result length is 0
            return \Response::json(['message' => __('checkInvLang.no_results_found')], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['data' => $fetchedData]/* Status code here default is 200 ok*/);
        } // else

    } // dbSearch

    public function dbSearchWithinTimeRange(Request $request)
    {
        $rules = [
            'texBox' => [],
        ];

        $this->validate($request, $rules);
        $temp  = $request->input('timeRange');
        $formatStr = $request->input('formatStr');
        $pieces = explode(" ", $temp);
        // dd($pieces[2] . " " . $formatStr ) ; // test
        $rangeStrFrom = Carbon::parse($pieces[0])->format('Y-m-d H:i:s.v'); // starting date string
        $rangeObjFrom = Carbon::createFromFormat('Y-m-d H:i:s.v', $rangeStrFrom)->subDay()->endOfDay() ; // covert to datetime obj
        $rangeStrTo = Carbon::parse($pieces[2])->format('Y-m-d H:i:s.v'); // ending date string
        $rangeObjTo = Carbon::createFromFormat('Y-m-d H:i:s.v', $rangeStrTo)->addDay()->startOfDay() ; // covert to datetime obj
        $fetchedData = $this->service->fetchInventCheckRecordWithinTimeRange($request, $rangeObjFrom, $rangeObjTo);
        // dd($rangeObjFrom . " " . $rangeObjTo ) ; // test
        if (count($fetchedData) === 0) { // return 420 if the search result length is 0
            return \Response::json(['message' => __('checkInvLang.no_results_found')], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['data' => $fetchedData]/* Status code here default is 200 ok*/);
        } // else

    } // dbSearchWithinTimeRange

    public function updateChecking(Request $request)
    {
        $rules = [
            'checkk' => ['required', 'regex:/^(\+|-)?\d+$/'],
        ];

        $this->validate($request, $rules);
        $updateDone = $this->service->updateInventCheckRecord($request);

        if ($updateDone) {
            return \Response::json(['message' => 'Update Success !']/* Status code here default is 200 ok*/);
        } // if no results are found
        else { // return 420 if updated failed
            return \Response::json(['message' => 'Update Failed !'], 420/* Status code here default is 200 ok*/);
        } // else

    } // updateChecking

    public function createTable(Request $request)
    {
        $createDone = $this->service->createTableService($request);

        if ($createDone) {
            return \Response::json(['message' => 'Create Success !']/* Status code here default is 200 ok*/);
        } // if no results are found
        else { // return 420 if updated failed
            return \Response::json(['message' => 'Create Failed !'], 420/* Status code here default is 200 ok*/);
        } // else

    } // createTable

    public function getCreators(Request $request)
    {
        $fetchedCreators = $this->service->fetchCreators($request);

        if (count($fetchedCreators) === 0) { // return 420 if the search result length is 0
            return \Response::json(['message' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['data' => $fetchedCreators]/* Status code here default is 200 ok*/);
        } // else
    } // getCreators

    public function setContinue(Request $request)
    {
        $fetchedCreators = $this->service->fetchCreators($request);

        $request->session()->put('tableName', $request->tableName);
        if (!$request->session()->has('tableName')) { // return 420 if not done
            return \Response::json(['message' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['message' => 'put to session!']/* Status code here default is 200 ok*/);
        } // else

    } // setContinue


} // end of CheckingInventoryController class

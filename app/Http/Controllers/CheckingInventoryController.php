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
            'texBox' => ['required'],
        ];

        $this->validate($request, $rules);
        $fetchedData = $this->service->fetchInventCheckRecord($request);

        if (count($fetchedData) === 0) { // return 420 if the search result length is 0
            return \Response::json(['messgae' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json([ 'data' => $fetchedData ]/* Status code here default is 200 ok*/);
        } // else

    } // dbSearch

    public function updateChecking(Request $request)
    {
        $rules = [
            'checkk' => ['required', 'regex:/^(\+|-)?\d+$/'],
        ];

        $this->validate($request, $rules);
        $updateDone = $this->service->updateInventCheckRecord($request);

        if (!$updateDone) { // return 420 if the search result length is 0
            return \Response::json(['messgae' => 'Update Failed !'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json([ 'messgae' => 'Update Success !' ]/* Status code here default is 200 ok*/);
        } // else

    } // updateChecking


} // end of CheckingInventoryController class

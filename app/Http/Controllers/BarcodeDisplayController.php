<?php

namespace App\Http\Controllers;

use App\Services\BarWebDisplay;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class BarcodeDisplayController extends Controller
{
    private $service;
    public function __construct(BarWebDisplay $barWebDisplay)
    {
        $this->service = $barWebDisplay;
    } // constructor

    public function delTempImg(Request $request)
    {
        // We are collecting all data submitting via Ajax
        $input = $request->all();

        /*
          $post = new Post;  // using model to save data to db
          $post->name = $input['name'];
          $post->description = $input['description'];
          $post->status = $input['status'];
          $post->save();
        */

        if ($request->boolean('DelorNot') && $request->boolean('isISN')) { // DelorNot is true and isISN is true
            unlink(storage_path('app/public/barcodeImg/' . \Session::getId() . '.png'));
            $request->session()->forget('imgg');
        } // if
        else if ($request->boolean('DelorNot') && !$request->boolean('isISN')) { // DelorNot is true and it is a loc pic
            unlink(storage_path('app/public/barcodeImg/' . \Session::getId() . '-2.png'));
            $request->session()->forget('imgg2');
        } // if else if


        // Sending json response to client
        return \Response::json(['message' => 'temp img delete successful !']); // Status code here
    }

    /**
     * go back when post
     */
    public function postBack(Request $request)
    {
        $rules = [
            'barcode1' => ['required', 'regex:/[0-9A-Za-z]{4,4}/'],
            'barcode2' => ['required', 'regex:/[a-zA-Z0-9]{7,7}/'],
        ];


        $this->validate($request, $rules);
        $img = $this->service->drawBarcode($request);
        $request->session()->put('imgg', $img);
        return \Response::json(['message' => 'isn barcode generated successful !']); // Status code here

    } // postBack

    public function postBack_loc(Request $request)
    {

        $rules = [
            'barcode3' => ['required', 'regex:/[a-zA-Z0-9._%+-]{1,11}/'],
        ];

        $this->validate($request, $rules);
        $img = $this->service->drawBarcode($request);
        $request->session()->put('imgg2', $img);
        return \Response::json(['message' => 'loc barcode generated successful !']); // Status code here

    } // postBack_loc

    public function batchUpload(Request $request)
    {
        if (isset($_FILES['batchUp'])) {
            try {
                // $fileName = $_FILES['batchUp']['name'] ; // test
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                    !isset($_FILES['batchUp']['error']) ||
                    is_array($_FILES['batchUp']['error'])
                ) {
                    throw new \RuntimeException('Invalid parameters.');
                } // if
                // Check $_FILES['batchUp']['error'] value.
                switch ($_FILES['batchUp']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new \RuntimeException('No file sent.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new \RuntimeException('Exceeded filesize limit.');
                    default:
                        throw new \RuntimeException('Unknown errors.');
                }

                // You should also check filesize here.
                if ($_FILES['batchUp']['size'] > 1000000) {
                    throw new \RuntimeException('Exceeded filesize limit.');
                } // if

                // DO NOT TRUST $_FILES['batchUp']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $extensions = array("xls", "xlsx", "xlm", "xla", "xlc", "xlt", "xlw", "csv");
                $result = array($request->file('batchUp')->getClientOriginalExtension());
                if (in_array($result[0], $extensions)) {
                    $ext = array_search(
                        $result[0],
                        array(
                            'xls' => 'xls',
                            'xlsx' => 'xlsx',
                            'csv' => 'csv',
                            'xlm' => 'xlm',
                        )
                    );
                } else {
                    throw new \RuntimeException('Invalid file format.');
                } // if else

                // somehow I dont have application/vnd.openxmlformats-officedocument.spreadsheetml.sheet in FILEINFO_MIME_TYPE
                // $finfo = new \finfo(FILEINFO_MIME_TYPE);   // go to php.ini and enable the extension=fileinfo (uncomment the line)
                // if (false === $ext = array_search(
                //     $finfo->file($_FILES['batchUp']['tmp_name']),
                //     array(
                //         'xls' => 'application/vnd.ms-excel',
                //         'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                //         'csv' => 'text/csv',
                //     ),
                //     true
                // )) {
                //     throw new \RuntimeException('Invalid file format.');
                // } // if

                // You should name it uniquely.
                // DO NOT USE $_FILES['batchUp']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                $save = public_path('upload'); // get the public/upload folder
                //                       NOTE! is the system using "/" or "\" ????
                //                        echo $save; // test
                // chmod(getcwd(), 0777);
                // chmod($save, 0777);
                $fileName = sprintf('%s.%s', sha1_file($_FILES['batchUp']['tmp_name']), $ext); // file name

                if (!move_uploaded_file(
                    $_FILES['batchUp']['tmp_name'],
                    sprintf(
                        $save . '/%s.%s',
                        sha1_file($_FILES['batchUp']['tmp_name']),
                        $ext
                    )
                )) {
                    throw new \RuntimeException('Failed to move uploaded file.');
                } // if

                return \Response::json(['message' => 'file upload was successful !', 'filename' => $fileName]); // Status code here
            } catch (\RuntimeException $e) {
                return \Response::json(['message' => $e->getMessage()], 420); // Status code here
            } // try catch

            return \Response::json(['message' => 'file upload was successful !', 'filename' => $fileName]); // Status code here
        } // if a file is uploaded
        else {
            return \Response::json(['message' => __('validation.required')], 469); // Status code here
        } // else 

    } // batchUpload

    public function decompose(Request $request)
    {
        $fileName = public_path('upload') . '/' . $request->input('fileName');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);
        //                    echo $fileName; // test
        $worksheet = $spreadsheet->getActiveSheet();

        $spreadsheetBarcodesArray = array();
        $spreadsheetBarcodesArray['isn'] = array();
        $spreadsheetBarcodesArray['name'] = array();
        $spreadsheetBarcodesArray['loc'] = array();
        $countingStars = 1;
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            //    even if a cell value is not set.
            // For 'TRUE', we loop through cells
            //    only when their value is set.
            // If this method is not called,
            //    the default value is 'false'.
            foreach ($cellIterator as $cell) {
                if ($countingStars === 1) {
                    if ($cell->getValue() !== null) {
                        $spreadsheetBarcodesArray['isn'][] = $cell->getValue();
                    } // if   
                } // if
                else if ($countingStars === 2) {
                    $spreadsheetBarcodesArray['name'][] = $cell->getValue();  // names can be null
                } // else if
                else { // $countingStars === 3
                    if ($cell->getValue() !== null) {
                        $spreadsheetBarcodesArray['loc'][] = $cell->getValue();
                    } // if
                } // else 

                $countingStars++;
            } // foreach

            $countingStars = 1;
        } // outer foreach

        array_splice($spreadsheetBarcodesArray['isn'], 0, 1); // remove the column name
        array_splice($spreadsheetBarcodesArray['name'], 0, 1); // remove the column name
        array_splice($spreadsheetBarcodesArray['loc'], 0, 1); // remove the column name

        \File::delete($fileName); // delete the file

        return \Response::json([
            'isnSheet' => $spreadsheetBarcodesArray['isn'],
            'nameSheet' => $spreadsheetBarcodesArray['name'],
            'locSheet' => $spreadsheetBarcodesArray['loc']
        ]/* Status code here default is 200 ok*/);
    } // decompose

    public function printBarcode(Request $request)
    {
        // dd($_POST['isnArray']); // test
        $request->session()->put('isnArray', $_POST['isnArray']);
        $request->session()->put('isnNameArray', $_POST['isnNameArray']);
        $request->session()->put('isnSepCount', $_POST['isnSepCount']);

        $request->session()->put('locArray', $_POST['locArray']);
        $request->session()->put('locSepCount', $_POST['locSepCount']);

        $this->service->drawABunchofBarcodes($request);
        return \Response::json(['message' => 'loc barcode generated successful !']); // Status code here
    } // printBarcode

    public function cleanupAllBarcodes(Request $request)
    {
        // We are collecting all data submitting via Ajax

        if (\Session::has('isnSepCount') && \Session::get('isnSepCount') !== "" && count(\Session::get('isnSepCount')) > 0) {
            for ($a = 0; $a < count(\Session::get('isnSepCount')); $a++) {
                unlink(storage_path('app/public/barcodeImg/' . \Session::getId() . '--isn--' . $a . '.png'));
            } // for

            $request->session()->forget('isnSepCount');
            $request->session()->forget('isnArray');
            $request->session()->forget('isnNameArray');
        } // if

        if (\Session::has('locSepCount') && \Session::get('locSepCount') !== "" && count(\Session::get('locSepCount')) > 0) {
            for ($a = 0; $a < count(\Session::get('locSepCount')); $a++) {
                unlink(storage_path('app/public/barcodeImg/' . \Session::getId() . '--loc--' . $a . '-2.png'));
            } // for

            $request->session()->forget('locSepCount');
            $request->session()->forget('locArray');
        } // if else if


        // Sending json response to client
        return \Response::json(['message' => 'all temp img delete successful !']); // Status code here
    } // cleanupAllBarcodes

    public function searchISN(Request $request)
    {
        // We are collecting all data submitting via Ajax

        $rules = [
            'searchIn' => ['required', 'regex:/[a-zA-Z0-9._%+-]{1,12}/'],
        ];

        $this->validate($request, $rules);
        $fetchedData = $this->service->searchISNinDB($request);
        if (count($fetchedData) === 0) { // return 420 if the search result length is 0
            return \Response::json(['messgae' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['data' => $fetchedData]/* Status code here default is 200 ok*/);
        } // else

    } // searchISN

    public function searchLoc(Request $request)
    {
        // We are collecting all data submitting via Ajax

        $rules = [
            'searchIn' => ['required', 'regex:/[a-zA-Z0-9._%+-]{1,12}/'],
        ];

        $this->validate($request, $rules);
        $fetchedData = $this->service->searchLocinDB($request);
        if (count($fetchedData) === 0) { // return 420 if the search result length is 0
            return \Response::json(['messgae' => 'No Results Found!'], 420/* Status code here default is 200 ok*/);
        } // if no results are found
        else {
            return \Response::json(['data' => $fetchedData]/* Status code here default is 200 ok*/);
        } // else

    } // searchLoc

} // end of class

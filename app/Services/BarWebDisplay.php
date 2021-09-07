<?php

namespace App\Services;

use Illuminate\Http\Request;

class BarWebDisplay
{
    /**
     * draw the barcode from form ( POST )
     */
    public function drawBarcode(Request $request)
    {
        if (filter_input(INPUT_POST, 'isIsn', FILTER_VALIDATE_BOOLEAN) === true) { // isn pic
            // set Barcode39 object
            $barcode1 = trim(filter_input(INPUT_POST, 'barcode1', FILTER_SANITIZE_STRING));
            if (null !== filter_input(INPUT_POST, 'barcode2', FILTER_SANITIZE_STRING)) {
                $barcode2 = trim(filter_input(INPUT_POST, 'barcode2', FILTER_SANITIZE_STRING));
            } // if
            $productName = filter_input(INPUT_POST, 'pName', FILTER_SANITIZE_STRING);
            $isThisIsn = "true";
            $toSession = filter_input(INPUT_POST, 'toSess', FILTER_SANITIZE_STRING);
            $fName = $_POST['fName'];


            $bc = new Barcode39($barcode1 . "-" . $barcode2);
            $bc->setUseSession($toSession);
            $bc->setMaterialName($productName);
            $bc->setIsItISN($isThisIsn);
            return $bc->draw($request, $fName);
        } // if
        else if (filter_input(INPUT_POST, 'isIsn', FILTER_VALIDATE_BOOLEAN) === false) { // loc pic
            // set Barcode39 object
            $barcode3 = trim(filter_input(INPUT_POST, 'barcode3', FILTER_SANITIZE_STRING));
            $isThisIsn = "false";
            $toSession = filter_input(INPUT_POST, 'toSess', FILTER_SANITIZE_STRING);
            $fName = $_POST['fName'];


            $bc = new Barcode39($barcode3);
            $bc->setUseSession($toSession);
            $bc->setIsItISN($isThisIsn);
            return $bc->draw($request, $fName);
        } //else if

        return false;
    } // public function drawBarcode

    public function drawABunchofBarcodes(Request $request)
    {
        $isnArray = $request->session()->get('isnArray');
        $isnNameArray = $request->session()->get('isnNameArray');
        $isnSepCount = $request->session()->get('isnSepCount');

        $locArray = $request->session()->get('locArray');
        $locSepCount = $request->session()->get('locSepCount');

        for ($a = 0; $a < count($isnArray); $a++) {
            // set Barcode39 object
            $barcodeCom = $isnArray[$a];
            $productName = $isnNameArray[$a];
            $fName = $_POST['sID'] . '--isn--' . $a;
            $bc = new Barcode39($barcodeCom);
            $bc->setUseSession('false');
            $bc->setMaterialName($productName);
            $bc->setIsItISN('true');
            $bc->draw($request, $fName);
        } // for

        for ($a = 0; $a < count($locArray); $a++) {
            // set Barcode39 object
            $barcodeCom2 = $locArray[$a];
            $fName = $_POST['sID'] . '--loc--' . $a;
            $bc = new Barcode39($barcodeCom2);
            $bc->setUseSession('false');
            $bc->setIsItISN('false');
            $bc->draw($request, $fName);
        } // for

        return false;
    } // drawABunchofBarcodes

} // BarWebDisplay

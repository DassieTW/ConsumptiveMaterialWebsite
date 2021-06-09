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
        if (null !== filter_input(INPUT_POST, 'barcode1', FILTER_SANITIZE_STRING)) {
            // set Barcode39 object
            $barcode1 = trim(filter_input(INPUT_POST, 'barcode1', FILTER_SANITIZE_STRING));
            if (null !== filter_input(INPUT_POST, 'barcode2', FILTER_SANITIZE_STRING)) {
                $barcode2 = trim(filter_input(INPUT_POST, 'barcode2', FILTER_SANITIZE_STRING));
            } // if
            $productName = filter_input(INPUT_POST, 'pName', FILTER_SANITIZE_STRING);
            $isThisIsn = filter_input(INPUT_POST, 'isIsn', FILTER_SANITIZE_STRING);
            $toSession = filter_input(INPUT_POST, 'toSess', FILTER_SANITIZE_STRING);
            $fName = $_POST['fName'] ;

            if ($isThisIsn === 'false') {
                $bc = new Barcode39($barcode1);
                $bc->setUseSession($toSession);
                $bc->setMaterialName($productName);
                $bc->setIsItISN($isThisIsn);
                return $bc->draw($request, $fName);
            } else {
                $bc = new Barcode39($barcode1 . "-" . $barcode2);
                $bc->setUseSession($toSession);
                $bc->setMaterialName($productName);
                $bc->setIsItISN($isThisIsn);
                return $bc->draw($request, $fName);
            }
        } // if

        if (null !== filter_input(INPUT_GET, 'barComplete', FILTER_SANITIZE_STRING)) {
            // set Barcode39 object
            $barcodeCom = filter_input(INPUT_GET, 'barComplete', FILTER_SANITIZE_STRING);
            $productName = filter_input(INPUT_GET, 'pName', FILTER_SANITIZE_STRING);
            $isThisIsn = filter_input(INPUT_GET, 'isIsn', FILTER_SANITIZE_STRING);
            $toSession = filter_input(INPUT_GET, 'toSess', FILTER_SANITIZE_STRING);
            $fName = $_POST['fName'] ;
            $bc = new Barcode39($barcodeCom);
            $bc->setUseSession($toSession);
            $bc->setMaterialName($productName);
            $bc->setIsItISN($isThisIsn);
            return $bc->draw($request, $fName);
        } // if

        return false;
    } // public function drawBarcode
} // BarWebDisplay

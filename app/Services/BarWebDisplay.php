<?php
namespace App\Services;

class BarWebDisplay
{
    /**
     * draw the barcode from url ( GET )
     */
    public function drawBarcode()
    {
        if (null !== filter_input(INPUT_GET, 'barcode1', FILTER_SANITIZE_STRING)) {
            // set Barcode39 object
            $barcode1 = trim(filter_input(INPUT_GET, 'barcode1', FILTER_SANITIZE_STRING));
            if (null !== filter_input(INPUT_GET, 'barcode2', FILTER_SANITIZE_STRING)) {
                $barcode2 = trim(filter_input(INPUT_GET, 'barcode2', FILTER_SANITIZE_STRING));
            } // if
            $productName = filter_input(INPUT_GET, 'pName', FILTER_SANITIZE_STRING);
            $isThisIsn = filter_input(INPUT_GET, 'isIsn', FILTER_SANITIZE_STRING);
            $toSession = filter_input(INPUT_GET, 'toSess', FILTER_SANITIZE_STRING);

            if ($isThisIsn === 'false') {
                $bc = new Barcode39($barcode1);
                $bc->setUseSession($toSession);
                $bc->setMaterialName($productName);
                $bc->setIsItISN($isThisIsn);
                $bc->draw();
            } else {
                $bc = new Barcode39($barcode1 . "-" . $barcode2);
                $bc->setUseSession($toSession);
                $bc->setMaterialName($productName);
                $bc->setIsItISN($isThisIsn);
                $bc->draw();
            }
        } // if

        if (null !== filter_input(INPUT_GET, 'barComplete', FILTER_SANITIZE_STRING)) {
            // set Barcode39 object
            $barcodeCom = filter_input(INPUT_GET, 'barComplete', FILTER_SANITIZE_STRING);
            $productName = filter_input(INPUT_GET, 'pName', FILTER_SANITIZE_STRING);
            $isThisIsn = filter_input(INPUT_GET, 'isIsn', FILTER_SANITIZE_STRING);
            $toSession = filter_input(INPUT_GET, 'toSess', FILTER_SANITIZE_STRING);
            $bc = new Barcode39($barcodeCom);
            $bc->setUseSession($toSession);
            $bc->setMaterialName($productName);
            $bc->setIsItISN($isThisIsn);
            $bc->draw();
        } // if
    } // public function drawBarcode
} // BarWebDisplay

<?php

namespace App\Http\Controllers;
use App\Services\BarWebDisplay;
use Illuminate\Http\Request;

class BarcodeDisplayController extends Controller
{
    private $service;
    public function __construct(BarWebDisplay $barWebDisplay)
    {
        $this->service = $barWebDisplay;
    } // constructor

    /**
     * draw barcode
     */
    public function drawBarcode( Request $request )
    {
        $this->service->drawBarcode($request);
        return back();
    }
}

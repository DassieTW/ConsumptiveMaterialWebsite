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

    /**
     * go back when post
     */
    public function postBack(Request $request)
    {
        $input = $request->all();

        $rules = [
            'barcode1' => ['required', 'regex:/[0-9A-Za-z]{4,4}/'],
            'barcode2' => ['required', 'regex:/[a-zA-Z0-9]{7,7}/'],
            'pName' => 'required',
        ];

        $customMessages = [
            'barcode1.required' => 'This field is required.',
            'barcode2.required' => 'This field is required.',
            'pName.required' => 'Please enter a name.',
            'barcode1.regex' => 'Format error.',
            'barcode2.regex' => 'Format error.',
        ];

        $this->validate($request, $rules, $customMessages);
        $img = $this->service->drawBarcode($request) ;
        return redirect()->back()->with('imgg', $img);
        
    } // postBack
} // end of class

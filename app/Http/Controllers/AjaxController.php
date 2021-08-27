<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function myPost()
    {
        return view('home'); // unuse
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

        if( $request->boolean('DelorNot') && $request->boolean('isISN') ) { // DelorNot is true and isISN is true
            unlink( storage_path( 'app/public/barcodeImg/' . \Session::getId() . '.png') ) ;
            $request->session()->forget('imgg');
        } // if
        else if( $request->boolean('DelorNot') && !$request->boolean('isISN') ) {// DelorNot is true and it is a loc pic
            unlink( storage_path( 'app/public/barcodeImg/' . \Session::getId() . '-2.png') ) ;
            $request->session()->forget('imgg2');
        } // if else if

        
        // Sending json response to client
        return response()->json([
            "status" => true,
            "data" => 'done'
        ]);
    }
}

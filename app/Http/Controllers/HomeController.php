<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MeiliSearch\Client;

class HomeController extends Controller
{

    private $searchClient;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // set up the keyword database and search client
        $this->searchClient = new Client(env('MEILISEARCH_HOST'));

        $movies_json = file_get_contents(__DIR__ . '/../../../resources/meilisearchWords/movies.json');
        $movies = json_decode($movies_json);

        $this->searchClient->index('movies')->addDocuments($movies);
    } // constructor

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    } // index

    public function insiteSearch(Request $request)
    {
        // dd($this->client->getTask(0)); // test if the document added successfully
        // dd($request->input('inputStr')); // test
        $searchResult = $this->searchClient->index('movies')->search($request->input('inputStr'))->toJSON();
        // dd($searchResult); // test
        return \Response::json(['data' => $searchResult]/* Status code here default is 200 ok*/);
    } // using MeiliSearch
}

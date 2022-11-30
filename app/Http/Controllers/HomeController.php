<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MeiliSearch\Client;

class HomeController extends Controller
{

    private $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client('http://meilisearch:7700');

        $movies_json = file_get_contents(__DIR__ . '/../../../resources/meilisearchWords/movies.json');
        $movies = json_decode($movies_json);

        $this->client->index('movies')->addDocuments($movies);
    }

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
        
        dd($this->client->getTask(0)); // test if the document added successfully

        $this->client->index('movies')->search($request->input('select'));

    } // using MeiliSearch
}

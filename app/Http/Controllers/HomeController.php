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

        $keywords_json = file_get_contents(__DIR__ . '/../../../resources/meilisearchWords/keywords.json');
        $titles = json_decode($keywords_json);

        $this->searchClient->index('titles')->addDocuments($titles);

        $this->searchClient->index('titles')->updateSearchableAttributes([
            'en_title',
            'cn_title',
            'tw_title',
            'en_parentTitle',
            'cn_parentTitle',
            'tw_parentTitle'
        ]);
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
        $searchResult = $this->searchClient->index('titles')->search(
            $request->input('inputStr'),
            ['attributesToHighlight' => ['en_title', 'cn_title', 'tw_title', 'en_parentTitle', 'cn_parentTitle', 'tw_parentTitle']]
        )->toJSON();
        // dd($searchResult); // test
        return \Response::json(['data' => $searchResult, 'lang' => app()->getLocale()]/* Status code here default is 200 ok*/);
    } // using MeiliSearch
}

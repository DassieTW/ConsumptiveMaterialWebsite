<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use MeiliSearch\Client;
use MeiliSearch\Contracts\IndexesQuery;

class CleanUpMeiliSearchIdexes extends Command
{
    private $searchClient;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up users that did not log in within the last 6 months.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn("Now Running [meilisearch:clear]");
        try {
            $this->searchClient = new Client(env('MEILISEARCH_HOST'));
            $this->searchClient->deleteAllIndexes();

            $keywords_json = file_get_contents(__DIR__ . '/../../../resources/meilisearchWords/keywords.json');
            $titles = json_decode($keywords_json);
            $this->searchClient->index('titles')->addDocuments($titles);
            $this->searchClient->index('titles')->updateSearchableAttributes([
                'en_title',
                'tw_title',
                'cn_title',
                'vi_title',
                'id_title'
            ]);
            $this->info("[meilisearch:clear] Command executed successfully!");
        } catch (Exception $e) {
            \Log::channel('errorlog')->error(" execution failed with error : " . $e->getMessage());
            $this->error("Clean Up MeiliSearch Idexes execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class GetExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom command for getting the latest exchange rate.';

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
        // $databaseArray = config('database_list.databases');
        try {
            $this->warn("Now Fetching Exchange Rate...");
            $path = public_path('exchange_rate.json');
            file_put_contents($path, "");
            $response = \Http::get('http://api.exchangeratesapi.io/v1/latest?access_key=' . env('Rate_API_Key'));
            file_put_contents($path, $response);
        } catch (Exception $e) {
            $this->error("Command execution failed with error : " . $e->getMessage());
        } // try - catch
        return 0;
    } // handle
}

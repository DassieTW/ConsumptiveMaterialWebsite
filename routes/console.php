<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear', function() {

    exec('rm -f ' . base_path('*.log'));
    
    $this->comment('npm, composer, etc ... Logs have been cleared!');
    $this->comment('but not laravel.log, plz run "php artisan log:clear"');
    
})->describe('Clear log files');
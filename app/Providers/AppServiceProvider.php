<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Services\MailService;
use App\Helpers;
use View;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        } // if

        $this->app->singleton( MailService::class, function($app) {
            return new MailService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::share('current_user', Auth::User()); // share user data with all views

        DB::listen(function ($query) {            // listen on db querys and write logs in storage/log/dbquerys
            \Log::channel('dbquerys')->info(['sql' => $query->sql, 'bindings' => $query->bindings, 'time' => $query->time]);
            // $query->bindings
            // $query->time
        });
    }
}

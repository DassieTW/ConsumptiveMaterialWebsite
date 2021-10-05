<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;


class SetDBConnection
{
    public function handle(Request $request, Closure $next)
    {
        if (session('database')) {
            \Config::set('database.connections.' . env("DB_CONNECTION") . '.database', session('database'));
            \DB::purge(env("DB_CONNECTION"));
        } // if

        return $next($request);
    }
}

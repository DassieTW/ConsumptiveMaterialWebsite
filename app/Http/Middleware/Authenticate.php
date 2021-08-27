<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
<<<<<<< HEAD
=======
use Session;
>>>>>>> 0827tony

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
<<<<<<< HEAD
        if (! $request->expectsJson()) {
            return route('login');
        }
=======
        return url('/member/login');
>>>>>>> 0827tony
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class SetLocale
{
    const SESSION_KEY = 'locale';
    const LOCALES = ['en', 'zh-TW', 'zh-CN'];

    public function handle(Request $request, Closure $next)
    {
        /** @var Session $session */
        $session = $request->getSession();

        if (!$session->has(self::SESSION_KEY)) {
            $session->put(self::SESSION_KEY, $request->getPreferredLanguage(self::LOCALES));
        } // if

        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, self::LOCALES)) {
                $session->put(self::SESSION_KEY, $lang);
            } // if
        } // if

        app()->setLocale($session->get(self::SESSION_KEY));
        // app()->setLocale('zh-TW'); // test
        return $next($request);
    } // handle
} // end class

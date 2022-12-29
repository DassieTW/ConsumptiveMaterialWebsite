<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/api.php'));

            Route::prefix('api/basic')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/basic.php'));

            Route::prefix('api/inbound')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/inbound.php'));

            Route::prefix('api/outbound')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/outbound.php'));

            Route::prefix('api/month')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/month.php'));

            Route::prefix('api/obound')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/obound.php'));

            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web/web.php'));

            // 載入 人員 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('member')
                ->group(base_path('routes/web/member.php'));

            // 載入 基礎訊息 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('basic')
                ->group(base_path('routes/web/basic.php'));

            // 載入 月請購 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('month')
                ->group(base_path('routes/web/month.php'));

            // 載入 outbound routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('outbound')
                ->group(base_path('routes/web/outbound.php'));

            // 載入 inbound routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('inbound')
                ->group(base_path('routes/web/inbound.php'));

            // 載入 盤點 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('checking')
                ->group(base_path('routes/web/checking.php'));

            //載入 O庫 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('obound')
                ->group(base_path('routes/web/obound.php'));

            //載入 報警系統 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('call')
                ->group(base_path('routes/web/call.php'));

            // 載入 條碼 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('barcode')
                ->group(base_path('routes/web/barcode.php'));

            // 載入 BU耗材管理 routes
            Route::middleware(['web', 'auth', 'locale'])
                ->namespace($this->namespace)
                ->prefix('bu')
                ->group(base_path('routes/web/bu.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
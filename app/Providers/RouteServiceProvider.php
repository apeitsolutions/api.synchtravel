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
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    protected $namespace = 'App\Http\Controllers';
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/website_endpoints.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/flight_routes.php'));    
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/appapi.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/Show_Interest_routes.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/Show_B2BAgents_routes.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/subscriptions_Package.php'));
            
            Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/hasanat_Coin.php'));
            
            Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
        });


        resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');

parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}

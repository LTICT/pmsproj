<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
 * Register the logger instance in the container.
 *
 * @return MyCustomWriter
 */
/*protected function registerLogger()
{
    $this->app->instance('log', $log = new MyCustomWriter(
        new Monolog($this->app->environment()), $app['events'])
    );

    $log->dontLogInfoOnEnvironmnets(['production', 'staging', 'other']);
    return $log;
}*/
}

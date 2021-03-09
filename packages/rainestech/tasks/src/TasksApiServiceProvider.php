<?php

namespace Rainestech\Tasks;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TasksApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerRoutes() {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }

    protected function routeConfiguration() {
        return [
            'prefix' => 'api/v1',
            'middleware' => ['api'],
        ];
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $this->registerRoutes();
    }
}

<?php

namespace Rainestech\AdminApi;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Rainestech\AdminApi\Controllers\StorageApiController;

class AdminApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });

        Route::get('/fs/dl/{file}', [StorageApiController::class, 'getFile'])->name('fs.get.file');
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => 'api',
            'middleware' => ['api'],
        ];
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
    }
}

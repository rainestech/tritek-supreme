<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

use Rainestech\AdminApi\Controllers\BankListController;
use Rainestech\AdminApi\Controllers\LgasApiController;
use Rainestech\AdminApi\Controllers\NavController;
use Rainestech\AdminApi\Controllers\RoleApiController;
use Rainestech\AdminApi\Controllers\StatesApiController;
use Rainestech\AdminApi\Controllers\StorageApiController;
use Rainestech\AdminApi\Controllers\UserApiController;

// Storage
Route::get('/fs/dl/{file}', [StorageApiController::class, 'getFile'])->name('fs.get.file');

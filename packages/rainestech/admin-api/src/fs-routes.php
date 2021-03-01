<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

use Rainestech\AdminApi\Controllers\StorageApiController;

// Storage
Route::get('/fs/dl/{file}', [StorageApiController::class, 'getFile'])->name('fs.get.file');

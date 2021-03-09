<?php


use Rainestech\Tasks\Controllers\TaskController;

Route::group(['middleware' => 'admin.api', 'prefix' => 'tasks'], function () {
    Route::get('/cid/{id}', [TaskController::class, 'getTaskByChannel'])->middleware('access:ROLE_TASKS')->name('tasks.index');
    Route::post('/', [TaskController::class, 'saveTask'])->middleware('access:ROLE_TASKS')->name('tasks.save');
    Route::put('/', [TaskController::class, 'editTask'])->middleware('access:ROLE_TASKS')->name('tasks.edit');
    Route::delete('/remove/{id}', [TaskController::class, 'deleteTask'])->middleware('access:ROLE_TASKS')->name('tasks.delete');
    Route::post('/attach_file', [TaskController::class, 'attachFile'])->middleware('access:ROLE_TASKS')->name('tasks.attach.file');
    Route::post('/detach_file', [TaskController::class, 'detachFile'])->middleware('access:ROLE_TASKS')->name('tasks.detach.file');
    Route::post('/reorder', [TaskController::class, 'updatePosition'])->middleware('access:ROLE_TASKS')->name('tasks.order');
});

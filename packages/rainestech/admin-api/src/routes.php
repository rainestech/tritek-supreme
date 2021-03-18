<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

use Rainestech\AdminApi\Controllers\ContactController;
use Rainestech\AdminApi\Controllers\DocumentApiController;
use Rainestech\AdminApi\Controllers\NavController;
use Rainestech\AdminApi\Controllers\NotificationTemplateController;
use Rainestech\AdminApi\Controllers\RoleApiController;
use Rainestech\AdminApi\Controllers\StorageApiController;
use Rainestech\AdminApi\Controllers\UserApiController;

Route::get('/init', [NavController::class, 'init'])->name('nav.init');

// Storage
Route::get('/v1/fs/dl/{file}', [StorageApiController::class, 'getFile'])->name('admin.fs.get.file');
Route::get('/v1/fs/uid/{id}', [StorageApiController::class, 'getFs'])->name('admin.fs.get.record');
Route::post('/v1/fs', [StorageApiController::class, 'save'])->name('fs.save');
Route::get('/v1/docs/dl/{file}', [DocumentApiController::class, 'getFile'])->name('documents.get.file.public');

Route::group(['prefix' => 'users'], function () {
    Route::post('/changePwd', [UserApiController::class, 'changePassword'])->name('users.change.password');
    Route::post('/password/reset', [UserApiController::class, 'resetPassword'])->name('users.reset.password');
    Route::post('/recover', [UserApiController::class, 'recoverPassword'])->name('users.recover.password');
    Route::post('/verification', [UserApiController::class, 'verification'])->name('users.recover.verify');
    Route::post('/regenerate-token/{id?}', [UserApiController::class, 'regenerateToken'])->name('users.regenerate.token');
    Route::post('/register/verify', [UserApiController::class, 'registerVerify'])->name('users.register.verify');
    Route::post('/login', [UserApiController::class, 'login'])->name('login');
});

Route::group(['prefix' => 'contact'], function () {
    Route::get('/', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/', [ContactController::class, 'save'])->name('contact.save');
    Route::put('/', [ContactController::class, 'update'])->name('contact.edit');
    Route::delete('/remove/{id}', [ContactController::class, 'remove'])->name('contact.remove');
});

Route::group(['middleware' => 'admin.api'], function () {
    Route::get('/users', [UserApiController::class, 'index'])->name('users');
    Route::get('/users/s/{username}/{type}', [UserApiController::class, 'search'])->name('users.search');
    Route::get('/users/me', [UserApiController::class, 'me'])->name('users.me');
    Route::get('/users/logout', [UserApiController::class, 'logout'])->name('logout');
    Route::put('/users/edit/role/{id}', [UserApiController::class, 'editRole'])->name('users.edit.role')->middleware('access:ROLE_ADMIN_ACCESS');
    Route::put('/users/edit', [UserApiController::class, 'editMe'])->name('users.edit.me')->middleware('access:ROLE_PROFILE');
    Route::post('/users/renew', [UserApiController::class, 'renew'])->name('users.token.renew');

    // Storage
    Route::put('/v1/fs', [StorageApiController::class, 'edit'])->name('fs.edit');
    Route::delete('/v1/fs/rem/{id}', [StorageApiController::class, 'delete'])->name('fs.delete');

    Route::group(['prefix' => 'users', 'middleware' => ['access:ROLE_ADMIN_USERS']], function () {
        Route::delete('/remove/{id}', [UserApiController::class, 'remove'])->name('users.delete');
        Route::post('/register', [UserApiController::class, 'register'])->name('users.register');
        Route::put('/{id}', [UserApiController::class, 'editUser'])->name('users.edit');
    });

    Route::get('/users/roles', [RoleApiController::class, 'index'])->name('admin.api.roles');
    Route::get('/users/roles/domains', [RoleApiController::class, 'domains'])->name('admin.api.modules');
    Route::get('/users/roles/privileges', [RoleApiController::class, 'privileges'])->name('admin.api.privileges');

    Route::group(['prefix' => 'users/roles', 'middleware' => ['admin.api', 'access:ROLE_ADMIN_ROLES']], function () {
        Route::post('/default', [RoleApiController::class, 'defaultRole'])->name('admin.api.roles.default');
        Route::post('/save', [RoleApiController::class, 'save'])->name('admin.api.role.save');
        Route::delete('/remove/{id}', [RoleApiController::class, 'destroy'])->name('admin.api.roles.delete');
    });

    // Navigation Items
    Route::get('/v1/navItems/{app}', [NavController::class, 'index'])->name('nav.items');

    // Notifications
    Route::group(['prefix' => 'v1/notifications', 'middleware' => 'access:ROLE_NOTIFICATIONS'], function () {
        Route::get('/mail', [NotificationTemplateController::class, 'mailIndex'])->name('notifications.mail.index');
        Route::get('/sms', [NotificationTemplateController::class, 'smsIndex'])->name('notifications.sms.index');
        Route::post('/mail', [NotificationTemplateController::class, 'saveMailTemplate'])->name('notifications.mail.save');
        Route::post('/sms', [NotificationTemplateController::class, 'saveSmsTemplate'])->name('notifications.sms.save');
        Route::put('/mail', [NotificationTemplateController::class, 'editMailTemplate'])->name('notifications.mail.edit');
        Route::put('/sms', [NotificationTemplateController::class, 'editSmsTemplate'])->name('notifications.sms.edit');
        Route::delete('/sms/rem/{id}', [NotificationTemplateController::class, 'deleteSmsTemp'])->name('notifications.sms.delete');
        Route::delete('/mail/rem/{id}', [NotificationTemplateController::class, 'deleteMailTemp'])->name('notifications.mail.delete');
    });

    // Documents
    Route::group(['prefix' => 'v1/docs', 'middleware' => 'access:ROLE_DOCS'], function () {
//        Route::get('/dl/{file}', [DocumentApiController::class, 'getFile'])->name('documents.get.file');
        Route::post('/file', [DocumentApiController::class, 'save'])->name('doc.save.file');
        Route::put('/file', [DocumentApiController::class, 'edit'])->name('doc.edit.file');
        Route::get('/', [DocumentApiController::class, 'getMyDocuments'])->name('documents.get');
        Route::get('/uid/{id}', [DocumentApiController::class, 'getUserDocuments'])->name('documents.user.get');
        Route::post('/', [DocumentApiController::class, 'saveDoc'])->name('doc.save');
        Route::put('/', [DocumentApiController::class, 'editDoc'])->name('doc.edit');
        Route::delete('/remove/{id}', [DocumentApiController::class, 'deleteDocument'])->name('doc.delete');
    });

});

<?php

use Rainestech\Personnel\Controllers\ProfileController;

Route::group(['middleware' => 'admin.api', 'prefix' => 'profile'], function () {
    Route::get('/recruiters', [ProfileController::class, 'recruiters'])->middleware('access:ROLE_ADMIN_RECRUITERS')->name('recruiter.index');
    Route::get('/', [ProfileController::class, 'getMyProfile'])->middleware('access:ROLE_PROFILE')->name('recruiter.index');
    Route::get('/candidates', [ProfileController::class, 'candidates'])->name('candidates.index');
    Route::get('/rid/{id}', [ProfileController::class, 'getRecruiterByUserID'])->name('recruiter.user.id');
    Route::get('/cid/{id}', [ProfileController::class, 'getCandidatesByUserID'])->name('candidate.user.id');
    Route::post('/recruiters', [ProfileController::class, 'saveRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('recruiter.save');
    Route::put('/recruiters', [ProfileController::class, 'updateRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('recruiter.update');
    Route::post('/candidates', [ProfileController::class, 'saveCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.save');
    Route::put('/candidates', [ProfileController::class, 'updateCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.update');
    Route::delete('/recruiters/remove/{id}', [ProfileController::class, 'removeRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.save');
    Route::delete('/candidates/remove/{id}', [ProfileController::class, 'removeCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.update');
});

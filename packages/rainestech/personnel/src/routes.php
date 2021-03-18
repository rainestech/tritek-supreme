<?php

use Rainestech\Personnel\Controllers\ChatController;
use Rainestech\Personnel\Controllers\ProfileController;
use Rainestech\Personnel\Controllers\RequestController;
use Rainestech\Personnel\Controllers\SearchController;

Route::group(['middleware' => 'admin.api', 'prefix' => 'profile'], function () {
    Route::get('/skillset', [ProfileController::class, 'getSkillSets'])->name('skillset.index');
    Route::get('/recruiters', [ProfileController::class, 'recruiters'])->middleware('access:ROLE_ADMIN_RECRUITERS')->name('recruiter.index');
    Route::get('/', [ProfileController::class, 'getMyProfile'])->middleware('access:ROLE_PROFILE')->name('profile.index');
    Route::get('/candidates', [ProfileController::class, 'candidates'])->name('candidates.index');
    Route::get('/rid/{id}', [ProfileController::class, 'getRecruiterByUserID'])->name('recruiter.user.id');
    Route::get('/cid/{id}', [ProfileController::class, 'getCandidatesByUserID'])->name('candidate.user.id');
    Route::post('/recruiters', [ProfileController::class, 'saveRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('recruiter.save');
    Route::put('/recruiters', [ProfileController::class, 'updateRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('recruiter.update');
    Route::post('/candidates', [ProfileController::class, 'saveCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.save');
    Route::put('/candidates', [ProfileController::class, 'updateCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.update');
    Route::delete('/recruiter/remove/{id}', [ProfileController::class, 'removeRecruiter'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('recruiter.delete');
    Route::delete('/candidate/remove/{id}', [ProfileController::class, 'removeCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('candidate.delete');
});

Route::group(['middleware' => 'admin.api'], function () {
    Route::delete('/rup', [ProfileController::class, 'deleteProfile'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_RECRUITERS')->name('profile.delete');
});

Route::group(['middleware' => 'admin.api', 'prefix' => 'chats'], function () {
    Route::get('/friends', [ChatController::class, 'friends'])->middleware('access:ROLE_CHAT')->name('chats.friends');
    Route::get('/contacts', [ChatController::class, 'contacts'])->middleware('access:ROLE_CHAT')->name('chats.contacts');
    Route::post('/friends', [ChatController::class, 'saveFriends'])->middleware('access:ROLE_CHAT')->name('chats.friends.save');
});

Route::group(['middleware' => 'admin.api', 'prefix' => 'search'], function () {
    Route::post('/', [SearchController::class, 'search'])->middleware('access:ROLE_SEARCH')->name('search.candidates');
    Route::get('/shortlist', [SearchController::class, 'myShortlist'])->middleware('access:ROLE_SEARCH,ROLE_SHORTLIST')->name('search.shortlist');
    Route::get('/terms/{id}', [SearchController::class, 'getSearchAnalytics'])->middleware('access:ROLE_SEARCH,ROLE_SHORTLIST,ROLE_ADMIN')->name('search.analytics');
    Route::get('/shortlist/{id}', [SearchController::class, 'getShortlist'])->middleware('access:ROLE_SEARCH,ROLE_SHORTLIST,ROLE_ADMIN')->name('search.recruiter.shortlist');
    Route::post('/shortlist', [SearchController::class, 'shortList'])->middleware('access:ROLE_SEARCH,ROLE_SHORTLIST')->name('search.shortlist.actions');

    Route::get('/token', [SearchController::class, 'getToken'])->middleware('access:ROLE_SEARCH')->name('search.token');
});


// Candidates Recruiter Requests
Route::group(['middleware' => 'admin.api', 'prefix' => 'request'], function () {
    Route::get('/', [RequestController::class, 'index'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.index');
    Route::get('/list', [RequestController::class, 'indexList'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.list');
    Route::get('/rid/{id}', [RequestController::class, 'getRecruiterRequest'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.recruiter');
    Route::get('/list/rid/{id}', [RequestController::class, 'getRecruiterRequestList'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.recruiter.list');
    Route::post('/', [RequestController::class, 'save'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.save');
    Route::post('/all', [RequestController::class, 'saveAll'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.save.all');
    Route::delete('/remove/{id}', [RequestController::class, 'delete'])->middleware('access:ROLE_ADMIN_RECRUITERS,ROLE_SEARCH')->name('request.delete');
});

// Snippets Requests
Route::group(['middleware' => 'admin.api', 'prefix' => 'snippets'], function () {
    Route::get('/', [SearchController::class, 'getSnippets'])->name('snippet.index');
    Route::get('/name/{name}', [SearchController::class, 'getSnippet'])->name('snippet.name');
    Route::post('/', [SearchController::class, 'saveSnippet'])->name('snippet.save');
    Route::delete('/remove/{id}', [SearchController::class, 'deleteSnippet'])->name('snippet.delete');
});



<?php

use Rainestech\Personnel\Controllers\ChannelController;
use Rainestech\Personnel\Controllers\ChatController;
use Rainestech\Personnel\Controllers\CommentsController;
use Rainestech\Personnel\Controllers\ProfileController;

Route::group(['middleware' => 'admin.api', 'prefix' => 'profile'], function () {
    Route::get('/', [ProfileController::class, 'getMyProfile'])->middleware('access:ROLE_PROFILE')->name('profile.my');
    Route::get('/candidates', [ProfileController::class, 'candidates'])->name('candidates.index');
    Route::get('/cid/{id}', [ProfileController::class, 'getCandidatesByUserID'])->name('candidate.user.id');
    Route::post('/candidates', [ProfileController::class, 'saveCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_CANDIDATES')->name('candidate.save');
    Route::put('/candidates', [ProfileController::class, 'updateCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_CANDIDATES')->name('candidate.update');
    Route::delete('/candidates/remove/{id}', [ProfileController::class, 'removeCandidate'])->middleware('access:ROLE_PROFILE,ROLE_ADMIN_CANDIDATES')->name('candidate.remove');
});

Route::group(['middleware' => 'admin.api', 'prefix' => 'chats'], function () {
    Route::get('/friends', [ChatController::class, 'friends'])->middleware('access:ROLE_CHAT')->name('chats.friends');
    Route::get('/contacts', [ChatController::class, 'contacts'])->middleware('access:ROLE_CHAT')->name('chats.contacts');
    Route::post('/friends', [ChatController::class, 'saveFriends'])->middleware('access:ROLE_CHAT')->name('chats.friends.save');
});

Route::group(['middleware' => 'admin.api', 'prefix' => 'channels'], function () {
    Route::get('/', [ChannelController::class, 'index'])->middleware('access:ROLE_CHANNELS')->name('channels.index');
    Route::get('/my', [ChannelController::class, 'myChannels'])->middleware('access:ROLE_CHANNELS')->name('channels.my');
    Route::get('/cid/{id}', [ChannelController::class, 'getChannel'])->middleware('access:ROLE_CHANNELS')->name('channels.id');
    Route::post('/', [ChannelController::class, 'saveChannel'])->middleware('access:ROLE_CHANNELS')->name('channels.save');
    Route::put('/', [ChannelController::class, 'editChannel'])->middleware('access:ROLE_CHANNELS')->name('channels.edit');
    Route::delete('/remove/{id}', [ChannelController::class, 'delete'])->middleware('access:ROLE_CHANNELS')->name('channels.delete');
});

Route::group(['middleware' => 'admin.api', 'prefix' => 'comments'], function () {
    Route::post('/', [CommentsController::class, 'saveComments'])->middleware('access:ROLE_TASKS,ROLE_CALENDAR')->name('comments.save');
    Route::delete('/remove/{id}', [CommentsController::class, 'deleteComment'])->middleware('access:ROLE_TASKS,ROLE_CALENDAR')->name('comments.delete');
});

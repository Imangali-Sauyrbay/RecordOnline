<?php

Route::get('/profile', 'ProfileController@index')->name('profile.index');
Route::get('/records', 'RecordController@index')->name('record.index');
Route::get('/record', 'RecordController@create')->name('record.create');

Route::get('/record/lits', 'RecordController@search')->name('record.lits');
Route::get('/record/{sub}/count', 'RecordController@count')->name('records.count');

Route::redirect('/', '/record', 301);

Route::post('/profile/data', 'ProfileController@updateUserData')->name('profile.update.data');
Route::post('/profile/password', 'ProfileController@updatePassword')->name('profile.update.password');
Route::post('/record/store', 'RecordController@store')->name('record.store');
Route::post('/record/{record}/status/update', 'RecordStatusController@update')->name('record.status.update');

Auth::routes();


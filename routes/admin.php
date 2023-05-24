<?php

Voyager::routes();

Route::prefix('group')->group(function () {
    Route::get('delete', 'DeleteUsersController@index')->name('delete.users.index');
    Route::post('delete', 'DeleteUsersController@delete')->name('delete.users.delete');
    Route::post('restore', 'DeleteUsersController@restore')->name('delete.users.restore');
});

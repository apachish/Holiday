<?php

Route::middleware('api')
    ->prefix('api/holiday')
    ->namespace('Balea\Holiday\App\Http\Controllers')
    ->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('get/{year}/{mounth?}', 'HolidayController@getHoliday')->name("get.holiday");
        });
    });

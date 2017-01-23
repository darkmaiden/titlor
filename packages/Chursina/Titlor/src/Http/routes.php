<?php

    // Titlor routes

    Route::get('/titlor', [
        'uses' => 'Chursina\Titlor\Http\Controllers\TitlorController@getTitlor',
        'as' => 'titlor.index',
        'middleware' => 'web'
    ]);

    Route::post('/titlor/manage', [
        'uses' => 'Chursina\Titlor\Http\Controllers\TitlorController@postTitlorManage',
        'as' => 'titlor.manage',
        'middleware' => 'web'
    ]);

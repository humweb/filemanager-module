<?php

Route::group(['middleware' => 'auth'], function () {
    //GET
    Route::get('admin/filemanager', [
        'middleware' => 'allow.only:filemanager.list',
        'as'         => 'get.admin.filemanager.index',
        'uses'       => 'AdminFilemanagerController@getIndex',
    ]);

    Route::get('admin/filemanager/create/{id?}', [
        'middleware' => 'allow.only:filemanager.create',
        'as'         => 'get.admin.filemanager.create',
        'uses'       => 'AdminFilemanagerController@getCreate',
    ]);

    Route::get('admin/filemanager/edit/{id}', [
        'middleware' => 'allow.only:filemanager.edit',
        'message'    => 'You don\'t have permission to edit filemanager.',
        'as'         => 'get.admin.filemanager.edit',
        'uses'       => 'AdminFilemanagerController@getEdit',
    ]);

    Route::get('admin/filemanager/delete/{id}', [
        'middleware' => 'allow.only:filemanager.delete',
        'as'         => 'get.admin.filemanager.delete',
        'uses'       => 'AdminFilemanagerController@getDelete',
    ]);

    //POST
    Route::post('admin/filemanager/create/{id?}', [
        'middleware' => 'allow.only:filemanager.create',
        'as'         => 'post.admin.filemanager.create',
        'uses'       => 'AdminFilemanagerController@postCreate',
    ]);

    Route::post('admin/filemanager/edit/{id}', [
        'middleware' => 'allow.only:filemanager.edit',
        'as'         => 'post.admin.filemanager.edit',
        'uses'       => 'AdminFilemanagerController@postEdit',
    ]);

    Route::post('admin/filemanager/sort', [
        'middleware' => 'allow.only:filemanager.edit',
        'as'         => 'post.admin.filemanager.sort',
        'uses'       => 'AdminFilemanagerController@postSort'
    ]);
});

Route::any('{path?}', [
    'as'   => 'get.filemanager.index',
    'uses' => 'FilemanagerController@getIndex',
]);

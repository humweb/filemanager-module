<?php

// Show LFM
Route::group(['prefix' => 'admin/filemanager', 'middleware' => 'auth'], function () {

    // Index
    //Route::get('/', 'ApiController@getListing');

    Route::get('', [
        'as'   => 'get.filemanager.index',
        'uses' => 'MainController@getIndex',
    ]);

    //    // folders
    Route::any('/newfolder', [
        'as'   => 'get.filemanager.folder.new',
        'uses' => 'FolderController@getAddfolder',
    ]);
    Route::any('/deletefolder', [
        'as'   => 'get.filemanager.folder.delete',
        'uses' => 'FolderController@getDeletefolder',
    ]);
    Route::any('/folders', [
        'as'   => 'get.filemanager.folders',
        'uses' => 'FolderController@getFolders',
    ]);

    Route::any('upload', [
        'as'   => 'get.filemanager.folders',
        'uses' => 'UploadController@upload',
    ]);

    //    Route::controller('', 'ApiController');
    //
    //    // Upload
    //    Route::any('/upload', 'UploadController@upload');
    //
    //    // List images & files
    Route::get('/jsonimages', 'ItemsController@getImages');
    Route::get('/jsonfiles', 'ItemsController@getFiles');
    //
    //    // folders
    //    Route::get('/newfolder', 'FolderController@getAddfolder');
    //    Route::get('/deletefolder', 'FolderController@getDeletefolder');
    //    Route::get('/folders', 'FolderController@getFolders');
    //
    //    // crop
    //    Route::get('/crop', 'CropController@getCrop');
    //    Route::get('/cropimage', 'CropController@getCropimage');
    //
    //    // rename
    //    Route::get('/rename', 'RenameController@getRename');
    //
    //    // scale/resize
    //    Route::get('/resize', 'ResizeController@getResize');
    //    Route::get('/doresize', 'ResizeController@performResize');
    //
    //    // download
    //    Route::get('/download', 'DownloadController@getDownload');
    //
    //    // delete
    //    Route::get('/delete', 'DeleteController@getDelete');
});

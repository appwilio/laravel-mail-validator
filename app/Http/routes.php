<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view("index");
});

Route::group(["prefix" => "validator"], function () {
    Route::get('/list', ["as" => "validator.list", "uses" => "ValidatorsController@validatorsList"]);
    Route::get('/pending', ["as" => "validator.pending", "uses" => "ValidatorsController@havePending"]);
});

Route::group(["prefix" => "upload"], function () {
    Route::get('/list', ["as" => "upload.list", "uses" => "UploadController@uploadsList"]);
    Route::post('/', ["as" => "upload.do", "uses" => "UploadController@doUpload"]);
    Route::get('/validation-status-renew/{id}', ["as" => "upload.renew_validation", "uses" => "UploadController@renewValidation"]);
});

Route::group(["prefix" => "excludes"], function () {
    Route::get('/list', ["as" => "excludes.list", "uses" => "ExcludeController@excludesList"]);
    Route::post('/', ["as" => "excludes.create", "uses" => "ExcludeController@create"]);
    Route::get('/drop/{id}', ["as" => "excludes.drop", "uses" => "ExcludeController@drop"]);
});

Route::group(["prefix" => "export"], function () {
    Route::get('/list', ["as" => "export.list", "uses" => "ExportController@exportsList"]);
    Route::get('/make', ["as" => "export.make", "uses" => "ExportController@fullExport"]);
    Route::post('/make', ["as" => "export.make", "uses" => "ExportController@filteredExport"]);
});

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
});

Route::group(["prefix" => "upload"], function () {
    Route::get('/list', ["as" => "upload.list", "uses" => "UploadController@uploadsList"]);
    Route::post('/', ["as" => "upload.do", "uses" => "UploadController@doUpload"]);
});

Route::group(["prefix" => "excludes"], function () {
    Route::get('/list', ["as" => "excludes.list", "uses" => "ExcludeController@excludesList"]);
    Route::post('/', ["as" => "excludes.create", "uses" => "ExcludeController@create"]);
});

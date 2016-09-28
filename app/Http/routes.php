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
    return redirect()->route("validator.index");
});

Route::group(["prefix" => "validator"], function () {
    Route::get('/', ["as" => "validator.index", "uses" => "ValidatorsController@index"]);
    Route::get('/list', ["as" => "validator.list", "uses" => "ValidatorsController@validatorsList"]);
});

Route::group(["prefix" => "upload"], function () {
    Route::get('/', ["as" => "upload.form", "uses" => "UploadController@showForm"]);
    Route::post('/', "UploadController@doUpload");
});

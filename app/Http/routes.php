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
    return redirect()->route("email.index");
});


Route::group(["prefix" => "upload"], function () {
    Route::get('/', ["as" => "upload.form", "uses" => "UploadController@showForm"]);
    Route::post('/', "UploadController@doUpload");
});

Route::group(["prefix" => "email"], function () {
    Route::get('/', ["as" => "email.index", "uses" => "EmailController@index"]);
    Route::get('/paginate/{limit?}',  ["as" => "email.paginate", "uses" => "EmailController@paginate"]);
});

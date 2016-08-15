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
    return redirect()->route("upload.form");
});


Route::group(["prefix" => "upload"], function () {
    Route::get('/', ["as" => "upload.form", function () {
        return view('upload');
    }]);
    Route::post('/', "UploadController@upload");
});

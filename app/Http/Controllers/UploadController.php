<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class UploadController extends Controller
{
    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => 'required|email'
        ]);
        if ($validator->fails()) {
            return redirect()->route("upload.form")->withInput()->withErrors($validator);
        }
        return redirect()->route("upload.form");
    }
}

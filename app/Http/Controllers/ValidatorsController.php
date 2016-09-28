<?php

namespace App\Http\Controllers;

use App\Contracts\Validator;
use Cache;

class ValidatorsController extends Controller
{
    public function index() {
        return view("list.validators");
    }

    public function validatorsList(){
        $validators = array_merge(config("validators.domain"), config("validators.email"));
        return array_map(function($validatorClass){
            /**
             * @var $validator Validator
             */
            $validator = new $validatorClass();
            return [
                "key" => $validator->getName(),
                "valid" => Cache::get(prefix_valid($validatorClass), 0),
                "invalid" => Cache::get(prefix_invalid($validatorClass), 0),
                "pending" => Cache::get(prefix_pending($validatorClass), 0)
            ];
        }, $validators);
    }
}

<?php

namespace App\Http\Controllers;

use App\Contracts\Validator;
use Cache;

class ValidatorsController extends Controller
{
    public function validatorsList(){
        return
            array_merge(
                array_map(function($validatorClass){
                    /**
                     * @var $validator Validator
                     */
                    $validator = new $validatorClass();
                    return [
                        "type" => "domain",
                        "key" => $validator->getName(),
                        "valid" => Cache::get(prefix_valid($validatorClass), 0),
                        "invalid" => Cache::get(prefix_invalid($validatorClass), 0),
                        "pending" => Cache::get(prefix_pending($validatorClass), 0)
                    ];
                }, config("validators.domain")),
                array_map(function($validatorClass){
                    /**
                     * @var $validator Validator
                     */
                    $validator = new $validatorClass();
                    return [
                        "type" => "email",
                        "key" => $validator->getName(),
                        "valid" => Cache::get(prefix_valid($validatorClass), 0),
                        "invalid" => Cache::get(prefix_invalid($validatorClass), 0),
                        "pending" => Cache::get(prefix_pending($validatorClass), 0)
                    ];
                }, config("validators.email"))
            );
    }

    public function havePending(){
        return response()->json(validationPending());
    }
}

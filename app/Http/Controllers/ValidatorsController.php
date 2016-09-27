<?php

namespace App\Http\Controllers;

use App\Contracts\Validator;
use App\Domain\Repository\EmailRepository;
use Cache;

class ValidatorsController extends Controller
{
    /**
     * @var EmailRepository
     */
    protected $domainValidations;

    public function index() {
        return view("list.emails");
    }

    public function validatorsList(){

        $domainValidators = config("validators.domain");
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
        }, $domainValidators);
    }
}

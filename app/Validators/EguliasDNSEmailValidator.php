<?php

namespace App\Validators;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 14:03
 */
class EguliasDNSEmailValidator  implements \App\Contracts\EmailValidator
{

    protected $validator;

    /**
     * EmailValidator constructor.
     */
    public function __construct()
    {
        $this->validator = EmailValidator::class;
    }

    function getName()
    {
        return "dns";
    }

    function validate($email)
    {
        /**
         * @var $validate EmailValidator
         */
        $validate = new $this->validator();
        return $validate->isValid($email,  new DNSCheckValidation());
    }
}
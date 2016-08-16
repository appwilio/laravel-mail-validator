<?php

namespace App\Validators;

use SmtpValidatorEmail\ValidatorEmail;
/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 14:03
 */
class DdtracewebEmailValidator  implements \App\Contracts\EmailValidator
{

    protected $validator;
    protected $from = "xyz@xyzxyz.com";

    /**
     * DdtracewebEmailValidator constructor.
     */
    public function __construct()
    {
        $this->validator = ValidatorEmail::class;
    }

    function getName()
    {
        return "ddtraceweb";
    }

    function validate($email)
    {
        /**
         * @var $validate ValidatorEmail
         */
        $validate = new $this->validator([$email], $this->from);
        return $validate->getResults();
    }
}
<?php

namespace App\Validators\Email;

use App\Contracts\Validator;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 14:03
 */
class EguliasRFCEmailValidator  implements Validator
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
        return "rfc";
    }

    function validate($email)
    {
        /**
         * @var $validate EmailValidator
         */
        $validate = new $this->validator();
        return $validate->isValid($email, new RFCValidation());
    }
}
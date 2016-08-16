<?php

namespace App\Validators;

use Lavoiesl\Validation\Email\SMTP\Validator;
/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 14:03
 */
class LavoieslEmailValidator  implements \App\Contracts\EmailValidator
{

    protected $validator;
    protected $from = "xyz@xyzxyz.com";

    /**
     * DdtracewebEmailValidator constructor.
     */
    public function __construct()
    {
        $this->validator = Validator::class;
    }

    function getName()
    {
        return "lavoiesl";
    }

    function validate($email)
    {
        /**
         * @var $validate Validator
         */
        $validate = new $this->validator($this->from);
        return $validate->validate($email);
    }
}
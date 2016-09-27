<?php

namespace App\Validators\Domain;

use App\Contracts\Validator;

class MXRecordValidator implements Validator
{

    function getName()
    {
        return "MX";
    }

    function validate($domain)
    {
        return checkdnsrr($domain, 'MX');
    }
}
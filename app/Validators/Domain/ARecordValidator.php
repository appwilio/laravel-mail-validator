<?php

namespace App\Validators\Domain;

use App\Contracts\Validator;

class ARecordValidator implements Validator
{

    function getName()
    {
        return "A";
    }

    function validate($domain)
    {
        return checkdnsrr($domain, 'A');
    }
}
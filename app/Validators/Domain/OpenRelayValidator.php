<?php

namespace App\Validators\Domain;

use App\Contracts\Validator;
use App\Lib\Validators\Smtp\SmtpSocket;

class OpenRelayValidator implements Validator
{

    function getName()
    {
        return "openrelay";
    }

    function validate($domain)
    {
        $smtp = new SmtpSocket();
        $smtp->setPort("25");
        try {
            $valid = !($smtp->setHost($domain)->check(config("validators.dummy.from"), config("validators.dummy.to")));
        } catch (\Exception $e) {
            $valid = true;
        }
        return $valid;
    }
}
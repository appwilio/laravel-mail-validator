<?php
namespace App\Lib\Validators\Smtp;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Validation\EmailValidation;

/**
 * Created by PhpStorm.
 * User: m
 * Date: 22.08.16
 * Time: 16:39
 */
class SmtpValidation implements EmailValidation
{
    /**
     * @param string $email
     * @param EmailLexer $emailLexer
     */
    public function isValid($email, EmailLexer $emailLexer)
    {
        // TODO: Implement isValid() method.
    }

    /**
     * @return string
     */
    public function getError()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getWarnings()
    {
        return "";
    }


}
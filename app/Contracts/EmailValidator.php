<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 13:59
 */

namespace App\Contracts;


interface EmailValidator
{
    /**
     * @return string
     */
    function getName();

    /**
     * @param string $email
     * @return bool
     */
    function validate($email);
}
<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 16.08.16
 * Time: 13:59
 */

namespace App\Contracts;


interface Validator
{
    /**
     * @return string
     */
    function getName();

    /**
     * @param string $value
     * @return bool
     */
    function validate($value);
}
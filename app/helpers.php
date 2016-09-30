<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 16:16
 */

/**
 * @param string $email
 * @return bool|string
 */
function sanitize_email($email) {
    $email = trim($email);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return strtolower($email);
    } else {
        return false;
    }
}

function prefix($value, $prefix) {
    return "{$prefix}_{$value}";
}

function prefix_valid($value){
    return prefix($value, config("validators.valid", "valid"));
}

function prefix_invalid($value){
    return prefix($value, config("validators.valid", "invalid"));
}

function prefix_pending($value){
    return prefix($value, config("validators.valid", "pending"));
}


function validators_assoc($key) {
    $result = [];
    $validators = config("validators.{$key}");
    if(is_array($validators)) {
        foreach ($validators as $validatorClass) {
            /**
             * @var $validator \App\Contracts\Validator
             */
            $validator = new $validatorClass();
            $result[$validator->getName()] = $validatorClass;
        }
    }
    return $result;
}
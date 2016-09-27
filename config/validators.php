<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 26.09.16
 * Time: 16:34
 */
return [
    "domain" => [
        \App\Validators\Domain\ARecordValidator::class,
        \App\Validators\Domain\MXRecordValidator::class,
        \App\Validators\Domain\OpenRelayValidator::class
    ],
    "email" => [
        \App\Validators\Email\EguliasRFCEmailValidator::class
    ],
    "dummy" => [
        "from" => "botn8@yandex.ru",
        "to" => 'artarn@appwilio.com'
    ]
];
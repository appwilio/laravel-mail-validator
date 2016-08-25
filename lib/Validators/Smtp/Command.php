<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 23.08.16
 * Time: 12:10
 */

namespace App\Lib\Validators\Smtp;

class Command
{
    /**
     * @type integer
     */
    protected $expectCode;
    /**
     * @var callable
     */
    protected $formatter;

    /**
     * Command constructor.
     * @param int $expectCode
     * @param callable $formatter
     */
    public function __construct($expectCode, callable $formatter)
    {
        $this->expectCode = $expectCode;
        $this->formatter = $formatter;
    }

    /**
     * @return int
     */
    public function getExpectCode()
    {
        return $this->expectCode;
    }

    /**
     * @return callable
     */
    public function format($data)
    {
        $formatter = $this->formatter;
        return $formatter($data);
    }
}
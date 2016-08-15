<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Email;

use App\AbstractRepository;

class EmailRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return Email::class;
    }

}
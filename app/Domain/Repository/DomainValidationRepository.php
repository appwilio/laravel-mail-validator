<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\Contracts\Validator;
use App\Domain\Model\DomainValidation;

class DomainValidationRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return DomainValidation::class;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\AbstractRepository;
use App\Domain\Model\Domain;

class DomainRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return Domain::class;
    }

}
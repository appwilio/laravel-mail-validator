<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\Domain\Model\Export;

class ExportRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return Export::class;
    }
}
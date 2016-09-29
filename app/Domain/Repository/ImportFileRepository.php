<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\Domain\Model\ImportFile;

class ImportFileRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return ImportFile::class;
    }
}
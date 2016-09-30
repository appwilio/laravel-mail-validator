<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;


use App\Domain\Model\Exclude;

class ExcludeRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return Exclude::class;
    }

    public function getPrefixExcludes() {
        return $this->getManyBy("type", Exclude::PREFIX_EXCLUDE);
    }

    public function getSuffixExclude() {
        return $this->getManyBy("type", Exclude::SUFFIX_EXCLUDE);
    }

    public function getTypes(){
        return [
            Exclude::PREFIX_EXCLUDE,
            Exclude::SUFFIX_EXCLUDE
        ];
    }
}
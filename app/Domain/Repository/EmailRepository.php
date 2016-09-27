<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\Contracts\Validator;
use App\Domain\Model\Email;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return Email::class;
    }
    /**
     * @param Validator $validator
     * @param callable $processor function(Builder $result) {
     *      return $result->method();
     * }
     * @return mixed
     */
    public function validCondition(Validator $validator, callable $processor = null) {
        /**
         * @var $collection HasMany
         */
        $collection = $this->model->with("validations", function(Builder $query) use ($validator) {
            $query->where("validator", $validator->getName())->where("valid", true);
        })->get();
        if(null === $processor) {
            return $collection;
        } else {
            return $processor($collection);
        }
    }

    /**
     * @param Validator $validator
     * @param callable $processor function(Builder $result) {
     *      return $result->method();
     * }
     *
     * @return Builder | mixed
     */
    public function invalidCondition(Validator $validator, callable $processor = null) {
        /**
         * @var $collection \Illuminate\Database\Eloquent\Builder
         */
        $collection = $this->model->with(["validations" => function(\Illuminate\Database\Query\Builder $query) use ($validator) {
            $query->where("validator", $validator->getName())->where("valid", true);
        }]);
        if(null === $processor) {
            return $collection->getQuery();
        } else {
            return $processor($collection);
        }

    }
}
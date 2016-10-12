<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;


abstract class ValidationRepository extends AbstractRepository
{
    /**
     * @param $validator string
     */
    public function getPendingCount($validator)
    {
        return $this->model->where("validator", $validator)->where("is_pending", true)->count();
    }

    /**
     * @param $validator string
     * @param $valid boolean
     */
    public function getStatusCount($validator, $valid)
    {
        return $this->model->where("validator", $validator)->where("is_pending", false)->where("valid", $valid)->count();
    }
}
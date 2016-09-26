<?php

namespace App;

use App\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

abstract class AbstractRepository implements Repository
{

    /**
     * @type Model
     */
    protected $model;

    protected $paginateSize = 20;

    /**
     * Specify the entity class name.
     *
     * @return object|string
     */
    abstract protected function getModelClass();

    public function __construct()
    {
        $model = app()->make($this->getModelClass());

        if (!$model instanceof Model) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" must be an instance of "%s"', $this->getModelClass(), Model::class)
            );
        }

        $this->model = $model;
    }

    /**
     * Get all items.
     *
     * @param  array   $columns  columns to select
     * @param  string  $orderBy  column to sort by
     * @param  string  $sort     sort direction
     *
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function all($columns = ['*'], $orderBy = 'created_at', $sort = 'DESC')
    {
        return $this->model->orderBy($orderBy, $sort)->get($columns);
    }


    /**
     * Find a entity by id.
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($id)
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    /**
     * Find a single entity by key value
     *
     * @param  string        $key
     * @param  string|null   $value
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFirstBy($key, $value = null)
    {
        return $this->model->where($key, $value)->first();
    }

    /**
     * Find many entities by key value.
     *
     * @param  string       $key
     * @param  string|null  $value
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getManyBy($key, $value = null, $limit = null)
    {
        $query = $this->model->where($key, $value);
        if($limit) {
            $query->limit((int)$limit);
        }
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function paginate($limit = null) {
        if(null == $limit) {
            $limit = $this->paginateSize;
        }
        return $this->model->with('validations')->paginate($limit);
    }
}

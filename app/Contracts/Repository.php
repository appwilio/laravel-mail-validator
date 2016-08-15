<?php

namespace App\Contracts;

interface Repository
{
    /**
     * Get all items.
     *
     * @param  array   $columns  columns to select
     * @param  string  $orderBy  column to sort by
     * @param  string  $sort     sort direction
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'], $orderBy = 'created_at', $sort = 'DECS');

    /**
     * Find a entity by id.
     *
     * @param  int    $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id);

    /**
     * Find a single entity by key value
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getFirstBy($key, $value = null);

    /**
     * Find many entities by key value.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getManyBy($key, $value = null);
}

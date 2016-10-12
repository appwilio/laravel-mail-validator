<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 15.08.16
 * Time: 15:46
 */

namespace App\Domain\Repository;

use App\Domain\Model\ImportFile;
use Illuminate\Database\Eloquent\Collection;

class ImportFileRepository extends AbstractRepository
{
    protected function getModelClass()
    {
        return ImportFile::class;
    }

    public function importTransformer() {
        return function (ImportFile $file) {
            $response = $file->toArray();
            $response["import_status"] = ($file->finished) ?
                                            "finished" : (
                                                ( $file->updated_at > $file->created_at ) ? "started" : "pending"
                                            );
            $response["validation_status"] = "unknown";
            return $response;
        };
    }

    /**
     * @param $transformer Callable
     * @return Collection
     */
    public function allTransformed(Callable $transformer, $columns = ['*'], $orderBy = 'id', $sort = 'DESC') {
        $preResult = $this->all($columns, $orderBy, $sort);
        return $preResult->map($transformer);
    }

    public function all($columns = ['*'], $orderBy = 'id', $sort = 'DESC')
    {
        return $this->model->withCount("emails")->orderBy($orderBy, $sort)->get($columns);
    }
}
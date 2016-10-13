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

            $response["available"] = false;

            switch ($file->validation_status) {
                case ImportFile::VALIDATION_CHECKING:
                    $response["validation_status"] = "calculating";
                    $response["update_link"] = false;
                    break;
                case ImportFile::VALIDATION_FINISHED:
                    $response["validation_status"] = "finished";
                    $response["update_link"] = false;
                    $response["available"] = true;
                    break;
                case ImportFile::VALIDATION_PENDING:
                    $response["validation_status"] = "in process";
                    $response["update_link"] = route("upload.renew_validation", $file->id);
                    break;
                case ImportFile::VALIDATION_UNKNOWN:
                default:
                    $response["validation_status"] = "unknown";
                    $response["update_link"] = route("upload.renew_validation", $file->id);
            }


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
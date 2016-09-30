<?php

namespace App\Http\Controllers;

use App\Domain\Model\Export;
use App\Domain\Repository\ExportRepository;
use App\Domain\Repository\ImportFileRepository;
use App\Jobs\GenerateExport;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ExportController extends Controller
{
    use DispatchesJobs;

    /**
     * @var $importFiles ImportFileRepository
     */
    protected $exportRepository;

    public function __construct(ExportRepository $repository)
    {
        $this->exportRepository = $repository;
    }

    public function exportsList() {
        $exports = $this->exportRepository->all();

        return response()->json($exports->map(function ($item){
            if($item->finished) {
                $item->name = "<a href='/csv/$item->name'>$item->name</a>";
            }
            return $item;
        }));
    }

    public function doExport(){
        $file_name = md5(time().rand(100, 999)).".csv";

        $export = new Export([
            "name" => $file_name,
            "finished" => false
        ]);
        $export->save();
        $this->dispatch(new GenerateExport($export));
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Domain\Model\Export;
use App\Domain\Repository\ExportRepository;
use App\Domain\Repository\ImportFileRepository;
use App\Jobs\GenerateExport;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

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

    public function fullExport(){
        $file_name = md5(time().rand(100, 999)).".csv";

        $export = new Export([
            "name" => $file_name,
            "finished" => false
        ]);
        $export->save();

        $job = (new GenerateExport($export))->onQueue(config("queue.queues_list.export"));

        $this->dispatch($job);
        return redirect()->back();
    }

    public function filteredExport(Request $r) {
        $file_name = md5(time().rand(100, 999)).".csv";

        $export = new Export([
            "name" => $file_name,
            "finished" => false
        ]);
        $export->save();

        $job = (new GenerateExport(
            $export,
            $r->input("importFile", []),
            $r->input("exclude.prefix", []),
            $r->input("exclude.suffix", [])
        ))->onQueue(config("queue.queues_list.export"));

        $this->dispatch($job);
        return response("", 200);
    }

}

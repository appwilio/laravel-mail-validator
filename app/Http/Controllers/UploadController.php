<?php

namespace App\Http\Controllers;

use App\Domain\Model\ImportFile;
use App\Domain\Repository\ImportFileRepository;
use App\Http\Requests\UploadRequest;
use App\Jobs\ReadImportFile;
use App\Jobs\RenewFileValidationStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UploadController extends Controller
{
    use DispatchesJobs;

    /**
     * @var $importFiles ImportFileRepository
     */
    protected $importFiles;

    public function __construct(ImportFileRepository $repository)
    {
        $this->importFiles = $repository;
    }

    public function uploadsList() {
        return response()->json($this->importFiles->allTransformed($this->importFiles->importTransformer()));
    }

    public function doUpload(UploadRequest $request){
        $file = $request->file("file");
        if(null !== $file) {
            $new_name = md5($file->getClientOriginalName().time()) . "." . $file->getClientOriginalExtension();
            $importFile = new ImportFile([
                "original_name" => $file->getClientOriginalName(),
                "generated_name" => $new_name,
                "lines_processed" => 0
            ]);

            $importFile->save();

            $file->move(config("import.directory"), $new_name);

            $this->dispatch((new ReadImportFile($importFile))->delay(1));

        }
        return redirect()->back();
    }

    public function renewValidation($id){
        $import = $this->importFiles->findById($id);

        $import->validation_status = ImportFile::VALIDATION_CHECKING;
        $import->save();

        $this->dispatch(new RenewFileValidationStatus($import));

        return redirect()->back();
    }
}

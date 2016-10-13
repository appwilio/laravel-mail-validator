<?php

namespace App\Jobs;

use App\Domain\Model\Domain;
use App\Domain\Model\Email;
use App\Domain\Model\ImportFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RenewFileValidationStatus extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var $import ImportFile
     */
    protected $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImportFile $import)
    {
        $this->import = $import;
    }


    protected function setIsPending(){
        $this->import->validation_status = ImportFile::VALIDATION_PENDING;
        $this->import->save();
        $this->delete();
    }

    protected function setFinished(){
        $this->import->validation_status = ImportFile::VALIDATION_FINISHED;
        $this->import->save();
        $this->delete();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pending_email = Email::whereHas("import_files", function ($query) {
            $query->where("import_file_id", $this->import->id);
        })->whereHas("validations", function ($query) {
            $query->where("is_pending", true);
        })->count();

        if($pending_email != 0) {
            $this->setIsPending();
            return;
        }

        $pending_domains = Domain::whereHas("validations", function ($query) {
            $query->where("is_pending", true);
        })->whereHas("emails", function($query){
            $query->whereHas("import_files",  function($query){
                $query->where("import_file_id", $this->import->id);
            });
        })->count();

        if($pending_domains != 0) {
            $this->setIsPending();
            return;
        }

        $this->setFinished();

    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\ReadImportFile;
use Artisan;
use DB;

use App\Domain\Model\Domain;
use App\Domain\Model\DomainValidation;
use App\Domain\Model\Email;
use App\Domain\Model\EmailImport;
use App\Domain\Model\EmailValidation;
use App\Domain\Model\ImportFile;
use App\Domain\Repository\ImportFileRepository;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RerunImport extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:reimport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rerun import files';

    /**
     * @var $importFileRepository ImportFileRepository
     */
    protected $importFileRepository;

    protected $tables_to_truncate = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImportFileRepository $importFileRepository)
    {
        parent::__construct();
        $this->importFileRepository = $importFileRepository;
        $this->tables_to_truncate = [
            "jobs",
            (new EmailImport())->getTable(),
            (new DomainValidation())->getTable(),
            (new Domain())->getTable(),
            (new EmailValidation())->getTable(),
            (new Email())->getTable()
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->tables_to_truncate as $table) {
            $this->info("truncate {$table}");
            DB::table($table)->truncate();
            $this->info("done");
        }
        $this->info("refresh cache");
        Artisan::call('t:rrc');
        $this->info("done");
        $this->importFileRepository->all()->each(function (ImportFile $file) {
           $this->info("reset file {$file->original_name}");
           $file->lines_processed = 0;
           $file->finished = false;
           $file->validation_status = ImportFile::VALIDATION_UNKNOWN;
           $file->save();
           $this->info("done");
           $job = (new ReadImportFile($file))->onQueue(config("queue.queues_list.import"));
           $this->dispatch($job);
        });
        return 0;
    }
}

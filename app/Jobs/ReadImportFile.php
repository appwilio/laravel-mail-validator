<?php

namespace App\Jobs;

use App\Domain\Model\Email;
use App\Domain\Model\ImportFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use League\Csv\Reader;

class ReadImportFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImportFile $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->file;
        $reader = Reader::createFromPath(config("import.directory")."/".$file->generated_name);

        if($file->lines_processed > 0) {
            $reader->setOffset($file->lines_processed);
            $count = $file->lines_processed;
        } else {
            $count = 0;
        }

        $reader->each(function ($row) use (&$count, $file) {
            $count++;

            $email = sanitize_email($row[0]);

            if($email) {
                /**
                 * @var $newEmail Email
                 */
                $newEmail = Email::firstOrCreate(["address"=>$email]);
                $newEmail->import_files()->attach($file->id);
            }

            $file->lines_processed = $count;
            return $file->save();

        });
        $file->finished = true;
        $file->save();
    }
}

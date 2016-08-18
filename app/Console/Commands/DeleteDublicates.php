<?php

namespace App\Console\Commands;

use App\Domain\Email\EmailRepository;
use App\Jobs\ValidateEmail;
use App\Validators\DdtracewebEmailValidator;
use App\Validators\LavoieslEmailValidator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DeleteDublicates extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:dd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command to delete dubles';

    /**
     * @type EmailRepository
     */
    protected $emails;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailRepository $emails)
    {
        parent::__construct();
        $this->emails = $emails;
    }

    private function showStats()
    {
        $memory = memory_get_peak_usage(true);
        $this->comment(sprintf('Выполнено за: %0.2f sec.', round(microtime(true) - LARAVEL_START, 3)));
        $this->comment(sprintf('Потреблено памяти: %0.2f MiB', $memory / (1024 * 1024)));
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->info("start");
        $collection = \App\Domain\Email\Email::groupBy("address")->havingRaw('COUNT(*) > 1')->get();
        if ($collection->isEmpty()) {
            $this->info("clear");
        } else {
            foreach ($collection as $email) {
                $dulicates = \App\Domain\Email\Email::where("address", $email->address)->where('id', '<>', $email->id)->get();
                foreach ($dulicates as $dulicate) {
                    $this->info("Drop {$dulicate->id} {$dulicate->address}");
                    $dulicate->delete();
                }
            }
            $this->showStats();
            $this->info("done");
        }
    }
}

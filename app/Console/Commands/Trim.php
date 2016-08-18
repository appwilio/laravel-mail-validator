<?php

namespace App\Console\Commands;

use App\Domain\Email\EmailRepository;
use App\Jobs\ValidateEmail;
use App\Validators\DdtracewebEmailValidator;
use App\Validators\LavoieslEmailValidator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Trim extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:trim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command to trim emails';

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
        while(true) {
            $emails = $this->emails->getManyBy("trimmed", false, 10000);
            if($emails->isEmpty()) {
                $this->info("no more");
                break;
            }
            foreach ($emails as $email) {
                $candidate = $email->address;
                $address = sanitize_email($candidate);
                $this->info($address);
                if ($address) {
                    $this->info("{$candidate} => {$address}");
                    if($candidate !==$address) {
                        $email->address = $address;
                    }
                    $email->trimmed = true;
                    $email->save();
                } else {
                    $this->info("delete {$candidate}");
                    $email->delete();
                }
                $this->showStats();
                $this->info("go next");
            }
        }
        $this->showStats();
        $this->info("done");
    }
}

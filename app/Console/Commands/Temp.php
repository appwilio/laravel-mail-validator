<?php

namespace App\Console\Commands;

use App\Domain\Email\EmailRepository;
use App\Jobs\ValidateEmail;
use App\Validators\EguliasEmailValidator;
use App\Validators\LavoieslEmailValidator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Temp extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command';

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
        $mails = \App\Domain\Email\Email::whereDoesntHave("validations", function($query) {
                $query->where("validator", "egulias");
            }
        )->limit(40000)->get();

        foreach ($mails as $mail) {
            $this->dispatch(new ValidateEmail($mail, new EguliasEmailValidator()));
        }

        $mails = \App\Domain\Email\Email::whereDoesntHave("validations", function($query) {
            $query->where("validator", "lavoiesl");
            }
        )->limit(40000)->get();

        foreach ($mails as $mail) {
            $this->dispatch(new ValidateEmail($mail, new LavoieslEmailValidator()));
        }

        $this->showStats();
        $this->info("done");

    }
}
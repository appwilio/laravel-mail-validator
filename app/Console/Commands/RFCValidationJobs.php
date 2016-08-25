<?php

namespace App\Console\Commands;

use App\Domain\Email\Email;
use App\Jobs\ValidateEmail;
use App\Validators\EguliasRFCEmailValidator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RFCValidationJobs extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:rfcjobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("start");
        Email::with("validations")->chunk(10000, function ($emails) {
            $this->info("tik");
            foreach ($emails as $email) {
                if (!$email->validations->contains("validator", 'rfc')) {
                    $this->dispatch(new ValidateEmail($email, new EguliasRFCEmailValidator()));
                }
            }
        });
        $this->info("done");
    }
}

<?php

namespace App\Jobs;

use App\Contracts\EmailValidator;
use App\Domain\Email\Email;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ValidateEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @type Email
     */
    protected $email;
    protected $validator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email, EmailValidator $validator)
    {
        $this->email = $email;
        $this->validator = $validator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo PHP_EOL;
        var_dump($this->validator->getName());
        echo PHP_EOL;
        var_dump($this->validator->validate($this->email->address));
        echo PHP_EOL;
    }
}

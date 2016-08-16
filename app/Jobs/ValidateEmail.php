<?php

namespace App\Jobs;

use App\Contracts\EmailValidator;
use App\Domain\Email\Email;
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
        $valid = new \App\Domain\Validation\Validation([
            "validator" => $this->validator->getName()
        ]);
        try {
            $check = $this->validator->validate($this->email->address);
            $valid->valid = $check;
        } catch (\Exception $e) {
            $valid->valid = false;
            $valid->message = $e->getMessage();
        }
        $this->email->validations()->save($valid);
    }
}

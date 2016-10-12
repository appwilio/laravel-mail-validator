<?php

namespace App\Jobs;

use App\Contracts\Validator;
use App\Domain\Model\Email;
use App\Domain\Model\EmailValidation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;

class EmailValidationJobs extends Job
{
    use SerializesModels, DispatchesJobs;

    /**
     * @var $email Email
     */
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (config("validators.email") as $validatorClass) {

            Cache::increment(prefix_pending($validatorClass));

            /**
             * @var $validator Validator
             */
            $validator = new $validatorClass();

            $validation = new EmailValidation([
                "validator" => $validator->getName(),
                "is_pending" => true
            ]);

            $this->email->validations()->save($validation);

            $this->dispatch(new ValidateEmail($this->email, $validator, $validation));
        }
    }
}

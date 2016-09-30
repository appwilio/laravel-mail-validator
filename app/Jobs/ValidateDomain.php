<?php

namespace App\Jobs;

use App\Contracts\Validator;
use App\Domain\Model\Domain;
use App\Domain\Model\EmailValidation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ValidateDomain extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @type Domain
     */
    protected $domain;
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, Validator $validator)
    {
        $this->domain = $domain;

        $this->validator = $validator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $valid = new EmailValidation([
            "validator" => $this->validator->getName()
        ]);
        try {
            $check = $this->validator->validate($this->domain->address);
            $valid->valid = $check;
        } catch (\Exception $e) {
            $valid->valid = false;
            $valid->message = $e->getMessage();
        }
        return $this->domain->validations()->save($valid);
    }
}

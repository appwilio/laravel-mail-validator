<?php

namespace App\Jobs;

use App\Contracts\Validator;
use App\Domain\Model\Domain;
use App\Domain\Model\DomainValidation;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class DomainValidationJobs extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    /**
     * @var $domain Domain
     */
    protected $domain;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (config("validators.domain") as $validatorClass) {
            Cache::increment(prefix_pending($validatorClass));

            /**
             * @var $validator Validator
             */
            $validator = new $validatorClass();

            $validation = new DomainValidation([
                "validator" => $validator->getName(),
                "is_pending" => true
            ]);

            $this->domain->validations()->save($validation);

            $this->dispatch(new ValidateDomain($this->domain, $validator, $validation));
        }
    }
}

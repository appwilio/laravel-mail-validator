<?php

namespace App\Jobs;

use App\Contracts\Validator;
use App\Domain\Model\Domain;
use App\Domain\Model\DomainValidation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

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
        $valid = new DomainValidation([
            "validator" => $this->validator->getName()
        ]);

        $class = get_class($this->validator);

        try {
            $check = $this->validator->validate($this->domain->domain);
            $valid->valid = $check;

        } catch (\Exception $e) {
            $valid->valid = false;
            $valid->message = $e->getMessage();
        }
        if($valid->valid) {
            Cache::increment(prefix_valid($class));
        } else {
            Cache::increment(prefix_invalid($class));
        }
        Cache::decrement(prefix_pending($class));
        $this->domain->validations()->save($valid);
    }
}

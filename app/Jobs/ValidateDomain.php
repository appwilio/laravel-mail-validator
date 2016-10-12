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
     * @var DomainValidation
     */
    protected $validation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, Validator $validator, DomainValidation $validation)
    {
        $this->domain = $domain;
        $this->validator = $validator;
        $this->validation = $validation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class = get_class($this->validator);

        try {

            $check = $this->validator->validate($this->domain->domain);
            $this->validation->valid = $check;

        } catch (\Exception $e) {

            $this->validation->valid = false;
            $this->validation->message = $e->getMessage();

        }

        if($this->validation->valid) {
            Cache::increment(prefix_valid($class));
        } else {
            Cache::increment(prefix_invalid($class));
        }

        Cache::decrement(prefix_pending($class));

        $this->validation->is_pending = false;
        $this->validation->save();
    }
}

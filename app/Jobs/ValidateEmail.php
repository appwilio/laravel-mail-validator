<?php

namespace App\Jobs;

use App\Contracts\Validator;
use App\Domain\Model\Email;
use App\Domain\Model\EmailValidation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class ValidateEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @type Email
     */
    protected $email;
    /**
     * @var Validator
     */
    protected $validator;
    /**
     * @var EmailValidation
     */
    protected $validation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email, Validator $validator, EmailValidation $validation)
    {
        $this->email = $email;
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

            $check = $this->validator->validate($this->email->address);
            $this->validation->valid = $check;

        } catch (\Exception $e) {

            Cache::increment(prefix_invalid($class));

            $this->validation->valid = false;
            $this->validation->message = $e->getMessage();
        }

        if ($this->validation) {
            Cache::increment(prefix_valid($class));
        } else {
            Cache::increment(prefix_invalid($class));
        }

        Cache::decrement(prefix_pending($class));

        $this->validation->is_pending = false;
        $this->validation->save();
    }
}

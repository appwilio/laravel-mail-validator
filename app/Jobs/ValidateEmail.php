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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email, Validator $validator)
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
        $valid = new EmailValidation([
            "validator" => $this->validator->getName()
        ]);

        $class = get_class($this->validator);


        try {
            $check = $this->validator->validate($this->email->address);
            $valid->valid = $check;


        } catch (\Exception $e) {

            Cache::increment(prefix_invalid($class));

            $valid->valid = false;
            $valid->message = $e->getMessage();
        }
        if ($valid->valid) {
            Cache::increment(prefix_valid($class));
        } else {
            Cache::increment(prefix_invalid($class));
        }
        Cache::decrement(prefix_pending($class));
        $this->email->validations()->save($valid);
    }
}

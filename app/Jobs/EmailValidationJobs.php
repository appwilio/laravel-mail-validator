<?php

namespace App\Jobs;

use App\Domain\Model\Email;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class EmailValidationJobs extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

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
            $this->dispatch(new ValidateEmail($this->email, new $validatorClass()));
        }
        foreach (config("validators.domain") as $validatorClass) {
            Cache::increment(prefix_pending($validatorClass));
        }
    }
}

<?php

namespace App\Jobs;

use App\Domain\Model\Domain;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class EmailValidationPending extends Job
{
    use DispatchesJobs;

    public function __construct()
    {
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
        }
    }
}

<?php

namespace App\Jobs;

use App\Domain\Model\Domain;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class DomainValidationPending extends Job
{
    use DispatchesJobs;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (config("validators.domain") as $validatorClass) {
        }
    }
}

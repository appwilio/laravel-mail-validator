<?php

namespace App\Jobs;

use App\Domain\Model\Domain;
use App\Domain\Model\Email;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CutDomain extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
        $split = explode("@", $this->email->address);

        $domain = strtolower(
            array_pop($split)
        );

        $domain_exist = Domain::where("domain", $domain)->first();

        if (null == $domain_exist) {
            $new_domain = new Domain([
                "domain" => $domain
            ]);
            $new_domain->save();
            $this->email->domain()->associate($new_domain);
        } else {
            $this->email->domain()->associate($domain_exist);
        }
        $this->email->save();
    }

}

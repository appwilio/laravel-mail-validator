<?php

namespace App\Console\Commands;

use App\Domain\Domain\Domain;
use App\Domain\Email\Email;
use Illuminate\Console\Command;

class SplitDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Split domains';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("start");
        Email::where("domain_id", 0)->chunk(10000, function ($emails) {
            $this->info("tik");
            foreach ($emails as $email) {
                $split = explode("@", $email->address);
                $domain = strtolower(array_pop($split));
                $domain_exist = Domain::where("domain", $domain)->first();
                if (null == $domain_exist) {
                    $new_domain = new \App\Domain\Domain\Domain([
                        "domain" => $domain
                    ]);
                    $new_domain->save();
                    $email->domain()->associate($new_domain);
                } else {
                    $email->domain()->associate($domain_exist);
                }
                $email->save();
            }
        });
        $this->info("done");
    }
}

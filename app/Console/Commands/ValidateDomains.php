<?php

namespace App\Console\Commands;

use App\Domain\Domain\Domain;
use App\Validators\EguliasDNSEmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Illuminate\Console\Command;

class ValidateDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:vd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'validate domains';

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
        Domain::where("valid", false)->chunk(1000, function ($domains) {
            $this->info("tik");
            foreach ($domains as $domain) {
                $this->info("tak");
                $validator = new EguliasDNSEmailValidator();
                $result = $validator->validate($domain->emails()->first()->address);
                $domain->valid = $result;
                $domain->save();
            }
        });
        $this->info("done");
    }
}

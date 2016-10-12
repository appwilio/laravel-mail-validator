<?php

namespace App\Console\Commands;

use App\Contracts\Validator;
use App\Domain\Repository\DomainValidationRepository;
use App\Domain\Repository\EmailValidationRepository;
use App\Domain\Repository\ValidationRepository;
use Cache;
use DB;
use Illuminate\Console\Command;

class ResultCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:rrc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate results cache';

    /**
     * @var $domainValidationRepository DomainValidationRepository
     */
    protected $domainValidationRepository;

    /**
     * @var $domainValidationRepository EmailValidationRepository
     */
    protected $emailValidationRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DomainValidationRepository $DVRepository, EmailValidationRepository $EVRepository)
    {
        parent::__construct();
        $this->domainValidationRepository =  $DVRepository;
        $this->emailValidationRepository = $EVRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("start");
        foreach (config("validators.domain") as $validatorClass) {
            $this->info("tik domain {$validatorClass}");
            $this->recalculate($validatorClass, $this->domainValidationRepository);

        }
        foreach (config("validators.email") as $validatorClass) {
            $this->info("tik email {$validatorClass}");
            $this->recalculate($validatorClass, $this->emailValidationRepository);
        }
        $this->info("done");
    }

    /**
     * @return string SQL with variable :validator
     */
    protected function domainValidations()
    {
        return 'SELECT dv.valid AS `valid`, count(*) AS `count` FROM domains d 
                LEFT JOIN domain_validations dv ON d.id = dv.domain_id AND dv.validator = :validator 
            GROUP BY dv.valid;';
    }

    /**
     * @return string SQL with variable :validator
     */
    protected function emailValidations()
    {
        return 'SELECT ev.valid AS `valid`, count(*) AS `count`  FROM emails e 
                LEFT JOIN email_validations ev ON e.id = ev.email_id AND ev.validator = :validator 
            GROUP BY ev.valid;';
    }

    /**
     * @param $validatorClass
     * @param $repository ValidationRepository
     */
    protected function recalculate($validatorClass, $repository)
    {
        /**
         * @var $validator Validator
         */
        $validator = new $validatorClass();
        $cacheTemplate = [
            "valid" => $repository->getStatusCount($validator->getName(), true),
            "invalid" => $repository->getStatusCount($validator->getName(), false),
            "pending" => $repository->getPendingCount($validator->getName())
        ];
        $this->store($validatorClass, $cacheTemplate);
    }

    /**
     * @param $validatorClass
     * @param $cacheTemplate array [
     *      "valid" => (int)
     *      "invalid" => (int)
     *      "pending" => (int)
     * ]
     */
    protected function store($validatorClass, $cacheTemplate)
    {
        Cache::forever(prefix_valid($validatorClass), $cacheTemplate["valid"]);
        Cache::forever(prefix_invalid($validatorClass), $cacheTemplate["invalid"]);
        Cache::forever(prefix_pending($validatorClass), $cacheTemplate["pending"]);
    }

}

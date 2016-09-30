<?php

namespace App\Console\Commands;

use App\Contracts\Validator;
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
     */
    public function handle()
    {
        $this->info("start");
        foreach (config("validators.domain") as $validatorClass) {
            $this->info("tik domain {$validatorClass}");
            $this->recalculate($validatorClass, $this->domainValidations());

        }
        foreach (config("validators.email") as $validatorClass) {
            $this->info("tik email {$validatorClass}");
            $this->recalculate($validatorClass, $this->emailValidations());
        }
        $this->info("done");
    }

    /**
     * @return string SQL with variable :validator
     */
    protected function domainValidations()
    {
        return 'SELECT dv.valid AS `valid`, count(*) AS `count`  FROM emails e 
                JOIN domains d  ON e.domain_id = d.id 
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
     * @param string $query
     */
    protected function recalculate($validatorClass, $query)
    {
        /**
         * @var $validator Validator
         */
        $validator = new $validatorClass();
        $cacheTemplate = [
            "valid" => 0,
            "invalid" => 0,
            "pending" => 0
        ];
        $stat = DB::select(
            $query,
            [
                "validator" => $validator->getName()
            ]
        );
        foreach ($stat as $line) {
            if (null === $line->valid) {
                $cacheTemplate["pending"] = $line->count;
            } else {
                switch ($line->valid) {
                    case 1:
                        $cacheTemplate["valid"] = $line->count;
                        break;
                    case 0:
                        $cacheTemplate["invalid"] = $line->count;
                        break;
                }
            }
        }
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

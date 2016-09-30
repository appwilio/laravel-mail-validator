<?php

namespace App\Console\Commands;

use App\Domain\Model\Domain;
use App\Domain\Model\Email;
use App\Domain\Model\Exclude;
use App\Domain\Repository\ExcludeRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Cache;

class Temp extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command';

    private function showStats()
    {
        $memory = memory_get_peak_usage(true);

        $this->comment(sprintf('Выполнено за: %0.2f sec.', round(microtime(true) - LARAVEL_START, 3)));
        $this->comment(sprintf('Потреблено памяти: %0.2f MiB', $memory / (1024 * 1024)));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("start");

        $emailExcludes = Exclude::where("type", Exclude::PREFIX_EXCLUDE)->get(["value"])->pluck("value");
        $domainsExcludes = Exclude::where("type", Exclude::SUFFIX_EXCLUDE)->get(["value"])->pluck("value");

        $domainsRequest = (new Domain)->newQuery();

        foreach ($domainsExcludes as $exclude) {
            $domainsRequest->where("domain", "NOT LIKE", "%{$exclude}");
        }



        $domainsRequest->chunk(1, function ($domains) use (&$a, $emailExcludes) {
            /**
             * @var $domain Domain
             */
            foreach ($domains as $domain) {
                /**
                 * @var $failed HasMany
                 */
                $failed = $domain->validations()->where("valid", false)->first();
                if (null == $failed) {
                    /**
                     * @var $emailsQuery Builder
                     */
                    $emailsQuery = $domain->load("emails")->emails();
                    foreach ($emailExcludes as $exclude) {
                            $emailsQuery->where("address", "NOT LIKE", "{$exclude}%");
                    }
                    $list = $emailsQuery->get(["address"])->pluck("address");
                    var_dump($list);
                }
            }
//            foreach ($domains as $domain) {
//                $list = $domain->load("emails")->emails();
//            }
        });
        /**
         * @var $emails Builder
         */

//        $domainValidators = config("validators.domain");
//        foreach ($domainValidators as $validatorClass) {
//
//        }
//        array_map(function($validatorClass){
//            /**
//             * @var $validator Validator
//             */
//            $validator = new $validatorClass();
//            return [
//                "key" => $validator->getName(),
//                "valid" => Cache::get(prefix_valid($validatorClass), 0),
//                "invalid" => Cache::get(prefix_invalid($validatorClass), 0),
//                "pending" => Cache::get(prefix_pending($validatorClass), 0)
//            ];
//

//        $emails = DB::select('SELECT dv.valid as `valid`, count(*) as `count` FROM emails e JOIN domains d  ON e.domain_id = d.id LEFT JOIN domain_validations dv ON d.id = dv.domain_id and dv.validator = :validator GROUP BY dv.valid;', ["validator" => "openrelay"]);
//        dd($emails);
//        $count = 0;
//        $smtp = new SmtpSocket();
//        $smtp->setPort("25");
//        Domain::chunk(1000, function ($domains) use (&$count, $smtp) {
//            $count++;
//            $this->info("tik {$count}");
//            foreach ($domains as $domain) {
//                $domain->validations()->save(new DomainValidation([
//                    "validator" => "A",
//                    "valid" => checkdnsrr($domain->domain, 'A')
//                ]));
//                 $domain->validations()->save(new DomainValidation([
//                    "validator" => "MX",
//                    "valid" => checkdnsrr($domain->domain, 'MX')
//                ]));
//                try {
//                    $valid = !($smtp->setHost($domain->domain)->check("botn8@yandex.ru", "artarn@appwili.com"));
//                } catch (\Exception $e) {
//                    $valid = true;
//                }
//
//                $domain->validations()->save(new DomainValidation([
//                    "validator" => "openrelay",
//                    "valid" => $valid
//                ]));
//            }
//        });


        $this->showStats();
        $this->info("done");
        die(0);
    }
}

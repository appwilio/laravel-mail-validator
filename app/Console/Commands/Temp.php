<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;

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

//        $smtp = new SmtpSocket();
//        $valid = $smtp->setHost("85.143.211.171")->setPort("2525")->check("l_gusareva@ridan.kiev.ua", "uradvd85@gmail.com");;
//        var_dump($valid);
//
//        try {
//            $valid = $smtp->setHost("85.143.211.171")
//                ->setPort("2525")
//                ->setAuthorises(true)
//                ->setLogin("uradvd85@gmail.com")
//                ->setPassword("yWsHlRjhzvr")
//                ->check("l_gusareva@ridan.kiev.ua", "uradvd85@gmail.com");
//        } catch (CriticalSocketException $e) {
//            echo "<pre>";
//            var_dump($e->getMessage());
//            echo "</pre>";
//        } catch (InformativeSocketException $e) {
//            echo "<pre>";
//            var_dump($e->getMessage());
//            echo "</pre>";
//        }
//        var_dump(["valid"=>$valid, "d"=>$smtp->getDebug()]);


        $this->showStats();
        $this->info("done");
        die(0);
    }
}

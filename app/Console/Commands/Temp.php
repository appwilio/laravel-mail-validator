<?php

namespace App\Console\Commands;

use App\Domain\Email\Email;
use App\Domain\Email\EmailRepository;
use App\Jobs\ValidateEmail;
use App\Lib\Validation\SMTP\SmtpValidation;
use App\Lib\Validators\Smtp\CriticalSocketException;
use App\Lib\Validators\Smtp\InformativeSocketException;
use App\Lib\Validators\Smtp\SmtpSocket;
use App\Validators\EguliasEmailValidator;
use App\Validators\EguliasRFCEmailValidator;
use App\Validators\LavoieslEmailValidator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

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

    /**
     * @type EmailRepository
     */
    protected $emails;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailRepository $emails)
    {
        parent::__construct();
        $this->emails = $emails;
    }

    private function showStats()
    {
        $memory = memory_get_peak_usage(true);

        $this->comment(sprintf('Выполнено за: %0.2f sec.', round(microtime(true) - LARAVEL_START, 3)));
        $this->comment(sprintf('Потреблено памяти: %0.2f MiB', $memory / (1024 * 1024)));
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->info("start");

//        $smtp = new SmtpSocket();
//        $valid = "unknown";
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

        $offset = 0;
        $step = 10000;

        while (true) {
            $this->info("tik");
            $emails = Email::skip($offset)->take($step)->get();

            foreach ($emails as $email) {
                $this->dispatch(new ValidateEmail($email, new EguliasRFCEmailValidator()));
            }
            $offset += $step;
            $this->showStats();
        }

        $this->showStats();
        $this->info("done");
    }
}

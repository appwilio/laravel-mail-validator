<?php

namespace App\Console\Commands;

use App\Domain\Model\Email;
use App\Domain\Repository\EmailRepository;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DeleteDublicates extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:dd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper command to delete dubles';

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
        $dublicates = 0;
        $this->info("start");
        Email::groupBy("address")->havingRaw('COUNT(*) > 1')->chunk(100000, function ($collection) {
            $this->showStats();
            $this->info("chunk tik start");
            foreach ($collection as $email) {
                $this->info("tik {$email->id} {$email->address}");
                $dulicates = Email::where("address", $email->address)->where('id', '<>', $email->id)->get();
                $delets = 0;
                foreach ($dulicates as $dulicate) {
                    $this->info("Drop {$dulicate->id} {$dulicate->address}");
                    $delets += 1;
                    $dulicate->delete();
                }
                $this->info("deleted {$delets}");
            }
            $this->info("chunk tik end");
            $this->showStats();
        });
        $this->showStats();
        $this->info("done");
    }
}

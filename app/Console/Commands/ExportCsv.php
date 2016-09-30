<?php

namespace App\Console\Commands;

use App\Domain\Model\Domain;
use File;
use Illuminate\Console\Command;

class ExportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export mails to csv';

    protected $skip = ["mail.ru"];
    protected $vaultName = "vault";
    protected $vaultLimit = 100;

    private function showStats()
    {
        $memory = memory_get_peak_usage(true);
        $this->comment(sprintf('Выполнено за: %0.2f sec.', round(microtime(true) - LARAVEL_START, 3)));
        $this->comment(sprintf('Потреблено памяти: %0.2f MiB', $memory / (1024 * 1024)));
    }

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
        Domain::where("valid", true)->chunk(1, function ($domains) {
            $this->info("tik");
            foreach ($domains as $domain) {
                if(!in_array($domain->domain, $this->skip)) {
                    $this->info("domain {$domain->domain}");
                    /**
                     * @var $list \Illuminate\Support\Collection
                     */
                    $list = $domain->load("emails")->emails()->where("import", "max")->distinct()->orderBy("address")->pluck("address");
		    if($list->count()==0) {
			$this->info("skip");
		    	continue;
		    }
                    if($list->count() < $this->vaultLimit) {
                        File::append(public_path()."/csv/{$this->vaultName}.csv", implode(PHP_EOL, $list->toArray()));
                    } else {
                        File::put(public_path()."/csv/{$domain->domain}.csv", implode(PHP_EOL, $list->toArray()));
                    }
                }
            }
            $this->showStats();
        });
        $this->info("done");
    }
}

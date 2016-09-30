<?php

namespace App\Jobs;

use App\Domain\Model\Domain;
use App\Domain\Model\Exclude;
use App\Domain\Model\Export;
use File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateExport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var $export Export
     */
    protected $export;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $export = $this->export;
        $skip = config("export.skip");
        $file = config("export.dir") . "/" . $this->export->name;

        File::put($file, "");

        $emailExcludes = Exclude::where("type", Exclude::PREFIX_EXCLUDE)->get(["value"])->pluck("value");
        $domainsExcludes = Exclude::where("type", Exclude::SUFFIX_EXCLUDE)->get(["value"])->pluck("value");

        $domainsRequest = (new Domain)->newQuery();

        foreach ($domainsExcludes as $exclude) {
            $domainsRequest->where("domain", "NOT LIKE", "%{$exclude}");
        }


        $domainsRequest->chunk(1, function ($domains) use (&$a, $emailExcludes, $file, $skip, $export) {
            /**
             * @var $domain Domain
             */
            foreach ($domains as $domain) {
                $failed = $domain->validations()->where("valid", false)->first();
                if (null == $failed) {
                    $emailsQuery = $domain->load("emails")->emails();
                    foreach ($emailExcludes as $exclude) {
                        $emailsQuery->where("address", "NOT LIKE", "{$exclude}%");
                    }
                    $list = $emailsQuery->get(["address"])->pluck("address");

                    File::append($file, implode(PHP_EOL, $list->toArray()).PHP_EOL);

                }
            }
        });

        $this->export->finished = true;
        $this->export->save();
    }
}

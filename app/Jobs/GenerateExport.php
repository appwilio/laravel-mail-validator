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
     * @var $includeFiles array
     */
    protected $includeFiles;
    /**
     * @var $excludePrefix array
     */
    protected $excludePrefix;
    /**
     * @var $excludeSuffix array
     */
    protected $excludeSuffix;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Export $export, $includeFiles = [], $excludePrefix = [], $excludeSuffix = [])
    {
        $this->export = $export;
        $this->includeFiles = $includeFiles;
        $this->excludePrefix = $excludePrefix;
        $this->excludeSuffix = $excludeSuffix;
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

        if(count($this->excludePrefix)>0)  {
            $emailExcludes = Exclude::where("type", Exclude::PREFIX_EXCLUDE)->whereIn("id", $this->excludePrefix)->pluck("value");
        } else {
            $emailExcludes = [];
        }

        if(count($this->excludeSuffix) > 0) {
            $domainsExcludes = Exclude::where("type", Exclude::SUFFIX_EXCLUDE)->whereIn("id", $this->excludeSuffix)->pluck("value");
        } else {
            $domainsExcludes = [];
        }

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
                /**
                 * no failed domains
                 */
                if (null == $failed) {
                    $emailsQuery = $domain->load("emails")->emails();

                    if(count($this->includeFiles)>0){
                        $emailsQuery->whereHas("import_files", function ($query) {
                            $query->whereIn('import_file_id', $this->includeFiles);
                        });
                    }

                    foreach ($emailExcludes as $exclude) {
                        $emailsQuery->where("address", "NOT LIKE", "{$exclude}%");
                    }

                    $list = $emailsQuery->pluck("address")->toArray();

                    if(count($list)>0) {
                        File::append($file, implode(PHP_EOL, $list).PHP_EOL);
                    }

                }
            }
        });

        $this->export->finished = true;
        $this->export->save();
    }
}

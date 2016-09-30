<?php

namespace App\Http\Controllers;

use App\Domain\Model\Exclude;
use App\Domain\Repository\ExcludeRepository;
use App\Http\Requests\ExcludeRequest;

class ExcludeController extends Controller
{
    /**
     * @var $excludes ExcludeRepository
     */
    protected $excludes;

    public function __construct(ExcludeRepository $repository)
    {
        $this->excludes = $repository;
    }

    public function excludesList()
    {
        return response()->json([
            "prefix" => $this->excludes->getPrefixExcludes(),
            "suffix" => $this->excludes->getSuffixExclude()
        ]);
    }

    public function create(ExcludeRequest $r)
    {
        $exclude = Exclude::firstOrCreate([
            "value" => strtolower(
                str_replace(
                    "@",
                    "",
                    trim($r->value)
                )
            ),
            "type" => $r->type
        ]);
        return response()->json(
            $exclude->save()
        );
    }
}

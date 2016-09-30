<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class Exclude extends Model
{
    const PREFIX_EXCLUDE = 0;
    const SUFFIX_EXCLUDE = 1;

    public $timestamps = false;
    protected $fillable = ["type", "value"];
}

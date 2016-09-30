<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = ["name", "finished"];

    protected $casts = [
        'finished' => 'boolean'
    ];
}

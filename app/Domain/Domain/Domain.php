<?php

namespace App\Domain\Domain\Domain;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ["domain"];

    protected $casts = [
        'valid' => 'boolean'
    ];

    public function emails() {
        return $this->hasMany('App\Domain\Email\Email');
    }
}

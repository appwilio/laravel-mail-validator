<?php
namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class DomainValidation extends Model
{
    protected $table = "domain_validations";

    public function email() {
        $this->belongsTo('App\Domain\Model\Domain');
    }
}


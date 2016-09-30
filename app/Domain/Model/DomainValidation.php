<?php
namespace App\Domain\Model;


class DomainValidation extends AbstractValidation
{
    protected $table = "domain_validations";

    public function email() {
        $this->belongsTo('App\Domain\Model\Domain');
    }
}


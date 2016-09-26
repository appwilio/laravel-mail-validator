<?php
namespace App\Domain\Model;

class EmailValidation extends AbstractValidation
{
    protected $table = "email_validations";

    public function email() {
        $this->belongsTo('App\Domain\Model\Email');
    }
}


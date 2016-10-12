<?php
namespace App\Domain\Model;


class DomainValidation extends AbstractValidation
{
    protected $table = "domain_validations";

    protected $casts = [
        'valid' => 'boolean',
        'is_pending' => 'boolean'
    ];

    protected $fillable = [
        'valid', 'validator', 'is_pending'
    ];

    public function email() {
        $this->belongsTo('App\Domain\Model\Domain');
    }
}


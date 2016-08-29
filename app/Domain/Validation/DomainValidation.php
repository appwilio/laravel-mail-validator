<?php
namespace App\Domain\Validation;

use Illuminate\Database\Eloquent\Model;

class DomainValidation extends Model
{
    protected $fillable = ["validator", "valid", "message"];

    protected $casts = [
        'valid' => 'boolean'
    ];

    public function email() {
        $this->belongsTo('App\Domain\Domain\Domain');
    }
}


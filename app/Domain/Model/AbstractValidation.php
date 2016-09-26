<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 26.09.16
 * Time: 14:15
 */

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class AbstractValidation extends Model
{
    protected $fillable = ["validator", "valid", "message"];

    protected $casts = [
        'valid' => 'boolean'
    ];
}
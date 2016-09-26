<?php
namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ["address"];

    protected $casts = [
        'trimmed' => 'boolean'
    ];

    public function validations(){
        return $this->hasMany('App\Domain\Model\EmailValidation');
    }

    public function domain(){
        return $this->belongsTo('App\Domain\Model\Email');
    }
}

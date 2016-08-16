<?php
namespace App\Domain\Email;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ["address"];

    public function validations(){
        return $this->hasMany('App\Domain\Validation\Validation');
    }
}

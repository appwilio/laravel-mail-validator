<?php
namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ["address"];

    protected $casts = [
        'trimmed' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function validations(){
        return $this->hasMany('App\Domain\Model\EmailValidation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain(){
        return $this->belongsTo('App\Domain\Model\Email');
    }
}

<?php
namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model as Model;

class ImportFile extends Model
{
    protected $fillable = ["original_name", "generated_name", "lines_processed"];

    protected $hidden = ["generated_name"];

    protected $casts = [
        'finished' => 'boolean'
    ];
}

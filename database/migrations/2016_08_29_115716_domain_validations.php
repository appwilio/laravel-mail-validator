<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DomainValidations extends Migration
{
    protected $table;

    public function __construct()
    {
        $this->table = (new \App\Domain\Model\DomainValidation())->getTable();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
             $table->unsignedInteger("domain_id");
            $table->string("validator");
            $table->boolean("valid");
            $table->text("message");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table);
    }
}

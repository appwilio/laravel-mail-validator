<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTable extends Migration
{
    protected $table;

    public function __construct()
    {
        $this->table = (new \App\Domain\Model\Email())->getTable();
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
            $table->string("address");
            $table->boolean("trimmed")->default(false);
            $table->unsignedInteger("domain_id")->default(0);
            $table->string("import")->nullable();
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

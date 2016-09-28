<?php

use App\Domain\Model\ImportFile;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportFilesTable extends Migration
{
    protected $table;

    public function __construct()
    {
        $this->table = (new ImportFile())->getTable();
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
            $table->string("original_name");
            $table->string("generated_name");
            $table->unsignedInteger("lines_total")->default(0);
            $table->unsignedInteger("lines_processed")->default(0);
            $table->boolean("finished")->default(false);
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

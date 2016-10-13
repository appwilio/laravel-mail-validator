<?php

use App\Domain\Model\ImportFile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FileValidationStatus extends Migration
{

    protected $table;
    protected $validation_status;

    public function __construct()
    {
        $this->table = (new ImportFile())->getTable();
        $this->validation_status = "validation_status";
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
           $table->unsignedInteger($this->validation_status);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
           $table->dropColumn($this->validation_status);
        });
    }
}

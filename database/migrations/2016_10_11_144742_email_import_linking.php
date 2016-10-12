<?php

use App\Domain\Model\EmailImport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EmailImportLinking extends Migration
{
    protected $table;

    public function __construct()
    {
        $this->table = (new EmailImport())->getTable();
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger("email_id");
            $table->unsignedInteger("import_file_id");
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

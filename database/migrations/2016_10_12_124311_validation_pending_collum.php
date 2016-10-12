<?php

use App\Domain\Model\DomainValidation;
use App\Domain\Model\EmailValidation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ValidationPendingCollum extends Migration
{

    protected $email_validation_table;
    protected $domain_validation_table;
    protected $pending_column_name = "is_pending";

    /**
     * ValidationPendingCollum constructor.
     */
    public function __construct()
    {
        $this->email_validation_table = (new EmailValidation())->getTable();
        $this->domain_validation_table = (new DomainValidation())->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->email_validation_table, function (Blueprint $table) {
            $table->boolean($this->pending_column_name);
        });
        Schema::table($this->domain_validation_table, function (Blueprint $table) {
            $table->boolean($this->pending_column_name);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->email_validation_table, function (Blueprint $table) {
            $table->dropColumn($this->pending_column_name);
        });
        Schema::table($this->domain_validation_table, function (Blueprint $table) {
            $table->dropColumn($this->pending_column_name);
        });
    }


}

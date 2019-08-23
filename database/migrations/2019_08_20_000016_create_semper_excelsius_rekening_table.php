<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemperExcelsiusRekeningTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'semper_excelsius_rekening';

    /**
     * Run the migrations.
     * @table semper_excelsius_rekening
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('rekeningnummer');
            $table->decimal('saldo', 16, 2);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}

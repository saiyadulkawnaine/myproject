<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeycontrolParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keycontrol_parameters', function (Blueprint $table) {
          $table->increments('id')->unsignedInteger();
          $table->unsignedInteger('keycontrol_id')->unsigned();
          $table->foreign('keycontrol_id')->references('id')->on('keycontrols')->onDelete('cascade');
          $table->unsignedInteger('parameter_id');
          $table->date('from_date');
          $table->date('to_date');
          $table->decimal('value', 8, 4);
          $table->unsignedSmallInteger('created_by')->nullable();
          $table->timestamp('created_at')->nullable();
          $table->unsignedSmallInteger('updated_by')->nullable();
          $table->timestamp('updated_at')->nullable();
          $table->timestamp('deleted_at')->nullable();
          $table->string('created_ip',20)->nullable();
          $table->string('updated_ip',20)->nullable();
          $table->string('deleted_ip',20)->nullable();
          $table->unsignedTinyInteger('row_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keycontrol_parameters');
    }
}

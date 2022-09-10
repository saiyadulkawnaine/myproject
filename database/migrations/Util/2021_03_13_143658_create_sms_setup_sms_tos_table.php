<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsSetupSmsTosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_setup_sms_tos',  function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sms_setup_id');
            $table->foreign('sms_setup_id')->references('id')->on('sms_setups')->onDelete('cascade');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id')->references('id')->on('employee_h_rs')->onDelete('cascade');
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
        Schema::dropIfExists('sms_setup_sms_tos');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeRecruitReqReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_recruit_req_replaces', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('employee_recruit_req_id')->unsigned();
            $table->foreign('employee_recruit_req_id','recId')->references('id')->on('employee_recruit_reqs')->onDelete('cascade');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id','replacedEmpID')->references('id')->on('employee_h_rs')->onDelete('cascade');
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
        Schema::dropIfExists('employee_recruit_req_replaces');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeRecruitReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_recruit_reqs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('requisition_date')->nullable();
            $table->unsignedInteger('employee_budget_position_id');
            $table->foreign('employee_budget_position_id','bidgetid')->references('id')->on('employee_budget_positions')->onDelete('cascade');
            $table->unsignedInteger('no_of_required_position');
            $table->date('date_of_join')->nullable();
            $table->unsignedInteger('age_limit')->nullable();
            $table->unsignedInteger('employee_h_r_id');
            $table->foreign('employee_h_r_id','repempId')->references('id')->on('employee_h_rs')->onDelete('cascade');
            $table->string('justification',400)->nullable();
            $table->string('person_specification',400)->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('employee_recruit_reqs');
    }
}

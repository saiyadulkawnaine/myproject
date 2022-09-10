<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeJobHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_job_histories', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('employee_transfer_id')->nullable();
            $table->foreign('employee_transfer_id','job_employee_transfer_id')->references('id')->on('employee_transfers')->onDelete('cascade');
            $table->unsignedInteger('employee_promotion_id')->nullable();
            $table->foreign('employee_promotion_id','job_employee_promotion_id')->references('id')->on('employee_promotions')->onDelete('cascade');
            $table->unsignedInteger('employee_h_r_job_id');
            $table->foreign('employee_h_r_job_id','historyempjobid')->references('id')->on('employee_h_r_jobs')->onDelete('cascade');
            $table->string('job_description',700);
            $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('employee_job_histories');
    }
}

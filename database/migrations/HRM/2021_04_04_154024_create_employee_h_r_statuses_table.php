<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHRStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_h_r_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id', 'employeeidstatusid')->references('id')->on('employee_h_rs')->onDelete('cascade');
            $table->unsignedSmallInteger('status_id');
            $table->date('status_date');
            $table->unsignedSmallInteger('logistics_status_id');
            $table->unsignedSmallInteger('old_status_id');
            $table->string('remarks', 400)->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('employee_h_r_statuses');
    }
}

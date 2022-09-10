<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeToDoListTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_to_do_list_tasks', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('employee_to_do_list_id');
            $table->foreign('employee_to_do_list_id')->references('id')->on('employee_to_do_lists')->onDelete('cascade');
            $table->string('task_desc',1000);
            $table->unsignedSmallInteger('priority_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('result_desc',1000)->nullable();
            $table->string('impact_desc',1000)->nullable();
            $table->unsignedSmallInteger('created_by')->nulable();
            $table->timestamp('created_at')->nulable();
            $table->unsignedSmallInteger('updated_by')->nulable();
            $table->timestamp('updated_at')->nulable();
            $table->timestamp('deleted_at')->nulable();
            $table->string('created_ip',20)->nulable();
            $table->string('updated_ip',20)->nulable();
            $table->string('deleted_ip',20)->nulable();
            $table->unsignedTinyInteger('row_status')->nulable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_to_do_list_tasks');
    }
}

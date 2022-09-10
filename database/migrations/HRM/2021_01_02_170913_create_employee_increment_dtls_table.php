<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeIncrementDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_increment_dtls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_increment_id')->unsigned();
            $table->foreign('employee_increment_id','employeeincrementid')->references('id')->on('employee_increments')->onDelete('cascade');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id','employeeIdincre1')->references('id')->on('employee_h_rs')->onDelete('cascade');
            
            $table->decimal('prev_gross',14,4);
            $table->decimal('increment_per',8,2);
            $table->decimal('increment_amount',14,4);
            $table->decimal('new_gross',14,4);
            $table->date('effective_date');
           
            $table->unsignedSmallInteger('approved_by')->nullable();
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
        Schema::dropIfExists('employee_increment_dtls');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAttendencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendences', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('attendence_date');
            $table->unsignedInteger('operator');
            $table->unsignedInteger('helper')->nullable();
            $table->unsignedInteger('prod_staff')->nullable();
            $table->unsignedInteger('supporting_staff')->nullable();
            $table->unsignedInteger('cutting_staff')->nullable();
            $table->unsignedInteger('embroidery_staff')->nullable();
            $table->unsignedInteger('printing_staff')->nullable();
            $table->unsignedInteger('finishing_staff')->nullable();
            $table->decimal('operator_salary',14,4)->nullable();
            $table->decimal('helper_salary',14,4)->nullable();
            $table->decimal('prod_stuff_salary',14,4)->nullable();
            $table->decimal('supporting_stuff_salary',14,4)->nullable();
            $table->decimal('operator_ot',14,4)->nullable();
            $table->decimal('helper_ot',14,4)->nullable();//overtime
            $table->decimal('daily_prod_bill',14,4)->nullable();
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
        Schema::dropIfExists('employee_attendences');
    }
}

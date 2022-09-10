<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id','employeIdPromotion')->references('id')->on('employee_h_rs')->onDelete('cascade');
            $table->date('promotion_date')->nulable();
            $table->unsignedInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->string('grade',30)->nulable();
            $table->unsignedInteger('report_to_id')->nullable();

            $table->unsignedInteger('old_designation_id')->nulable();
            $table->foreign('old_designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->string('old_grade',30)->nulable();
            $table->unsignedInteger('old_report_to_id')->nullable();
            $table->string('remarks',500)->nullable();
            $table->unsignedTinyInteger('api_status')->nulable()->default(0);
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
        Schema::dropIfExists('employee_promotions');
    }
}

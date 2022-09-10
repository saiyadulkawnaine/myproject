<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdFinishMcParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_finish_mc_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_finish_mc_date_id')->unsigned();
            $table->foreign('prod_finish_mc_date_id')->references('id')->on('prod_finish_mc_dates')->onDelete('cascade');
            $table->integer('prod_batch_id')->unsigned();
            $table->foreign('prod_batch_id','prodbatchid')->references('id')->on("prod_batches")->onDelete('cascade');
            $table->unsignedInteger('rmp');
            $table->unsignedInteger('gsm_weight');
            $table->unsignedInteger('dia');
            $table->decimal('working_minute',14,4);
            $table->unsignedInteger('shift_id');
            $table->integer('employee_h_r_id')->unsigned();
            $table->foreign('employee_h_r_id')->references('id')->on('employee_h_rs')->onDelete('cascade');
            $table->string('remarks',500)->nullable();
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
        Schema::dropIfExists('prod_finish_mc_parameters');
    }
}

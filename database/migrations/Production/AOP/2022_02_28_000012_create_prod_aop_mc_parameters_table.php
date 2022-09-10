<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdAopMcParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_aop_mc_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_aop_mc_date_id')->unsigned();
            $table->foreign('prod_aop_mc_date_id')->references('id')->on('prod_aop_mc_dates')->onDelete('cascade');
            $table->integer('prod_aop_batch_id')->unsigned();
            $table->foreign('prod_aop_batch_id','AopBatchID')->references('id')->on("prod_aop_batches")->onDelete('cascade');
            $table->unsignedInteger('rpm');
            $table->unsignedInteger('gsm_weight');
            $table->unsignedInteger('dia');
            $table->decimal('repeat_size',14,4);
            $table->decimal('production_per_hr',14,4);
            $table->unsignedInteger('tgt_qty');
            $table->unsignedInteger('shiftname_id');
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
        Schema::dropIfExists('prod_aop_mc_parameters');
    }
}

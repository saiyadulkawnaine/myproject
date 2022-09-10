<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchFinishQcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_finish_qcs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shift_id');
            $table->integer('prod_batch_id')->unsigned();
            $table->foreign('prod_batch_id')->references('id')->on('prod_batches');
            $table->integer('prod_aop_batch_id')->unsigned();
            $table->foreign('prod_aop_batch_id')->references('id')->on('prod_aop_batches');
            $table->unsignedInteger('machine_id');
            $table->date('posting_date');
            $table->unsignedInteger('qc_by_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('buyers');
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
        Schema::dropIfExists('prod_batch_finish_qcs');
    }
}

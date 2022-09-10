<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchFinishQcRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_finish_qc_rolls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_batch_finish_qc_id')->unsigned();
            $table->foreign('prod_batch_finish_qc_id','prod_12345')->references('id')->on('prod_batch_finish_qcs');

            $table->integer('prod_batch_roll_id')->unsigned();
            
            $table->foreign('prod_batch_roll_id','prod_batch_roll_id1234')->references('id')->on('prod_batch_rolls');
            $table->integer('prod_aop_batch_roll_id')->unsigned();
            
            $table->foreign('prod_aop_batch_roll_id','prod_aop_batch_roll_id1234')->references('id')->on('prod_aop_batch_rolls');
            $table->string('dia_width',50)->nullable();
            $table->unsignedInteger('gsm_weight')->nullable();
            $table->decimal('qty',14,4);
            $table->decimal('reject_qty',14,4)->nullable();
            $table->unsignedInteger('grade_id');
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
        Schema::dropIfExists('prod_batch_finish_qc_rolls');
    }
}

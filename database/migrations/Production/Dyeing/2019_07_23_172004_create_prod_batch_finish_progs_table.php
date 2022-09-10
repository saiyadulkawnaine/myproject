<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchFinishProgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_finish_progs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shift_id');
            $table->integer('production_process_id')->unsigned();
            $table->foreign('production_process_id')->references('id')->on('production_processes');
            $table->unsignedInteger('prod_batch_id')->nullable();
            $table->foreign('prod_batch_id')->references('id')->on('prod_batches');
            $table->unsignedInteger('prod_aop_batch_id')->nullable();
            $table->foreign('prod_aop_batch_id')->references('id')->on('prod_aop_batches');
            $table->unsignedInteger('machine_id');
            
            $table->timestamp('loaded_at')->nullable();
            $table->timestamp('unloaded_at')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('posting_date');
            $table->unsignedInteger('operator_id')->nullable();
            $table->unsignedInteger('incharge_id')->nullable();
            $table->decimal('temparature',14,4)->nullable();
            $table->string('stretch',100)->nullable();
            $table->string('over_feed',100)->nullable();
            $table->string('feed_in',100)->nullable();
            $table->string('pinning',100)->nullable();
            $table->string('speed',100)->nullable();
            $table->string('spirality',100)->nullable();
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
        Schema::dropIfExists('prod_batch_finish_progs');
    }
}

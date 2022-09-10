<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdAopBatchProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_aop_batch_processes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('prod_aop_batch_id')->unsigned();
            $table->foreign('prod_aop_batch_id')->references('id')->on('prod_aop_batches')->onDelete('cascade');
            
            $table->integer('production_process_id')->unsigned();
            $table->foreign('production_process_id')->references('id')->on('production_processes');
            $table->unsignedInteger('asset_quantity_cost_id')->nulable();
            $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs');
            $table->unsignedInteger('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('employee_h_rs');
            $table->unsignedInteger('shift_id')->nullable();
            $table->date('prod_date');

            $table->unsignedSmallInteger('sort_id')->unsigned();
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
        Schema::dropIfExists('prod_aop_batch_processes');
    }
}

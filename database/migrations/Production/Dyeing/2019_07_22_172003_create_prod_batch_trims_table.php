<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdBatchTrimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_batch_trims', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('prod_batch_id')->unsigned();
            $table->foreign('prod_batch_id')->references('id')->on('prod_batches')->onDelete('cascade');
            
            $table->integer('itemclass_id')->unsigned();
            $table->foreign('itemclass_id')->references('id')->on('itemclasses');
            $table->decimal('qty',14,4);
            $table->integer('uom_id')->unsigned();
            $table->foreign('uom_id')->references('id')->on('uoms');
            $table->decimal('wgt_per_unit',14,4);
            $table->decimal('wgt_qty',14,4);
            $table->string('remarks',500)->nullable();
            $table->integer('root_batch_trim_id')->unsigned();
            $table->foreign('root_batch_trim_id')->references('id')->on('prod_batch_trims');
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
        Schema::dropIfExists('prod_batch_trims');
    }
}

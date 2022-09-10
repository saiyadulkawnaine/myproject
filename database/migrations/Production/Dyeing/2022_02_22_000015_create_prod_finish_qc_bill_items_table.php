<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdFinishQcbillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_finish_qc_bill_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('prod_finish_dlv_id');
            $table->foreign('prod_finish_dlv_id','prodfindlvid')->references('id')->on('prod_finish_dlvs');
            ///////$table->unsignedInteger('autoyarn_id');
            $table->integer('so_dyeing_fabric_rcv_item_id')->nullable()->unsigned();
            $table->foreign('so_dyeing_fabric_rcv_item_id','fabricRcvItemId')->references('id')->on('so_dyeing_fabric_rcv_items')->onDelete('cascade');
            // $table->integer('prod_batch_id')->unsigned();
            // $table->foreign('prod_batch_id','prodbatchId')->references('id')->on('prod_batches')->onDelete('cascade');
            $table->integer('prod_batch_finish_qc_id')->unsigned();
            $table->foreign('prod_batch_finish_qc_id','prodfinishqcID')->references('id')->on('prod_batch_finish_qcs');
            $table->string('process_name')->nullable();
            $table->decimal('amount', 14, 4);
            $table->decimal('qty', 14, 4);
            $table->decimal('rate', 10, 4);
            $table->unsignedInteger('no_of_roll')->nullable();
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
        Schema::dropIfExists('prod_finish_qc_bill_items');
    }
}

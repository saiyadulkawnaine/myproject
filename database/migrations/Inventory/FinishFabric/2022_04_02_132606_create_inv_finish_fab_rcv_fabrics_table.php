<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvFinishFabRcvFabricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_finish_fab_rcv_fabrics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_finish_fab_rcv_id');
            $table->foreign('inv_finish_fab_rcv_id')->references('id')->on('inv_finish_fab_rcvs')->onDelete('cascade');
            $table->unsignedInteger('po_fabric_item_id');
            $table->foreign('po_fabric_item_id')->references('id')->on('po_fabric_items')->onDelete('cascade');
            $table->string('req_dia');
            $table->unsignedInteger('fabric_color_id');
            $table->foreign('fabric_color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->unsignedInteger('sales_order_id');
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');
            $table->unsignedInteger('colorrange_id');
            $table->unsignedInteger('gsm_weight');
            $table->string('dia');
            $table->decimal('stitch_length',12,4);
            $table->decimal('shrink_per',12,4);
            $table->decimal('qty',12,4);
            $table->decimal('rate',12,4);
            $table->decimal('amount',12,4);
            $table->string('remarks',500)->nullable();
            
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_ip', 20)->nullable();
            $table->string('updated_ip', 20)->nullable();
            $table->string('deleted_ip', 20)->nullable();
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
        Schema::dropIfExists('inv_finish_fab_rcv_fabrics');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvYarnRcvItemSosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_yarn_rcv_item_sos', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_yarn_rcv_item_id');
            $table->foreign('inv_yarn_rcv_item_id')->references('id')->on('inv_yarn_rcv_items')->onDelete('cascade');

            $table->unsignedInteger('po_yarn_item_bom_qty_id');
            $table->foreign('po_yarn_item_bom_qty_id')->references('id')->on('po_yarn_item_bom_qties');
                        
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('store_qty',14,4)->nullable();
            $table->decimal('store_rate',14,4)->nullable();
            $table->decimal('store_amount',14,4)->nullable();
            $table->string('remarks', 500)->nullable();

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
        Schema::dropIfExists('inv_yarn_rcv_item_sos');
    }
}

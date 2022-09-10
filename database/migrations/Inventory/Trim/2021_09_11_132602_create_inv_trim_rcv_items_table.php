<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvTrimRcvItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_trim_rcv_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('inv_trim_rcv_id');
            $table->foreign('inv_trim_rcv_id')->references('id')->on('inv_trim_rcvs')->onDelete('cascade');

            $table->unsignedInteger('po_trim_item_report_id');
            $table->foreign('po_trim_item_report_id')->references('id')->on('po_trim_item_reports');
            
            $table->unsignedInteger('inv_trim_item_id');
            $table->foreign('inv_trim_item_id','invtrimitemid')->references('id')->on('inv_trim_items');
            $table->unsignedInteger('store_id');

            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);

            $table->decimal('store_qty',14,4);
            $table->decimal('store_rate',14,4);
            $table->decimal('store_amount',14,4);

            $table->string('room', 100)->nullable();
            $table->string('rack', 100)->nullable();
            $table->string('shelf', 100)->nullable();
            $table->string('remarks', 500)->nullable();
            $table->unsignedInteger('inv_trim_isu_item_id')->nullable();
            //$table->foreign('inv_trim_isu_item_id')->references('id')->on('inv_trim_isu_items');

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
        Schema::dropIfExists('inv_trim_rcv_items');
    }
}

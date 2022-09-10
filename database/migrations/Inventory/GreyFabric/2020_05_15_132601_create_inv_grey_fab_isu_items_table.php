<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvGreyFabIsuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_grey_fab_isu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_isu_id');
            $table->foreign('inv_isu_id')->references('id')->on('inv_isus')->onDelete('cascade');
            $table->unsignedInteger('inv_grey_fab_item_id');
            $table->foreign('inv_grey_fab_item_id','inv_grey_fab_item_id_1')->references('id')->on('inv_grey_fab_items');
            $table->unsignedInteger('inv_grey_fab_rcv_item_id');
            $table->foreign('inv_grey_fab_rcv_item_id','inv_grey_fab_rcv_item_id_1')->references('id')->on('inv_grey_fab_rcv_items');

            $table->unsignedInteger('store_id');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4)->nullable();
            $table->decimal('amount',14,4)->nullable();
            $table->decimal('returnable_qty',14,4)->nullable();
            $table->decimal('returned_qty',14,4)->nullable();
            $table->string('remarks', 500)->nullable();

            

            $table->unsignedInteger('style_id')->nullable();
            $table->foreign('style_id')->references('id')->on('styles');

            $table->unsignedInteger('style_sample_id')->nullable();
            $table->foreign('style_sample_id')->references('id')->on('style_samples');
            
            $table->unsignedInteger('sale_order_id')->nullable();
            $table->foreign('sale_order_id')->references('id')->on('sales_orders');

            

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
        Schema::dropIfExists('inv_grey_fab_isu_items');
    }
}

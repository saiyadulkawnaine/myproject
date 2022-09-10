<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvYarnIsuRtnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_yarn_isu_rtn_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_yarn_isu_rtn_id');
            $table->foreign('inv_yarn_isu_rtn_id')->references('id')->on('inv_yarn_isu_rtns')->onDelete('cascade');
            $table->unsignedInteger('inv_yarn_item_id');
            $table->foreign('inv_yarn_item_id','invyarnitemid_isu_rtn')->references('id')->on('inv_yarn_items');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('sales_order_id');
            $table->foreign('sales_order_id','salesorderid_isu_rtn')->references('id')->on('sales_orders');
            $table->unsignedInteger('cone_per_bag')->nullable();
            $table->decimal('wgt_per_cone',14,4)->nullable();
            $table->decimal('wgt_per_bag',14,4)->nullable();
            $table->unsignedInteger('no_of_bag')->nullable();

            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4)->nullable();

            $table->decimal('loose_cone_wgt',14,4)->nullable();
            $table->string('room', 500)->nullable();
            $table->string('rack', 500)->nullable();
            $table->string('shelf', 500)->nullable();
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
        Schema::dropIfExists('inv_yarn_isu_rtn_items');
    }
}

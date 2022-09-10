<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvYarnRcvItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_yarn_rcv_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();

            //$table->unsignedInteger('inv_rcv_id');
            //$table->foreign('inv_rcv_id')->references('id')->on('inv_rcvs')->onDelete('cascade');

            $table->unsignedInteger('inv_yarn_rcv_id');
            $table->foreign('inv_yarn_rcv_id')->references('id')->on('inv_yarn_rcvs')->onDelete('cascade');
            $table->unsignedInteger('po_yarn_item_id');
            $table->foreign('po_yarn_item_id')->references('id')->on('po_yarn_items');
            
            $table->unsignedInteger('inv_yarn_item_id');
            $table->foreign('inv_yarn_item_id')->references('id')->on('inv_yarn_items');

            /*$table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->unsignedInteger('color_id');
            $table->string('lot');
            $table->string('brand');*/
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('cone_per_bag');
            $table->decimal('wgt_per_cone', 14, 4);      
            $table->decimal('wgt_per_bag', 14, 4)->nullable();      
            $table->unsignedInteger('no_of_bag');

            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);

            $table->decimal('store_qty',14,4);
            $table->decimal('store_rate',14,4);
            $table->decimal('store_amount',14,4);

            $table->decimal('ile_percent',14,4)->nullable();
            $table->decimal('ile_a',14,4)->nullable();
            $table->decimal('ile_b',14,4)->nullable();
            $table->decimal('ile_c',14,4)->nullable();
            $table->decimal('ile_d',14,4)->nullable();
            $table->decimal('ile_e',14,4)->nullable();
            $table->decimal('ile_f',14,4)->nullable();
            $table->decimal('used_yarn',14,4)->nullable();
            $table->unsignedInteger('inv_yarn_isu_item_id')->nullable();
            $table->foreign('inv_yarn_isu_item_id')->references('id')->on('inv_yarn_isu_items');
            $table->decimal('yarn_dyeing_rate',14,4)->nullable();
            $table->unsignedInteger('sales_order_id');
            $table->foreign('sales_order_id','salesorderid_rcv')->references('id')->on('sales_orders');

            
            $table->string('room', 100)->nullable();
            $table->string('rack', 100)->nullable();
            $table->string('shelf', 100)->nullable();
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
        Schema::dropIfExists('inv_yarn_rcv_items');
    }
}

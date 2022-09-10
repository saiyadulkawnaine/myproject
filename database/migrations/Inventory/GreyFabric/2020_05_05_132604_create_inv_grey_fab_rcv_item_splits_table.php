<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvGreyFabRcvItemSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_grey_fab_rcv_item_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_grey_fab_rcv_item_id');
            $table->foreign('inv_grey_fab_rcv_item_id','invgreyfabrcvitemspid')->references('id')->on('inv_grey_fab_rcv_items')->onDelete('cascade');
            $table->unsignedInteger('root_rcv_item_id');
            $table->unsignedInteger('prod_knit_item_roll_id');
            $table->foreign('prod_knit_item_roll_id','prodknititemrollspid')->references('id')->on('prod_knit_item_rolls');
            $table->unsignedInteger('root_roll_id');
            $table->string('custom_no');

            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);

            $table->decimal('store_qty',14,4);
            $table->decimal('store_rate',14,4);
            $table->decimal('store_amount',14,4);
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
        Schema::dropIfExists('inv_grey_fab_rcv_item_splits');
    }
}

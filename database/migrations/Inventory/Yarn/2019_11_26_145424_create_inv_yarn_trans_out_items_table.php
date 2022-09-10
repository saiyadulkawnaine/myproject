<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvYarnTransOutItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_yarn_trans_out_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inv_yarn_trans_out_id');
            $table->foreign('inv_yarn_trans_out_id')->references('id')->on('inv_yarn_trans_outs')->onDelete('cascade');
            $table->unsignedInteger('inv_yarn_item_id');
            $table->foreign('inv_yarn_item_id','invyarnitemid_transout')->references('id')->on('inv_yarn_items');
            $table->unsignedInteger('store_id');
            $table->decimal('qty',14,4)->nullable();
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4)->nullable();
            $table->string('room', 500)->nullable();
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
        Schema::dropIfExists('inv_yarn_trans_out_items');
    }
}

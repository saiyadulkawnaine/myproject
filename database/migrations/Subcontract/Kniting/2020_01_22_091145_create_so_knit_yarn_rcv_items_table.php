<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoKnitYarnRcvItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_knit_yarn_rcv_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_knit_yarn_rcv_id')->nullable()->unsigned();
            $table->foreign('so_knit_yarn_rcv_id')->references('id')->on('so_knit_yarn_rcvs')->onDelete('cascade');
            
            $table->integer('item_account_id')->nullable()->unsigned();
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->string('lot',100)->nullable();
            $table->string('supplier_name',250)->nullable();
            $table->integer('color_id',100)->unsigned();
            $table->integer('uom_id')->nullable()->unsigned();
            $table->foreign('uom_id')->references('id')->on('uoms');
            $table->decimal('qty',14,4);
            $table->decimal('rate',14,4);
            $table->decimal('amount',14,4);
            $table->decimal('process_loss_per',14,4);
            $table->decimal('real_rate',14,4);
            
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
        Schema::dropIfExists('so_knit_yarn_rcv_items');
    }
}

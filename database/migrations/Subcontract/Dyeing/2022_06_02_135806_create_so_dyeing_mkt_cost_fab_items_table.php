<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoDyeingMktCostFabItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_dyeing_mkt_cost_fab_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_dyeing_mkt_cost_fab_id');
            $table->foreign('so_dyeing_mkt_cost_fab_id','dyeingmktcostfabID')->references('id')->on('so_dyeing_mkt_cost_fabs')->onDelete('cascade');
            $table->unsignedInteger('sub_process_id')->nullable();
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id','fabitemaccountID')->references('id')->on('item_accounts');
            $table->decimal('per_on_fabric_wgt',10,4)->nullable();
            $table->decimal('gram_per_ltr_liqure',10,4)->nullable();
            $table->decimal('qty',12,4);
            $table->decimal('rate',12,4);
            $table->decimal('amount',14,4);
            $table->decimal('last_rcv_rate',12,4);
            $table->unsignedInteger('last_receive_no');
            $table->unsignedSmallInteger('sort_id')->nullable();
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
        Schema::dropIfExists('so_dyeing_mkt_cost_fab_items');
    }
}

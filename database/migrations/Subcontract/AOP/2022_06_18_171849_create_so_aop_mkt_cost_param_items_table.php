<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoAopMktCostParamItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_aop_mkt_cost_param_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('so_aop_mkt_cost_param_id');
            $table->foreign('so_aop_mkt_cost_param_id')->references('id')->on('so_aop_mkt_cost_params')->onDelete('cascade');
            $table->unsignedInteger('sub_process_id')->nullable();
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->decimal('rto_on_paste_wgt',14,4);
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
        Schema::dropIfExists('so_aop_mkt_cost_param_items');
    }
}

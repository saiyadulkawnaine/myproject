<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvGeneralIsuRqItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_general_isu_rq_items', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('inv_general_isu_rq_id');
            $table->foreign('inv_general_isu_rq_id')->references('id')->on('inv_general_isu_rqs')->onDelete('cascade');
            $table->unsignedInteger('item_account_id');
            $table->foreign('item_account_id')->references('id')->on('item_accounts');
            $table->integer('department_id')->nullable()->unsigned();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unsignedInteger('sale_order_id')->nullable();
            $table->foreign('sale_order_id')->references('id')->on('sales_orders');
            $table->unsignedInteger('asset_quantity_cost_id')->nulable();
            $table->foreign('asset_quantity_cost_id')->references('id')->on('asset_quantity_costs');
            $table->unsignedInteger('purpose_id');
            $table->decimal('qty',14,4);
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
        Schema::dropIfExists('inv_general_isu_rq_items');
    }
}
